<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    @vite('resources/css/review.css')
    @vite('resources/js/review.js')
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
    <!-- Font Awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
     <!-- nav bar -->
        <div class="navBar">
            <div class="navBarLeft">
                <img src="{{ asset('img/logo.svg') }}" alt="" />
            </div>
            <div class="navBarRight">
                <img class="hover faqsBtn" src="{{ asset('img/help.png') }}" alt="" />
                <div class="dropdown-container">
                    <img
                        class="hover notificationBtn"
                        src="{{ asset('img/notif.png') }}"
                        alt=""
                    />
                    <div
                        class="notification-dropdown"
                        id="notificationDropdown"
                        style="display: none"
                    >
                        <div class="notification-header">
                            <h3>Notifications</h3>
                        </div>
                        <div class="notification-list">
                            @if ($notifications->isEmpty())
                            <p style="padding-left: 10px">No notifications</p>
                            @else @foreach ($notifications as $notification)
                            <div class="notification">
                                <div class="title">
                                    <h1>
                                        @if($notification['title'] === "Product
                                        Approved")
                                        <span style="color: Green"
                                            >{{ $notification['title'] }}</span
                                        >
                                        @elseif($notification['title'] ===
                                        "Product Rejected")
                                        <span style="color: red"
                                            >{{ $notification['title'] }}</span
                                        >
                                        @else {{ $notification['title'] }}
                                        @endif
                                    </h1>
                                </div>
                                <div class="Message">
                                    {{ $notification['message'] }}
                                </div>
                                <div class="time">
                                    {{ $notification['time_ago'] }}
                                </div>
                            </div>
                            @endforeach @endif
                        </div>
                    </div>
                </div>
                <div class="dropdown-container">
                    <img
                        class="hover profileBtn"
                        src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}"
                        alt=""
                    />
                    <div
                        class="profile-dropdown"
                        id="profileDropdown"
                        style="display: none"
                    >
                        <ul>
                            <li><a href="">My Profile</a></li>
                            <li><a href=" ">Wishlist</a></li>
                            <li><a href="">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- nav bar -->
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
                                <i class="fa-solid fa-pencil"></i> Buy Orders
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
            // notifs
            document.addEventListener("DOMContentLoaded", function () {
                const notifBtn = document.querySelector(".notificationBtn");
                const notifDropdown = document.getElementById(
                    "notificationDropdown"
                );
                const profileBtn = document.querySelector(".profileBtn");
                const profileDropdown =
                    document.getElementById("profileDropdown");
                const closeNotif = document.querySelector(".closeButton");
                let category = "featured";

                document
                    .querySelectorAll(".mainFilterButtons")
                    .forEach((button) => {
                        button.addEventListener("click", () => {
                            // Remove 'current' from all filter buttons
                            document
                                .querySelectorAll(".mainFilterButtons")
                                .forEach((btn) => {
                                    btn.classList.remove("current");
                                });
                            let url = "?page=${page}";
                            button.classList.add("current");

                            category = button.dataset.category;
                            console.log("Clicked category:", category);
                            updateFilters();
                        });
                    });
                notifBtn.addEventListener("click", function () {
                    notifDropdown.style.display =
                        notifDropdown.style.display === "none"
                            ? "block"
                            : "none";
                    profileDropdown.style.display = "none";
                    console.log("clicked");
                });

                profileBtn.addEventListener("click", function () {
                    profileDropdown.style.display =
                        profileDropdown.style.display === "none"
                            ? "block"
                            : "none";
                    notifDropdown.style.display = "none"; // close notifications if open
                });

                // Optional: Close dropdowns if clicked outside
                window.addEventListener("click", function (e) {
                    if (!e.target.closest(".dropdown-container")) {
                        notifDropdown.style.display = "none";
                        profileDropdown.style.display = "none";
                    }
                });
            });
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
</body>
</html>