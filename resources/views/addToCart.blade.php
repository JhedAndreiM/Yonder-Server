<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    @vite('resources/css/addToCart.css')
    <style>
      body {
      background-image: url("{{ asset('img/background.svg') }}");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: top center;
      }
  </style>
</head>
<body>
              <!-- nav bar -->

    <div class="navBar">
      <div class="navBarLeft" id="logoClick"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>

      <div class="navBarRight">
        <img class="hover faqsBtn" src="{{ asset('img/help.png') }}" alt="" />
        <div class="dropdown-container">
    <img class="hover notificationBtn" src="{{ asset('img/notif.png') }}" alt="" />
    <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
      <div class="notification-header">
        <h3>Notifications</h3>
      </div>
      <div class="notification-list">
        @if ($notifications->isEmpty())
          <p style="padding-left:10px;">No notifications</p>
        @else
          @foreach ($notifications as $notification)
            <div class="notification">
              <div class="title">
                <h1>
                  @if($notification['title'] === "Product Approved")
                    <span style="color:Green;">{{ $notification['title'] }}</span>
                  @elseif($notification['title'] === "Product Rejected")
                    <span style="color:red;">{{ $notification['title'] }}</span>
                  @else
                    {{ $notification['title'] }}
                  @endif
                </h1>
              </div>
              <div class="Message">{{ $notification['message'] }}</div>
              <div class="time">{{ $notification['time_ago'] }}</div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
        <a href="{{ route('show.wishlist') }}">
            <img class="hover" src="{{ asset('img/wishlist.png') }}" alt="Wishlist"/>
        </a>
        <a href="{{ route('show.cart') }}">
            <img class="hover" src="{{ asset('img/cart.png') }}" alt="Cart"/>
        </a>
          <div class="dropdown-container">
    <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
    <div class="profile-dropdown" id="profileDropdown" style="display: none;">
      <ul>
        <li data-url="{{ route('student.profile') }}">My Profile</li>
        <li data-url="{{ route('account.page') }}">Settings</li>
        <li data-url="{{ route('logout') }}">Logout</li>
      </ul>
    </div>
  </div>
      </div>
    </div>

    <!-- nav bar -->
    <div class="mainContainer">
        <div class="top">
          <h1>My Cart</h1>
        </div>
        <div class="container">
          @include('partials.productCart', ['cartItems' => $cartItems])
        </div>
        <div class="total-bottom">
          <div class="bottom-container"style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p class="totalPerItems" style="font-weight: bold;">Items: {{ $totalItems }}</p>
                <p style="font-weight: bold;">Total: P {{ number_format($totalAmount, 2) }}</p>
            </div>
            <form action="{{ route('cart.checkoutAll') }}" method="POST">
                @csrf
                <button class="checkOutBtn"type="submit" >
                    Checkout All
                </button>
            </form>
        </div>
        </div>
      </div>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.cart-item').forEach(item => {
        const decreaseBtn = item.querySelector('.decrease');
        const increaseBtn = item.querySelector('.increase');
        const input = item.querySelector('.quantity');
        const stock = parseInt(item.dataset.stock);
        const id = item.dataset.id;
        let debounceTimer;

        function debounceUpdateQuantity(id, value){
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                updateQuantity(id, value);
            }, 300);
        }

        decreaseBtn.addEventListener('click', () => {
            if (parseInt(input.value) > 1) {
                input.value--;
                debounceUpdateQuantity(id, input.value);
            }
        });

        increaseBtn.addEventListener('click', () => {
            if (parseInt(input.value) < stock) {
                input.value++;
                debounceUpdateQuantity(id, input.value);
            }
        });

        input.addEventListener('change', () => {
            let val = parseInt(input.value);
            if (val < 1) val = 1;
            if (val > stock) val = stock;
            input.value = val;
            updateQuantity(id, val);
        });
      });

    function updateQuantity(itemId, newQuantity) {
    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update the item's price display
            const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
            const priceElement = cartItem.querySelector('.div-price p');
            priceElement.textContent = `P ${data.newTotal}`;


            const totalElementPerItem = document.querySelector('.bottom-container p:first-child');
            totalElementPerItem.textContent = `Total: P ${data.totalQuantity}`;
            // Update the total at the bottom
            const totalElement = document.querySelector('.bottom-container p:last-child');
            totalElement.textContent = `Total: P ${data.cartTotal}`;

            // Update the quantity input to match server-side value
            const quantityInput = cartItem.querySelector('.quantity');
            quantityInput.value = data.quantity;
        } else {
            console.error('Update failed:', data.message);
            alert(data.message || 'Failed to update quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the quantity. The page will refresh to show the current state.');
        location.reload(); // Fallback to page reload if JavaScript update fails
    });
}
});
     document.addEventListener("DOMContentLoaded", function () {
    const notifBtn = document.querySelector(".notificationBtn");
    const notifDropdown = document.getElementById("notificationDropdown");
    const profileBtn = document.querySelector(".profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");
    const closeNotif = document.querySelector(".closeButton");
    const wishlistButtons = document.querySelectorAll('.wishlistBtn');
    const cartButton = document.querySelectorAll('.cartBtn');
    let category = 'featured';

    document.querySelectorAll(".mainFilterButtons").forEach(button => {
    button.addEventListener("click", () => {
        // Remove 'current' from all filter buttons
        document.querySelectorAll(".mainFilterButtons").forEach(btn => {
            btn.classList.remove("current");
        });
        let url='?page=${page}';
        button.classList.add("current");

        category = button.dataset.category;
        console.log('Clicked category:', category);
        updateFilters();
    });
});
    notifBtn.addEventListener("click", function () {
      notifDropdown.style.display = notifDropdown.style.display === "none" ? "block" : "none";
      profileDropdown.style.display = "none"; 
      console.log("clicked");
    });

    profileBtn.addEventListener("click", function () {
      profileDropdown.style.display = profileDropdown.style.display === "none" ? "block" : "none";
      notifDropdown.style.display = "none"; // close notifications if open
    });
    if(closeNotif){
    closeNotif.addEventListener("click", function () {
      notifDropdown.style.display = "none";
    });
    }
    document.getElementById('logoClick').addEventListener('click', function() {
    window.location.href = "{{ route('student.dashboard') }}";
    });
    // Optional: Close dropdowns if clicked outside
    window.addEventListener("click", function (e) {
      if (!e.target.closest(".dropdown-container")) {
        notifDropdown.style.display = "none";
        profileDropdown.style.display = "none";
      }
    });

        // wishlist button
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function () {
                window.location.href = "{{ route('show.wishlist') }}";
            });
        });
         // cart button
        cartButton.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('show.cart')}}";
                
            })
        });

  });
      document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });
    const faqsBtn = document.querySelectorAll('.faqsBtn');
        faqsBtn.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('FAQs')}}";
                
            })
        });
    </script>
</body>
</html>