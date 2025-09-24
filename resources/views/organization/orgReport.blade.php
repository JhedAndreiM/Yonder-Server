@extends('Front_layouts.app')

@section('title', 'Inventory')
@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
  <!-- FilePond core -->
  <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
  <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

  <!-- FilePond image plugins -->
  <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
    rel="stylesheet" />
  <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
  <script
    src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
  <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
  <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>


  <!-- cropper.js :P -->
  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
  <script src="https://unpkg.com/cropperjs/dist/cropper.js"></script>
  <!-- Sortable.js for drag-and-drop ordering -->
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <!-- FONT AWESOME -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  @vite('resources/css/orgReport.css')
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
        <div class="containerRight"  id="containerRight">
  <div class="dashboard-header">
    <div>
      <h2>Dashboard</h2>
      <p>Here's your sales report</p>
    </div>
    <button class="generate-btn" id="generatePdfBtn">Generate Sales Report</button>
  </div>

  <div class="cards-row">
    <div class="report-card">
      <h4>Best Selling Product</h4>
      @if($topSellerProduct)
            @if($topSellerProduct->product_name)
                <p>{{ $topSellerProduct->product_name }}</p>
            @else
                <p>No Product Available</p>
            @endif
            @if($topSellerProduct->total_quantity)
                <span>{{ $topSellerProduct->total_quantity }} total sold</span>
            @else
                <span>No Data Available</span> 
            @endif
        @else
            <p>No Product Available</p>
            <span>No Data Available</span>
        @endif
      <button id="bestSellingViewReport" class="view-report-btn" data-modal="viewReportModal">View report →</button>
    </div>
    <div class="report-card">
      <h4>Most Wishlisted Item</h4>
      @if($mostWishlisted)
            @if($mostWishlisted->name)
                <p>{{ $mostWishlisted->name }}</p>
            @else
                <p>No Product Available</p>
            @endif
            @if($mostWishlisted->wishlist_count)
                <span>{{ $mostWishlisted->wishlist_count }} total wishlist</span>
            @else
                <span>No Data Available</span>
            @endif
        @else
            <p>No Product Available</p>
            <span>No Data Available</span>
        @endif
      <button id="wishlistViewReport"  class="view-report-btn" data-modal="viewReportModal">View report →</button>
    </div>
  </div>

  <div class="recent-sales">
    <div class="sales-header">
      <h4>Recent Sales</h4>
    </div>
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Stock</th>
            <th>Lead Time (Per Day)</th>
            <th>Safety Stock</th>
            <th>Critical Level</th>
            <th>Mode</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
    @foreach($lowStockProducts as $product)
        @php
            // prefer DB variants (product_variants)
            $hasDbVariants = $product->relationLoaded('variantsData') && $product->variantsData->count() > 0;
        @endphp

        @if ($hasDbVariants)
            {{-- Use product_variants rows --}}
            @foreach ($product->variantsData as $variant)
                @php
                    $isCritical = (int) $variant->stock <= (int) $variant->critical_level;
                @endphp
                <tr @class(['critical' => $isCritical])>
                    <td>{{ $product->name }} — {{ $variant->variant_name }}: {{ $variant->variant_option }}</td>
                    <td>{{ $variant->stock }}</td>

                    <form action="{{ route('update.stock') }}" method="POST">
                        @csrf
                        <td>
                            <input name="lead_time" type="number" min="0" value="{{ $variant->lead_time }}" class="lead-time">
                        </td>
                        <td>
                            <input name="safety_stock" type="number" min="0" value="{{ $variant->safety_stock }}" class="safety-stock">
                        </td>
                        <td>
                            <input
                                name="critical_level"
                                type="number"
                                min="0"
                                value="{{ $variant->critical_level }}"
                                class="critical-level"
                                @if($variant->critical_mode !== 'manual') disabled @endif
                            >
                        </td>
                        <td>
                            <select name="critical_mode" class="critical-mode" onchange="toggleCriticalInput(this)">
                                <option value="automatic" @selected($variant->critical_mode === 'automatic')>Automatic</option>
                                <option value="manual" @selected($variant->critical_mode === 'manual')>Manual</option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                            <button type="submit">Save</button>
                        </td>
                    </form>
                </tr>
            @endforeach

        @else
            {{-- Fallback: product without DB variants --}}
            @php
                $isCritical = (int) $product->stock <= (int) $product->critical_level;
            @endphp
            <tr @class(['critical' => $isCritical])>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>

                <form action="{{ route('update.stock') }}" method="POST">
                    @csrf
                    <td>
                        <input name="lead_time" type="number" min="0" value="{{ $product->lead_time }}" class="lead-time">
                    </td>
                    <td>
                        <input name="safety_stock" type="number" min="0" value="{{ $product->safety_stock }}" class="safety-stock">
                    </td>
                    <td>
                        <input
                            name="critical_level"
                            type="number"
                            min="0"
                            value="{{ $product->critical_level }}"
                            class="critical-level"
                            @if($product->critical_mode !== 'manual') disabled @endif
                        >
                    </td>
                    <td>
                        <select name="critical_mode" class="critical-mode" onchange="toggleCriticalInput(this)">
                            <option value="automatic" @selected($product->critical_mode === 'automatic')>Automatic</option>
                            <option value="manual" @selected($product->critical_mode === 'manual')>Manual</option>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <button type="submit">Save</button>
                    </td>
                </form>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
  </div>
