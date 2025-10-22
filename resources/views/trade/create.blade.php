@extends('Front_layouts.app')

@section('title', isset($originalOffer) ? 'Counter Trade Offer' : 'Create Trade Offer')
@section('head')
  <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
  @vite('resources/css/tradeOffer.css')
  @vite('resources/js/tradeOffer.js')
@endsection

@section('content')
<div class="trade-container">
  <!-- Header -->
  <div class="trade-header">
    <div class="trade-header-content">
      <h1>{{ isset($originalOffer) ? 'Counter Trade Offer' : 'Create Trade Offer' }}</h1>
      <p class="trade-subtitle">Trading with <strong>{{ $recipient->name }} {{ $recipient->last_name }}</strong></p>
      @if(isset($originalOffer))
        <p class="counter-notice" style="color: #7B1E1E; font-size: 14px; margin-top: 8px;">
          💡 You're creating a counter offer. The original offer will be marked as countered.
        </p>
      @endif
    </div>
    <a href="{{ url()->previous() }}" class="btn-back">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      Back
    </a>
  </div>

  <!-- Main Trade Interface -->
  <div class="trade-content">
    <!-- Left Panel - Your Items -->
    <div class="trade-panel">
      <div class="panel-header">
        <div class="panel-title">
          <img src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="Your Avatar" class="panel-avatar">
          <div>
            <h2>Your Items</h2>
            <p class="panel-subtitle">Select items to offer</p>
          </div>
        </div>
        <div class="search-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" id="searchMyItems" placeholder="Search your items..." class="search-input">
        </div>
      </div>

      <!-- Selected Items Display -->
      <div class="selected-items-area" id="mySelectedArea">
        <div class="empty-state">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
          <p>Click items below to add to your offer</p>
        </div>
      </div>

      <!-- Available Items Grid -->
      <div class="items-grid" id="myItemsGrid">
        @forelse($myItems as $item)
          @php
            $itemVariants = $item->variants;
            $hasVariants = !empty($itemVariants) && isset($itemVariants['options']) && count($itemVariants['options']) > 0;
            // Convert string stocks to integers before summing
            $totalStock = $hasVariants ? array_sum(array_map('intval', $itemVariants['optionStocks'])) : $item->stock;
          @endphp
          <div class="item-card" 
               data-item-id="{{ $item->product_id }}"
               data-item-name="{{ $item->name }}"
               data-item-condition="{{ $item->product_condition }}"
               data-item-image="{{ $item->images->first() ? asset('images/' . $item->images->first()->image_path) : asset('img/placeholder.png') }}"
               data-item-price="{{ $item->price }}"
               data-item-stock="{{ $totalStock }}"
               data-has-variants="{{ $hasVariants ? 'true' : 'false' }}"
               data-variants='@json($hasVariants ? $itemVariants : null)'>
            <div class="item-image">
              <img src="{{ $item->images->first() ? asset('images/' . $item->images->first()->image_path) : asset('img/placeholder.png') }}" alt="{{ $item->name }}">
              <div class="item-overlay">
                <button class="btn-select">Select</button>
              </div>
            </div>
            <div class="item-info">
              <h3 class="item-name">{{ \Illuminate\Support\Str::limit($item->name, 30) }}</h3>
              <div class="item-meta">
                <span class="item-price">₱{{ number_format($item->price, 2) }}</span>
                <span class="item-stock">Stock: {{ $totalStock }}</span>
              </div>
              <span class="item-condition {{ strtolower($item->product_condition) }}">{{ ucfirst($item->product_condition) }}</span>
            </div>
          </div>
        @empty
          <div class="no-items">
            <p>You don't have any items to trade</p>
          </div>
        @endforelse
      </div>
    </div>

    <!-- Center Divider -->
    <div class="trade-divider">
      <div class="divider-line"></div>
    </div>

    <!-- Right Panel - Their Items -->
    <div class="trade-panel">
      <div class="panel-header">
        <div class="panel-title">
          <img src="{{ asset('storage/users-avatar/' . $recipient->avatar) }}" alt="{{ $recipient->name }}'s Avatar" class="panel-avatar">
          <div>
            <h2>{{ $recipient->name }}'s Items</h2>
            <p class="panel-subtitle">Select items to request</p>
          </div>
        </div>
        <div class="search-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" id="searchTheirItems" placeholder="Search their items..." class="search-input">
        </div>
      </div>

      <!-- Selected Items Display -->
      <div class="selected-items-area" id="theirSelectedArea">
        @if($targetProduct && !$targetProduct->variants)
          <!-- Target Product - Pre-selected (only if no variants) -->
          <div class="selected-item target-product" data-item-id="{{ $targetProduct->product_id }}" data-original-id="{{ $targetProduct->product_id }}">
            <div class="target-badge">Target Item</div>
            <img src="{{ $targetProduct->images->first() ? asset('images/' . $targetProduct->images->first()->image_path) : asset('img/placeholder.png') }}" alt="{{ $targetProduct->name }}" class="selected-item-image">
            <div class="selected-item-details">
              <div class="selected-item-name">{{ \Illuminate\Support\Str::limit($targetProduct->name, 20) }}</div>
              <div class="selected-item-qty">Qty: 1</div>
            </div>
            <button class="btn-remove" data-item-id="{{ $targetProduct->product_id }}" data-side="their">&times;</button>
          </div>
        @else
          <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="12" y1="8" x2="12" y2="16"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            <p>Click items below to add to your request</p>
          </div>
        @endif
      </div>

      <!-- Available Items Grid -->
      <div class="items-grid" id="theirItemsGrid">
        @if($targetProduct)
          <!-- Show target product first (but in a special way) -->
          @php
            $targetVariants = $targetProduct->variants;
            $targetHasVariants = !empty($targetVariants) && isset($targetVariants['options']) && count($targetVariants['options']) > 0;
            // Convert string stocks to integers before summing
            $targetTotalStock = $targetHasVariants ? array_sum(array_map('intval', $targetVariants['optionStocks'])) : $targetProduct->stock;
          @endphp
          <div class="item-card target-product-card {{ !$targetHasVariants ? 'selected' : '' }}" 
               data-item-id="{{ $targetProduct->product_id }}"
               data-item-name="{{ $targetProduct->name }}"
               data-item-condition="{{ $targetProduct->product_condition }}"
               data-item-image="{{ $targetProduct->images->first() ? asset('images/' . $targetProduct->images->first()->image_path) : asset('img/placeholder.png') }}"
               data-item-price="{{ $targetProduct->price }}"
               data-item-stock="{{ $targetTotalStock }}"
               data-has-variants="{{ $targetHasVariants ? 'true' : 'false' }}"
               data-variants='@json($targetHasVariants ? $targetVariants : null)'>
            <div class="target-flag">You Want This</div>
            <div class="item-image">
              <img src="{{ $targetProduct->images->first() ? asset('images/' . $targetProduct->images->first()->image_path) : asset('img/placeholder.png') }}" alt="{{ $targetProduct->name }}">
              <div class="item-overlay">
                <button class="btn-select">{{ !$targetHasVariants ? 'Selected' : 'Select' }}</button>
              </div>
            </div>
            <div class="item-info">
              <h3 class="item-name">{{ \Illuminate\Support\Str::limit($targetProduct->name, 30) }}</h3>
              <div class="item-meta">
                <span class="item-price">₱{{ number_format($targetProduct->price, 2) }}</span>
                <span class="item-stock">Stock: {{ $targetTotalStock }}</span>
              </div>
              <span class="item-condition {{ strtolower($targetProduct->product_condition) }}">{{ ucfirst($targetProduct->product_condition) }}</span>
            </div>
          </div>
        @endif
        
        @forelse($recipientItems as $item)
          @php
            $itemVariants = $item->variants;
            $hasVariants = !empty($itemVariants) && isset($itemVariants['options']) && count($itemVariants['options']) > 0;
            // Convert string stocks to integers before summing
            $totalStock = $hasVariants ? array_sum(array_map('intval', $itemVariants['optionStocks'])) : $item->stock;
          @endphp
          <div class="item-card" 
               data-item-id="{{ $item->product_id }}"
               data-item-name="{{ $item->name }}"
               data-item-condition="{{ $item->product_condition }}"
               data-item-image="{{ $item->images->first() ? asset('images/' . $item->images->first()->image_path) : asset('img/placeholder.png') }}"
               data-item-price="{{ $item->price }}"
               data-item-stock="{{ $totalStock }}"
               data-has-variants="{{ $hasVariants ? 'true' : 'false' }}"
               data-variants='@json($hasVariants ? $itemVariants : null)'>
            <div class="item-image">
              <img src="{{ $item->images->first() ? asset('images/' . $item->images->first()->image_path) : asset('img/placeholder.png') }}" alt="{{ $item->name }}">
              <div class="item-overlay">
                <button class="btn-select">Select</button>
              </div>
            </div>
            <div class="item-info">
              <h3 class="item-name">{{ \Illuminate\Support\Str::limit($item->name, 30) }}</h3>
              <div class="item-meta">
                <span class="item-price">₱{{ number_format($item->price, 2) }}</span>
                <span class="item-stock">Stock: {{ $totalStock }}</span>
              </div>
              <span class="item-condition {{ strtolower($item->product_condition) }}">{{ ucfirst($item->product_condition) }}</span>
            </div>
          </div>
        @empty
          @if(!$targetProduct)
            <div class="no-items">
              <p>This user has no items available for trade</p>
            </div>
          @endif
        @endforelse
      </div>
    </div>
  </div>

  <!-- Trade Summary Footer -->
  <div class="trade-footer">
    <div class="trade-summary">
      <div class="summary-section">
        <span class="summary-label">Items You're Offering:</span>
        <span class="summary-count" id="myItemCount">0</span>
      </div>
      <div class="summary-section">
        <span class="summary-label">Total Value:</span>
        <span class="summary-price" id="myTotalPrice">₱0.00</span>
      </div>
      <div class="summary-divider"></div>
      <div class="summary-section">
        <span class="summary-label">Items You're Requesting:</span>
        <span class="summary-count" id="theirItemCount">0</span>
      </div>
      <div class="summary-section">
        <span class="summary-label">Total Value:</span>
        <span class="summary-price" id="theirTotalPrice">₱0.00</span>
      </div>
    </div>
    <div class="trade-actions">
      <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
      <button type="button" class="btn-send-offer" id="btnSendOffer" disabled>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="22" y1="2" x2="11" y2="13"></line>
          <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
        </svg>
        Send Trade Offer
      </button>
    </div>
  </div>
