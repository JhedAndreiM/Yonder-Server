@if ($products->isEmpty())
    <div class="no-items-wrapper">
        <p>No items found</p>
    </div>
@else
    @foreach ($products as $items)
    @php
        $images = \Illuminate\Support\Facades\DB::table('product_images')
            ->where('product_id', $items->product_id)
            ->get();
        $firstImage = count($images) > 0 ? $images[0]->image_path : 'default.png';
        
    @endphp
          <div class="card">
            <img src="{{ asset('images/'.$firstImage) }}" alt="Product" class="cardImg" />
            <div class="info">
              <div class="price">
                <p>P {{$items->price}}</p>
              </div>
              <p class="productDesc">
                {{$items->name}}
              </p>
            @if($items->approved === 'not')
                <div class="overlay">
                    <span>Listing Under Review</span>
                </div>
            @endif
              <div class="editButtons">
                <div class="editButtons-edit">
                  <img src="{{asset('img/blueEditLogo.png')}}" alt="Star" />
                  <a href="{{ route('products.edit', $items->product_id) }}" class="btn btn-sm btn-primary">Edit</a>
                </div>
                <div class="editButtons-delete">
                  <img src="{{asset('img/redRemoveLogo.png')}}" alt="Star" />
                  <p class="ratingScore">Delete</p>
                </div>
              </div>
            </div>
          </div>
    @endforeach
@endif