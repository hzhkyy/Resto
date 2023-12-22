<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ViewController extends Controller
{
    public function tampilHome()
    {

        $carts = Cart::all();
        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice +=  $cart->menu->harga * $cart->qty;
        }

        $api_url = 'https://menurestoranapi-production.up.railway.app/api/menu/get-all-menu';

        // Menggunakan Http::get() untuk melakukan GET request ke API
        $response = Http::get($api_url);

        // Mendapatkan body dari response
        $json_data = json_decode($response);

        return view('home', ['json_data' => $json_data, 'carts' => $carts, 'totalPrice' => $totalPrice]);
    }

    public function addToCart(Request $request, $id)
    {
        $api_url = 'https://pemesananapi-resto-production.up.railway.app/api/menu/add-to-cart/'. $id;

        $response = Http::post($api_url, [
            'qty' => $request->qty
        ]);
        // Mendapatkan body dari response
        $json_data = json_decode($response);

        return redirect('/home');
        // foreach($json_data->status as $item){
        //     if ($item->status = "error") {
        //         return redirect('/home');
        //     }

        // }

        return view('home', ['json_data' => $json_data]);
    }

    public function checkout()
    {

        $api_url = 'http://127.0.0.1:8000/api/payments';

        // Menggunakan Http::get() untuk melakukan GET request ke API
        $response = Http::get($api_url);

        // Mendapatkan body dari response
        $json_data = json_decode($response);
        $checkoutLink = $json_data['checkout_link'];
        dd($checkoutLink);

        return Redirect::to($checkoutlink);
    }

    public function store(Request $request) {

        global $apiInstance;
        $create_invoice_request = new \Xendit\Invoice\CreateInvoiceRequest([
            'external_id' => (string) Str::uuid(),
            'description' => 'Pemesanan di Delicious Resto',
            'amount' => 15000,
            // 'payer_email' => $request->payer_email,
          ]);

          $result = $apiInstance->createInvoice($create_invoice_request);

          //save to db
          return Redirect::to($result['invoice_url']);
    }

}
