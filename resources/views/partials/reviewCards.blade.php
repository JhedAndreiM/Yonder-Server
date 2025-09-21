@foreach($reviews as $review)
<div class="review-card" id="review-card">
  <img src="{{ asset($review->first_image ? 'images/' . $review->first_image : 'img/default-product.png') }}" 
     alt="Product image" 
     class="review-img" />
  
  <div class="review-content">
    <div class="review-header">
      <div class="reviewer-info">
        <img src="{{asset('storage/users-avatar/'. $review->avatar)}}" alt="Profile picture" class="reviewer-pic" />
        <div class="reviewer-details">
          <span class="reviewer-name">{{$review->name}} {{$review->last_name}}</span>
          <span class="review-date">{{ $review->formatted_date }}</span>
        </div>
      </div>
      <span class="stars">
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
      </span>
    </div>
    
    <p class="review-text">
      {{ $review->comment }}
    </p>
    
    <button class="see-more" style="display:none;">See more</button>
  </div>
</div>
@endforeach