</div>

<!-- Item Detail Modal (Optional for future) -->
<div class="item-modal" id="itemModal" style="display: none;">
  <div class="modal-backdrop"></div>
  <div class="modal-content">
    <button class="modal-close" id="closeModal">&times;</button>
    <div class="modal-body">
      <img id="modalItemImage" src="" alt="" class="modal-item-image">
      <div class="modal-item-details">
        <h2 id="modalItemName"></h2>
        <span id="modalItemCondition" class="item-condition"></span>
        <p id="modalItemDescription"></p>
      </div>
    </div>
  </div>
</div>

<!-- Item Selection Modal (Variant & Quantity) -->
<div class="item-selection-modal" id="itemSelectionModal" style="display: none;">
  <div class="modal-backdrop" onclick="document.getElementById('itemSelectionModal').style.display='none'"></div>
  <div class="selection-modal-content">
    <div class="selection-modal-header">
      <h2 id="modalItemTitle">Select Item Details</h2>
      <button class="modal-close" onclick="document.getElementById('itemSelectionModal').style.display='none'">&times;</button>
    </div>
    <div class="selection-modal-body">
      <div class="modal-item-preview">
        <img id="modalItemImageDisplay" src="" alt="" class="preview-image">
        <div class="preview-info">
          <p class="preview-price" id="modalItemPrice">₱0.00</p>
        </div>
      </div>
      
      <!-- Variant Selection -->
      <div class="modal-field" id="modalVariantSection" style="display: none;">
        <label for="modalVariantSelect">Select Variant:</label>
        <select id="modalVariantSelect" class="modal-select">
          <!-- Options populated by JS -->
        </select>
      </div>
      
      <!-- Quantity Selection -->
      <div class="modal-field">
        <label for="modalQuantityInput">Quantity: <span class="stock-info">(Max: <span id="modalMaxStock">0</span>)</span></label>
        <div class="quantity-controls">
          <button type="button" class="qty-btn">-</button>
          <input type="number" id="modalQuantityInput" class="modal-quantity-input" value="1" min="1" max="1">
          <button type="button" class="qty-btn">+</button>
        </div>
        <div id="quantityWarning" class="quantity-warning" style="display: none;"></div>
      </div>
      
      <div class="selection-modal-footer">
        <button type="button" class="btn-modal-cancel" onclick="document.getElementById('itemSelectionModal').style.display='none'">Cancel</button>
        <button type="button" class="btn-modal-confirm" id="modalConfirmSelection">Add to Trade</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Pass data to JavaScript
  window.tradeData = {
    recipientId: {{ $recipient->id }},
    recipientName: "{{ $recipient->name }} {{ $recipient->last_name }}",
    csrfToken: "{{ csrf_token() }}",
    @if(isset($originalOffer))
    isCounterOffer: true,
    originalOfferId: {{ $originalOffer->id }},
    // Pre-populate with original offer items
    preSelectedMyItems: [
      @foreach($originalOffer->recipientItems as $item)
      {
        id: {{ $item->product_id }},
        name: @json($item->product->name),
        quantity: {{ $item->quantity }},
        price: {{ $item->price_at_time }},
        image: @json($item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('img/placeholder.png')),
        condition: @json($item->product->product_condition),
        variantIndex: {{ $item->variant_index ?? 'null' }},
        variantName: @json($item->variant_name),
        hasVariants: {{ $item->product->variants ? 'true' : 'false' }},
        variants: @json($item->product->variants),
        stock: {{ $item->product->stock }}
      }{{ $loop->last ? '' : ',' }}
      @endforeach
    ],
    preSelectedTheirItems: [
      @foreach($originalOffer->senderItems as $item)
      {
        id: {{ $item->product_id }},
        name: @json($item->product->name),
        quantity: {{ $item->quantity }},
        price: {{ $item->price_at_time }},
        image: @json($item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('img/placeholder.png')),
        condition: @json($item->product->product_condition),
        variantIndex: {{ $item->variant_index ?? 'null' }},
        variantName: @json($item->variant_name),
        hasVariants: {{ $item->product->variants ? 'true' : 'false' }},
        variants: @json($item->product->variants),
        stock: {{ $item->product->stock }}
      }{{ $loop->last ? '' : ',' }}
      @endforeach
    ],
    @else
    isCounterOffer: false,
    originalOfferId: null,
    preSelectedMyItems: [],
    preSelectedTheirItems: [],
    @endif
    @if($targetProduct ?? false)
    targetProduct: {
      id: {{ $targetProduct->product_id }},
      name: "{{ $targetProduct->name }}",
      price: {{ $targetProduct->price }},
      image: "{{ $targetProduct->images->first() ? asset('images/' . $targetProduct->images->first()->image_path) : asset('img/placeholder.png') }}",
      condition: "{{ $targetProduct->product_condition }}",
      hasVariants: {{ $targetProduct->variants ? 'true' : 'false' }},
      variants: @json($targetProduct->variants),
      stock: {{ $targetProduct->stock }}
    }
    @else
    targetProduct: null
    @endif
  };
</script>
@endsection
