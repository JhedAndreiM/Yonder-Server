@extends('Front_layouts.app')

@section('title', 'Profile View')
@section('head')
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
@endsection
@section('content')
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
    </script>
@endsection