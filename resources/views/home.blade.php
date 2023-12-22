<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Restoran</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="/home">Delicious Resto</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#!">All Products</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                                <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                            </ul>
                        </li> --}}
                    </ul>
                    <div class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#cart">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill">1</span>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Delicious Resto</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Menu</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    @foreach($json_data->menus as $menu)
                    <div class="col mb-5">
                        <div class="col mb-5">
                            <form action="/add-to-cart/{{ $menu->id }}" method="POST">
                                @csrf
                                <div class="card h-100">
                                    <!-- Product image-->
                                    <img class="card-img-top" src="" alt="..." />
                                    <!-- Product details-->
                                    <div class="card-body p-4">
                                        <div class="text-center">
                                            <!-- Product name-->
                                            <h5 class="fw-bolder">{{ $menu->nama }}</h5>
                                            <!-- Product price-->
                                            <div>
                                                {{ $menu->deskripsi }}
                                            </div>

                                        </div>
                                        <p class="text-muted mb-2">Stok : {{ $menu->stok }}</p>
                                        <p class="text-muted mb-2">Harga : {{ $menu->harga }}</p>
                                        <p class="text-muted mb-2 ">Qty</p>
                                        <input type="number" class="qty-input" name="qty" min="1">
                                    </div>
                                    <!-- Product actions-->
                                    <div class="card-footer pt-0 border-top-0 bg-transparent">
                                        <div class="row">
                                            <div class="col">
                                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#"><i class="bi bi-eye"></i></a></div>
                                            </div>
                                            <div class="col">

                                                    <button type="submit" class="btn btn-outline-dark mt-auto"><i class="bi bi-cart-plus"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>
        </section>
        {{-- modal --}}
        <div class="modal fade" id="cart" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cartLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="closingLabel">Cart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
            <div class="modal-body">
                <div class="center">
                    <div class="d-flex align-items-center">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">produk</th>
                                    <th scope="col">jumlah</th>
                                    <th scope="col">harga</th>
                                    <th scope="col">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($carts as $cart)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>
                                                <div>{{ $cart->menu->nama }}</div>
                                            </td>
                                            <td>
                                                <div class="text-center">{{ $cart->qty }}</div>
                                            </td>
                                            <td>
                                                <div class="text-center">{{ $cart->menu->harga }}</div>
                                            </td>
                                            <td>
                                                <a href="#" title="Delete"><i class="bi bi-trash-fill"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <h5>TOTAL : Rp {{ number_format($totalPrice) }}</h5>
                            {{-- <h5>TOTAL : Rp. {{ number_format($totalPrice) }}</h5> --}}
                            {{-- <input type="text" hidden name="total" id="total" value="{{ $totalPrice }}"> --}}
                            <form method="GET" action="/payments">
                                @csrf

                                <input type="text" hidden name="total" id="total" value="15000">

                                <button class="btn btn-primary" type="submit">Checkout</button>
                            </form>

                        {{-- <a href="/payments"><p class="btn btn-primary">Checkout</p></a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Restoran 2023</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
