@extends('Front_layouts.app')

@section('title', 'My Listings')
@section('head')

@endsection
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
  rel="stylesheet"
  />
@vite('resources/css/myListings.css')
@section('content')
    <div class="floating">
      <a class="listing_link"href="{{ route('create.listing') }}"><img src="{{ asset('img/add(2).svg') }}" alt="" /></a>
    </div>
<div class="mainContainer">
      <div class="left">
        <div class="profile">
          <div class="top">
            <div class="name profileStalkClick">
              <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
              <h3>{{Auth::user()->name .' ' . Auth::user()->last_name}}</h3>
            </div>
            <div class="ratings">
              <img class="ratingLogo" src="{{ asset('img/rating.svg') }}" alt="" />
              <p class="rating">{{ number_format($sellerRating->avg_rating, 1) }}</p>
            </div>
          </div>
          <div class="myListings">
            <img class="current" src="{{asset('img/MyListings.svg')}}" alt="" />
            <h2 class="current">My Listings</h2>
          </div>
          <div class="mySales">
            <img class="notCurrent" src="{{asset('img/money-icon.svg')}}" alt="" />
            <h2 class="notCurrent">Product Orders</h2>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="nav">
          <h1>My Listing(s):</h1>
        </div>
        <div class="items">
          @include('partials.myListing',['products' => $products])
        </div>
      </div>
    </div>

    <!-- Notification Bars -->
    <div id="successBar" class="success-bar" style="display: none;">
        <span id="successMessage">Item deleted successfully!</span>
        <img src="{{ asset('imgModal/barCheckLogo.svg') }}" alt="success" class="success-icon">
    </div>
    <div id="errorBar" class="error-bar" style="display: none;">
        <span id="errorMessage">Failed to delete item. Please try again.</span>
        <img src="{{ asset('imgModal/barCrossLogo.svg') }}" alt="error" class="error-icon">
    </div>

        <!-- Unique modal container -->
        <div id="uniqueConfirmModal" class="unique-modal-overlay" style="display:none;">
  <div class="unique-modal-content">
    <div id="uniqueModalHeader" class="unique-modal-header">
        <div class="imageWrapper" id="imageWrapper">
            <img id="uniqueModalIcon" src="{{ asset('imgModal/cancelLogo.svg')}}" alt="icon" />
        </div>
    </div>
    <h3 id="uniqueHeaderMessage">Remove Item?</h3>
    <p id="uniqueConfirmMessage">Are you sure you want to remove this item?</p>
    <div class="unique-modal-buttons">
      <button id="uniqueConfirmNo" class="unique-modal-btn unique-modal-no">Cancel</button>
      <button id="uniqueConfirmYes" class="unique-modal-btn unique-modal-yes">Remove</button>
    </div>

  </div>
</div>
    <script>
        
        // for links
        const myPurchases = document.querySelectorAll('.myPurchases');
        myPurchases.forEach(button =>{
          button.addEventListener('click', function() {
            console.log('clicked');
            window.location.href = "{{ route('student.profile') }}";
          });
        });
        const myVouchers = document.querySelectorAll('.myVouchers');
        myVouchers.forEach(button =>{
            button.addEventListener('click', function() {
              console.log('clicked');
                window.location.href = "{{ route('show.vouchers') }}";
            });
        });    
        const mySales = document.querySelectorAll('.mySales');
        mySales.forEach(button =>{
        button.addEventListener('click', function() {
          console.log('clicked');
            window.location.href = "{{ route('student.sales') }}";
          });
        }); 
            const profileStalkClick = document.querySelectorAll('.profileStalkClick');
    profileStalkClick.forEach(button =>{
        button.addEventListener('click', function() {
            window.location.href = "{{ route('stalk.profile', Auth::id()) }}";
        });
    }); 
      document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });

    // Notification bar functions
    function showSuccessBar(message = 'Item deleted successfully!') {
        const successBar = document.getElementById('successBar');
        const successMessage = document.getElementById('successMessage');
        
        successMessage.textContent = message;
        successBar.style.display = 'flex';
        
        // Add show class after a brief delay for animation
        setTimeout(() => {
            successBar.classList.add('show');
        }, 10);
        
        // Hide after 5 seconds
        setTimeout(() => {
            successBar.classList.remove('show');
            setTimeout(() => {
                successBar.style.display = 'none';
            }, 300);
        }, 5000);
    }

    function showErrorBar(message = 'Failed to delete item. Please try again.') {
        const errorBar = document.getElementById('errorBar');
        const errorMessage = document.getElementById('errorMessage');
        
        errorMessage.textContent = message;
        errorBar.style.display = 'flex';
        
        // Add show class after a brief delay for animation
        setTimeout(() => {
            errorBar.classList.add('show');
        }, 10);
        
        // Hide after 5 seconds
        setTimeout(() => {
            errorBar.classList.remove('show');
            setTimeout(() => {
                errorBar.style.display = 'none';
            }, 300);
        }, 5000);
    }

    // Delete functionality with modal confirmation
    let productToDelete = null;

    // Show modal when delete button is clicked
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            productToDelete = {
                id: this.dataset.productId,
                name: this.dataset.productName,
                element: this.closest('.card')
            };
            
            // Update modal content
            document.getElementById('uniqueConfirmMessage').textContent = 
                `Are you sure you want to remove "${productToDelete.name}"?`;
            
            // Show modal
            document.getElementById('uniqueConfirmModal').style.display = 'flex';
        });
    });

    // Handle modal "No" button
    document.getElementById('uniqueConfirmNo').addEventListener('click', function() {
        document.getElementById('uniqueConfirmModal').style.display = 'none';
        productToDelete = null;
    });

    // Handle modal "Yes" button - perform delete
    document.getElementById('uniqueConfirmYes').addEventListener('click', async function() {
        if (!productToDelete) return;

        try {
            const response = await fetch('{{ route("delete.listing") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productToDelete.id
                })
            });

            if (response.ok) {
                // Remove the product card from the DOM
                productToDelete.element.remove();
                
                // Hide modal
                document.getElementById('uniqueConfirmModal').style.display = 'none';
                
                // Show success notification
                showSuccessBar(`"${productToDelete.name}" has been deleted successfully!`);
                
                // Check if no items left
                const remainingItems = document.querySelectorAll('.card');
                if (remainingItems.length === 0) {
                    document.querySelector('.items').innerHTML = 
                        '<div class="no-items-wrapper"><p>No items found</p></div>';
                }
                
                productToDelete = null;
            } else {
                const data = await response.json();
                // Hide modal first
                document.getElementById('uniqueConfirmModal').style.display = 'none';
                // Show error notification
                showErrorBar(data.message || 'Failed to delete product.');
                productToDelete = null;
            }
        } catch (error) {
            console.error('Error:', error);
            // Hide modal first
            document.getElementById('uniqueConfirmModal').style.display = 'none';
            // Show error notification
            showErrorBar('Something went wrong while deleting the product.');
            productToDelete = null;
        }
    });

    // Close modal when clicking outside of it
    document.getElementById('uniqueConfirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
            productToDelete = null;
        }
    });
    </script>
@endsection
