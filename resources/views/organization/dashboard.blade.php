@extends('Front_layouts.org')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Admin Page</title>
    @vite('resources/css/admin-org.css')
@endsection
@section('maincontent')
    <div class="container">
        <div class="left">
            <div class="left-container">
                <div class="left-one"><h3>PBEN Organization</h3></div>
                <div class="left-two"><hr></div>
                <div class="left-three"><i class="fa-solid fa-basket-shopping left-icon"></i>Products</div>
                <div class="left-four"><i class="fa-solid fa-list-check left-icon"></i>Orders</div>
                <div class="left-five"><i class="fa-solid fa-star-half-stroke left-icon"></i>Reviews</div>
                <div class="left-six"><i class="fa-solid fa-money-check-dollar left-icon"></i>Sales</div>
                <div class="left-seven"><i class="fa-solid fa-gear left-icon"></i>Settings</div>
            </div>
        </div>
        <div class="right">
            <div class="right-top">
                <div class="nav-container-top">
                    <div class="nav-top-one"></div>
                    <div class="nav-top-two">Name</div>
                    <div class="nav-top-three">Stock</div>
                    <div class="nav-top-four">Price</div>
                    <div class="nav-top-five"><div class="add-listing">
                        <a class="listing_link"href="{{ route('create.listing') }}"><h3>Add Product</h3></a>
                     </div></div>
                    
                    
                </div>
                
            </div>
            <div class="right-bottom">
                <div class="card-container">
                    @include('partials.adminProducts', ['products' => $products])
                    
                </div>
            </div>
        </div>
    </div>
@endsection