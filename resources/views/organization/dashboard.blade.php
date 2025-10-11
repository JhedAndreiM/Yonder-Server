@extends('Front_layouts.app')

@section('title', 'PBEN Dashboard')
@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@vite('resources/css/admin-org.css')
@vite('resources/js/org.dashboard.js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('content')
<!-- Include Recent Chats Panel -->
    @include('partials.chat-recent')
    <div class="floating">
      <a href="#" id="chatWidgetToggle"><img src="{{ asset('img/message.png') }}" alt="" /></a>
    </div>
    <div class="mainContainer">
            <div class="container">
                <div class="containerLeft">
                    <ul class="sidebar-menu">
                        <li>
                            <a href="{{ route('organization.dashboard') }}" 
                            class="{{ request()->routeIs('organization.dashboard') ? 'active' : '' }}">
                                <i class="fa-solid fa-cart-shopping"></i> My Products
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('order.page') }}" 
                            class="{{ request()->routeIs('order.page') ? 'active' : '' }}">
                                <i class="fa-solid fa-pencil"></i> Product Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('review.page') }}" 
                            class="{{ request()->routeIs('review.page') ? 'active' : '' }}">
                                <i class="fa-solid fa-star"></i> Product Reviews
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('org.report') }}" 
                            class="{{ request()->routeIs('org.report') ? 'active' : '' }}">
                                <i class="fa-solid fa-paperclip"></i> Inventory
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('create.listing') }}" 
                            class="{{ request()->routeIs('create.listing') ? 'active' : '' }}">
                                <i class="fa-solid fa-plus"></i> Add Product
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="containerRight">
                    <div class="filter-section">
                        <div class="welcome-header">
                            <div class="welcome-content">
                                <div class="greeting-text">
                                    <span class="greeting">Welcome back,</span>
                                    <h2 class="user-name">{{ Auth::user()->name }}!</h2>
                                </div>
                            </div>
                            <div class="filter-controls">
                                <label for="sort-by" class="filter-label">
                                    <i class="fa-solid fa-filter"></i>
                                    Filter by:
                                </label>
                                <select name="sort-filter" id="sort-by">
                                    <option value="all">All Products</option>
                                    <option value="pben">PBEN Products</option>
                                    <option value="student-org">Student Organization Products</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-container" id="card-container">
                        @include('partials.adminProducts', ['products' => $products])
                    </div>
                </div>
            </div>
        </div>
@endsection