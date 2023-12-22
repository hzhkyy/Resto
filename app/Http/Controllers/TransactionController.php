<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Xendit\Configuration;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Facades\Redirect;

class TransactionController extends Controller
{
    var $apiInstance = null;


    public function __construct() {
        global $apiInstance;
        Configuration::setXenditKey("xnd_development_0CJnEAmovQfpGS6QJPy9JXJxNRz8wMbXkBYaR3PC6xXnRsHTvQFETRFxZGr7r");
        $apiInstance = new InvoiceApi();
    }

    public function payment(Request $request) {
        global $apiInstance;

        $totalAmount = 0;
        $carts = Cart::all();

        foreach ($carts as $cart) {
            $totalAmount +=  $cart->menu->harga * $cart->qty;
        }

        try {
            $create_invoice_request = new \Xendit\Invoice\CreateInvoiceRequest([
                'external_id' => (string) Str::uuid(),
                'description' => 'Pemesanan di Delicious Resto',
                'amount' => $totalAmount
            ]);

            $result = $apiInstance->createInvoice($create_invoice_request);
            // dd($totalAmount);

            // Simpan ke database
            $payment = new Transaction();
            $payment->pegawai = 1;
            $payment->status = 'pending';
            $payment->total_amount = $totalAmount;
            $payment->external_id = $create_invoice_request['external_id'];
            $payment->save();

            // Respons JSON
            return response()->json([
                'status' => true,
                'checkout_link' => $result['invoice_url']
            ], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan pembuatan faktur
            $errorMessage = 'Gagal membuat faktur.';

            // Respons JSON dengan status HTTP 500 (Internal Server Error)
            return response()->json([
                'status' => 'error',
                'error' => $errorMessage
            ], 500);
        }

        //   return Redirect::to($payment->checkout_link);
    }

    public function paymentSettled(Request $request) {
        try {
            global $apiInstance;
            $result = $apiInstance->getInvoices(null, $request->external_id);

            //get data
            $payment = Transaction::where('external_id', $request->external_id)->firstOrFail();

            if ($payment->status == 'settled') {
            return response()->json([
                'status' => true,
                'message' => 'Pembayaran telah dilakukan'
            ], 200);
            }

            $payment->status = strtolower($result[0]['status']);
            $payment->save();

            Cart::truncate();

            return response()->json([
                'status' => true,
                'message' => 'Pembayaran berhasil'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => 'Terjadi kesalahan.'
            ], 500);
        }
    }
}
