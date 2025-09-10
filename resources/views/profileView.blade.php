<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile view</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    @vite('resources/css/profileView.css')
    @vite('resources/js/profileView.js')
  </head>
  <body>
@auth
      @if(auth()->user()->role === 'student')
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
          <li><a href="{{ route('student.profile') }}">My Profile</a></li>
          <li><a href="{{route('account.page')}}">Settings</a></li>
          <li><a href="{{ route('logout') }}">Logout</a></li>
        </ul>
      </div>
    </div>
        </div>
      </div>

      <!-- nav bar -->
      @elseif(auth()->user()->role === 'organization')
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
                            <li><a href="{{route('account.page')}}">Accounts</a></li>
                            <li><a href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- nav bar -->
      @endif
    @endauth

    <section class="first">
      <div class="profile">
        <img src="{{asset('img/bannerProfile.png')}}" alt="" />
      </div>
      <div class="avatarAndRating">
        <img class="avatar" src="{{asset('storage/users-avatar/'. $user->avatar )}}" alt="" />
        <div class="rating">
          <img src="{{asset('imgs/ratingBlack.svg')}}" alt="" /> <label for="">{{ number_format($ratings->avg_rating ?? 0, 1) }}</label>
        </div>
      </div>
      <div class="nameAndButttons">
        <div class="name">
          <h1>{{$user->name}} {{$user->last_name}}</h1>
          <p>{{ ucfirst($user->role) }}</p>
        </div>
        @if($user->id != Auth::id())
        <div class="buttons">
          <a href="{{ url('Yonder/Chat/' . $user->id) }}">
            <button  style="background-color:blue;" type="button">Message</button>
          </a>
          <button type="button" data-open-report>Report</button>
        </div>
        @endif
      </div>
    </section>

    <section class="second">
      <h2>Seller reviews ({{ $ratings->total_reviews}})</h2>
      <div class="reviews-container">
        @forelse($reviews as $review)
        <div class="review-card">
          <div class="review-header">
            <img class="review-avatar" src="{{asset('storage/users-avatar/'. $review->avatar)}}" alt="User Avatar" />
            <div class="review-info">
              <h3>{{$review->name}} {{$review->last_name}}</h3>
              <p class="review-date">{{ $review->formatted_date }}</p>
            </div>
          </div>
          <div class="review-stars">
            @if($review->rating == 1)
              ⭐
            @elseif($review->rating == 2)
              ⭐⭐
            @elseif($review->rating == 3)
               ⭐⭐⭐
            @elseif($review->rating == 4)
              ⭐⭐⭐⭐
            @elseif($review->rating == 5)
              ⭐⭐⭐⭐⭐
            @endif
          </div>
          <p class="review-text">
            {{ $review->comment }}
          </p>
        </div>
        @empty
          <p class="NoRating">No Reviews Available!</p>
        @endforelse
        <!-- Add more review cards here -->
      </div>
    </section>

<section class="third">
  @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
  <h2>{{$user->name}} {{$user->last_name}}'s Listings</h2>

  <div class="top-bar">
    <div class="search-box">
      <input id="searchInput" type="text" placeholder="Search products..." data-user-id="{{ $user->id }}" />
    </div>

    <div class="filters">
      <select id="stockFilter">
        <option value="">Stock</option>
        <option value="available">Available & in stock</option>
        <option value="out">Out of stock</option>
      </select>

      <select id="sortFilter">
        <option value="">Sort by</option>
        <option value="asc">Price: Low to High</option>
        <option value="desc">Price: High to Low</option>
      </select>
    </div>
  </div>

  <!-- Product Grid -->
  <div class="product-grid">
    @include('partials.stalkableProfile',['products' => $products])
  </div>
</section>
          
<div id="uniqueConfirmModal" class="unique-modal-overlay" style="display:none;">
  <div class="unique-modal-content">
    <div id="uniqueModalHeader" class="unique-modal-header">
      <div class="imageWrapper" id="imageWrapper">
        <img id="uniqueModalIcon" src="{{ asset('imgModal/cancelLogo.svg') }}" alt="icon" />
      </div>
    </div>

    <h3 id="uniqueHeaderMessage">Report User</h3>
    <p id="uniqueConfirmMessage">Tell us what happened. Your report helps keep Yonder safe.</p>

    <!-- Report Form -->
    <form id="reportUserForm" method="POST" action="{{ route('user-reports.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="reported_user_id" value="{{ $user->id }}">
      <input type="hidden" name="reporter_id" value="{{ auth()->id() }}">

      <div style="text-align:left; padding: 0 20px 10px 20px;">
        <label for="reason" style="font-weight:600; font-size:14px;">Reason</label>
        <select id="reason" name="reason" required
                style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:6px;">
          <option value="" disabled selected>Select a reason</option>
          <option value="harassment">Harassment or hate</option>
          <option value="spam">Spam or scams</option>
          <option value="impersonation">Impersonation</option>
          <option value="inappropriate_content">Inappropriate content</option>
          <option value="other">Other</option>
        </select>
      </div>

      <div style="text-align:left; padding: 0 20px 10px 20px;">
        <label for="details" style="font-weight:600; font-size:14px;">Details</label>
        <textarea id="details" name="details" rows="4" required
                  placeholder="Add context, message excerpts, dates, etc."
                  style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:6px; resize:vertical;"></textarea>
      </div>

      <div style="text-align:left; padding: 0 20px 0 20px;">
        <div class="unique-form-group">
          <label class="unique-label">Attachment (optional)</label>
          <input id="evidence" name="evidence" type="file" class="unique-file-input" accept=".png,.jpg,.jpeg,.pdf">

          <label for="evidence" class="unique-file-label">Choose File</label>

          <span id="file-chosen" class="unique-file-chosen">No file chosen</span>

          <small class="unique-help">Max 5 MB. PNG, JPG, or PDF.</small>
        </div>
      </div>

      <div class="unique-modal-buttons" style="margin-top:16px;">
        <button type="button" id="uniqueConfirmNo" class="unique-modal-btn unique-modal-no">Cancel</button>
        <button type="submit" id="uniqueConfirmYes" class="unique-modal-btn unique-modal-yes">Submit Report</button>
      </div>
    </form>
  </div>
