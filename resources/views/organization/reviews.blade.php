@extends('Front_layouts.org')

@section('head')
<style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
    @vite('resources/css/review.css')
@endsection
@section('maincontent')
<div class="container">
    <div class="container-top"></div>
        <div class="container-bottom">
            <div class="container-bottom-left">
                <div class="left-container">
                    <div class="left-one">
                        <h3>PBEN Organization</h3>
                    </div>
                    <div class="left-two">
                        <hr>
                    </div>
                    <div class="left-three"><i class="fa-solid fa-basket-shopping left-icon"></i><a href="{{ route('organization.dashboard') }}">Products</a></div>
                    <div class="left-four"><i class="fa-solid fa-list-check left-icon"></i><a href="{{ route('order.page') }}">Orders</a></div>
                    <div class="left-five"><i class="fa-solid fa-star-half-stroke left-icon"></i><span class="currentPage">Reviews</span></div>
                    <div class="left-six"><i class="fa-solid fa-money-check-dollar left-icon"></i><a href="{{ route('org.report') }}">Dashboard</a></div>
                </div>
            </div>
            <div class="container-bottom-right">
                <div class="card-placeholder">
                    @foreach($reviews as $review)
                    <div class="card">
                        <div class="left">
                            <div class="img-placeholder">
                                <img src="{{asset('images/'. $review->first_image)}}" alt="">
                            </div>
                        </div>
                        <div class="right">
                            <div class="header">
                                <div class="image-placeholder">
                                    <img src="{{asset('storage/users-avatar/'. $review->avatar)}}" alt="">
                                </div>
                                
                                <div class="user-contents">
                                    <div class="header-name">
                                    <h3>{{$review->name}} {{$review->last_name}}</h3>
                                    <p>{{ $review->formatted_date }}</p>
                                </div>
                                <div class="star" style="color:gold;">
                                    @if($review->rating == 1)
                                        &starf;&#9734;&#9734;&#9734;&#9734;
                                    @elseif($review->rating == 2)
                                        &starf;&starf;&#9734;&#9734;&#9734;
                                    @elseif($review->rating == 3)
                                        &starf;&starf;&starf;&#9734;&#9734;
                                    @elseif($review->rating == 4)
                                        &starf;&starf;&starf;&starf;&#9734;
                                    @elseif($review->rating == 5)
                                        &starf;&starf;&starf;&starf;&starf;
                                    @endif
                                </div>
                                </div>
                            </div>
                            <div class="message">
                                <p>{{ $review->comment }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            </div>
</div>
@endsection