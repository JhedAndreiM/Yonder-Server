@extends('Front_layouts.app')

@section('title', 'Reviews')
@section('head')
@vite('resources/css/review.css')
@vite('resources/js/review.js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('content')
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
                    <div class="reviews-container">
  <!-- Search -->
<div class="search-bar">
  <input type="text" id="search-input" placeholder="Search by reviewer or product..." />
</div>
<div id="reviews-container">
  <!-- Review Card -->
@include('partials.reviewCards', ['reviews' => $reviews])
</div>
</div>
                </div>
            </div>
        </div>
        <!-- nav bar -->
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll(".review-card").forEach(card => {
                    const text = card.querySelector(".review-text");
                    const button = card.querySelector(".see-more");

                    if (!text || !button) return; // skip if missing

                    // Get line height in pixels
                    const lineHeight = parseFloat(getComputedStyle(text).lineHeight);
                    const maxHeight = lineHeight * 3;

                    // If the rendered box is taller than 3 lines worth of space, show button
                    if (text.scrollHeight > maxHeight + 1) {
                    button.style.display = "inline";
                    }

                    button.addEventListener("click", () => {
                    text.classList.toggle("expanded");

                    if (text.classList.contains("expanded")) {
                        button.textContent = "See less";
                    } else {
                        button.textContent = "See more";
                    }
                    });
                });
                const faqsBtn = document.querySelectorAll('.faqsBtn');
                faqsBtn.forEach(button=>{
                button.addEventListener('click', function(){
                    window.location.href= "{{route('FAQs')}}";
                    
                })
            });
            });

        </script>
@endsection