</div>
      @if (session('error'))
        <div id="errorBar" class="error-bar">
                {{session('error')}} <img src="{{ asset('imgModal/barCrossLogo.svg') }}" alt="error" class="error-icon">
            </div>
            <script>
                const errorbar = document.getElementById('errorBar');
                errorbar.classList.add('show');

                // Hide after 3 seconds
                setTimeout(() => {
                    errorbar.classList.remove("show");
                    setTimeout(() => bar.remove(), 400);
                }, 5000);
        </script>
        @elseif (session('successfull'))
        <div id="successBar" class="success-bar">
            {{session('successfull')}} <img src="{{ asset('imgModal/barCheckLogo.svg') }}" alt="success" class="success-icon">
        </div>
        <script>
            const bar = document.getElementById('successBar');
            bar.classList.add('show');

            // Hide after 3 seconds
            setTimeout(() => {
                bar.classList.remove("show");
                setTimeout(() => bar.remove(), 400);
            }, 5000);
        </script>
        @endif
    <script>
      const scroller = document.querySelector('.reviews-container');
if (scroller) {
  scroller.addEventListener('wheel', (e) => {
    if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
      scroller.scrollLeft += e.deltaY;
      e.preventDefault();
    }
  }, { passive: false });
}

document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('uniqueConfirmModal');
  const btns = document.querySelectorAll('[data-open-report]');
  const cancelBtn = document.getElementById('uniqueConfirmNo');
  const fileInput = document.getElementById('evidence');
  const fileChosen = document.getElementById('file-chosen');

  // Optional: switch header color to “warning red” for reports
  const header = document.getElementById('uniqueModalHeader');
  const headerText = document.getElementById('uniqueHeaderMessage');
  header.style.backgroundColor = '#d9534f';
  document.querySelector('.imageWrapper').style.boxShadow = '0 1px 0 rgba(217,83,79,0.6)';
  headerText.style.color = '#d9534f';

  btns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      modal.style.display = 'flex';
    });
  });

  cancelBtn.addEventListener('click', function () {
    modal.style.display = 'none';
    const fileInput = document.getElementById('evidence');
      const fileChosen = document.getElementById('file-chosen');
      if (fileInput) fileInput.value = '';
      if (fileChosen) fileChosen.textContent = 'No file chosen';

      // optional: reset the whole form
      document.getElementById('reportUserForm').reset();
  });

  // Close when clicking outside
  modal.addEventListener('click', function (e) {
    if (e.target === modal) {
      modal.style.display = 'none';

      const fileInput = document.getElementById('evidence');
      const fileChosen = document.getElementById('file-chosen');
      if (fileInput) fileInput.value = '';
      if (fileChosen) fileChosen.textContent = 'No file chosen';

      // optional: reset the whole form
      document.getElementById('reportUserForm').reset();
    }
  });


  fileInput.addEventListener('change', function(){
    fileChosen.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
  });
});
           // notifs
 document.addEventListener("DOMContentLoaded", function () {
    const notifBtn = document.querySelector(".notificationBtn");
    const notifDropdown = document.getElementById("notificationDropdown");
    const profileBtn = document.querySelector(".profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");
    const closeNotif = document.querySelector(".closeButton");
    const wishlistButtons = document.querySelectorAll('.wishlistBtn');
    const faqsBtn = document.querySelectorAll('.faqsBtn');
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
      @if(auth()->check())
          @if(auth()->user()->role === 'student')
              window.location.href = "{{ route('student.dashboard') }}";
          @elseif(auth()->user()->role === 'organization')
              window.location.href = "{{ route('organization.dashboard') }}";
          @elseif(auth()->user()->role === 'admin')
              window.location.href = "{{ route('admin.dashboard') }}";
          @endif
      @else
          window.location.href = "{{ route('landing') }}";
      @endif
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
        faqsBtn.forEach(button=>{
            button.addEventListener('click', function(){
              console.log('clicked');
                window.location.href= "{{route('FAQs')}}";
                
            })
        });
  });
    </script>
  </body>
</html>
