@extends('Front_layouts.default')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>


    @vite('resources/css/profile.css')
@endsection

@section('maincontent')
    <div class="mainContainer">
        <div class="top">
            <h1>Profile</h1>
        </div>
        <div class="container">
            <div class="leftPart">
                <div class="leftPartItems">
                    <div class="profilePlace_profile"><img class="profile_link_profile"
                            src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt=""
                            id="nav-profile"></div>
                    <h3 class="h3">{{ Auth::user()->name }}</h3>
                </div>
                <hr>
                <div class="leftPartItems2">
                    <a href="{{ route('profile.page') }}" class="current">My Account</a>
                    <a href="{{ route('profileListings.page') }}">My Listings</a>
                    <a href="{{ route('vouchers.page') }}">My Vouchers</a>
                </div>
            </div>
            <div class="rightPart">
                <div class="categories">
                    <button class="btn-filter active" data-tab="all">All</button>
                    <button class="btn-filter" data-tab="pending">Pending</button>
                    <button class="btn-filter" data-tab="receive">To recieve</button>
                    <button class="btn-filter" data-tab="cancelled">Cancelled</button>
                    <button class="btn-filter" data-tab="completed">Completed</button>
                </div>
                <div class="itemsContainer" id="itemsContainer">

                    @include('partials.profileProduct', ['cartItems' => $items])



                </div>
            </div>
        </div>
        <script>
            const buttons = document.querySelectorAll('.btn-filter');
            //para pag ka load gana agad all filter
            window.addEventListener('DOMContentLoaded', () => {
                fetchFilter('all');
            });
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove .active from all buttons para isang button lagn active
                    buttons.forEach(btn => btn.classList.remove('active'));

                    // Add .active sa clicked button
                    button.classList.add('active');

                    const tab = button.getAttribute('data-tab');
                    fetchFilter(tab);
                });
            });

            function fetchFilter(tab) {
                let url = "?filter=";
                if (tab) {
                    url += tab;
                }
                // kukunin current url tapos dadagdag yung let url tapos send sa sarili so mapunta sa route.
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    //basically converts yung raw HTTP Req to Html tapos nilalagay sa id div ko
                    .then(response => response.text())
                    .then(data => {
                        const itemsContainer = document.getElementById('itemsContainer');
                        itemsContainer.innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Error fetching filtered products:', error);
                    })
            }
        </script>
    @endsection