</div>

<!-- View Report Modal for wishlist -->
<div class="modal" id="viewReportModalWishlist">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3 class="modal-title">Wishlist Report</h3>

    @if(!empty($mostWishlisted) && $mostWishlisted->name)
      <p class="highlight">
        Most Wishlisted: <strong>{{ $mostWishlisted->name }}</strong> 
        ({{ $mostWishlisted->wishlist_count }} wishlists)
      </p>

      <div class="wishlist-table-container">
        <table class="wishlist-table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Wishlist Count</th>
            </tr>
          </thead>
          <tbody>
            @foreach($wishlistCounts as $product)
              <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->wishlist_count }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="no-data">No wishlist data found.</p>
    @endif
  </div>
</div>

<!-- View Report Modal for best seller -->
<div class="modal" id="viewReportModalBestSeller">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3 class="modal-title">Best Selling</h3>

    @if(!empty($topSellerProduct) && $topSellerProduct->product_name)
      <p class="highlight">
        Most Sold: <strong>{{ $topSellerProduct->product_name }}</strong> 
        ({{ $topSellerProduct->total_quantity }} sold)
      </p>

      <div class="wishlist-table-container">
        <table class="wishlist-table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Total Sold</th>
            </tr>
          </thead>
          <tbody>
            @foreach($salesData as $product)
              <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->total_quantity }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="no-data">No Sales data found.</p>
    @endif
  </div>
</div>

<!-- View Report Modal for stock -->
<div class="modal" id="viewReportModalStock">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3 class="modal-title">Stock Quantity</h3>

    @if($lowStockProducts->isNotEmpty())
      <p class="highlight">
        Lowest Stock: <strong>{{ $lowStockFirst->name }}</strong> 
        ({{ $lowStockFirst->stock }} sold)
      </p>

      <div class="wishlist-table-container">
        <table class="wishlist-table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Total Sold</th>
            </tr>
          </thead>
          <tbody>
            @foreach($lowStockProducts as $product)
              <tr>
                <td>{{ $product->name  }}</td>
                <td>{{ $product->stock }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="no-data">No Product data found.</p>
    @endif
  </div>
</div>

<!-- Generate PDF Modal -->
<div class="modal" id="generatePdfModal" >
  <div class="modal-content">
    <span class="close">&times;</span>
    <form id="pdfForm" action="{{ route('generate.pdf') }}" method="POST" target="_blank">
      @csrf
      <h3>Generate PDF</h3>
      
      <label for="fromDate">From:</label>
      <input type="date" id="fromDate" name="fromDate" required max="">
      
      <label for="toDate">To:</label>
      <input type="date" id="toDate" name="toDate" required max="">
      
      <div class="buttonSubmit">
        <button type="submit" class="generate-btn">Generate</button>
      </div>
    </form>
  </div>
</div>

      </div>
    </div>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('containerRight');

    // Restore scroll position if exists
    const savedScroll = localStorage.getItem('containerRightScroll');
    if (savedScroll) {
        container.scrollTop = parseInt(savedScroll, 10);
    }

    // Save scroll position before submitting any form inside containerRight
    container.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            localStorage.setItem('containerRightScroll', container.scrollTop);
        });
    });
     console.log('function toggleCriticalInput(select)');
    container.querySelectorAll('.critical-mode').forEach(select => {
        select.addEventListener('change', function() {
            const row = select.closest('tr');
            const input = row.querySelector('.critical-level');
            input.disabled = (select.value !== 'manual');
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
  // Handle View modals
  document.getElementById("wishlistViewReport").addEventListener("click", function () {
    document.getElementById("viewReportModalWishlist").style.display = "flex";
  });
  document.getElementById("bestSellingViewReport").addEventListener("click", function () {
    document.getElementById("viewReportModalBestSeller").style.display = "flex";
  });



  // Handle Generate PDF button
  document.getElementById("generatePdfBtn").addEventListener("click", function () {
    document.getElementById("generatePdfModal").style.display = "flex";
  });

  // Close modals
  document.querySelectorAll(".modal .close").forEach(closeBtn => {
    closeBtn.addEventListener("click", function () {
      this.closest(".modal").style.display = "none";
    });
  });

  // Close on outside click
  document.querySelectorAll(".modal").forEach(modal => {
    modal.addEventListener("click", function (e) {
      if (e.target === modal) modal.style.display = "none";
    });
  });

    const fromDate = document.getElementById('fromDate');
    const toDate = document.getElementById('toDate');
    const form = document.getElementById('pdfForm');

    const today = new Date().toISOString().split('T')[0];
    fromDate.max = today;
    toDate.max = today;

    fromDate.addEventListener('change', () => {
      if(fromDate.value) {
        toDate.min = fromDate.value;
        if(toDate.value && toDate.value < fromDate.value) {
          toDate.value = '';
        }
      } else {
        toDate.min = '';
      }
    });
    toDate.addEventListener('change', () => {
      if(toDate.value) {
        fromDate.max = toDate.value < today ? toDate.value : today;
        if(fromDate.value && fromDate.value > toDate.value) {
          fromDate.value = '';
        }
      } else {
        fromDate.max = today;
      }
    });

});
</script>
@endsection