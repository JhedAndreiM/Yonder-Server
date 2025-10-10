@extends('Front_layouts.app')

@section('title', 'Inventory')
@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
  
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

  <!-- Filter Section -->
  <div class="filter-section">
    <div class="filter-controls">
      <div class="filter-group">
        <label for="yearFilter">Year:</label>
        <select id="yearFilter" class="filter-select">
          @php
            $currentYear = date('Y');
            $startYear = 2025;
          @endphp
          @for($year = $currentYear; $year >= $startYear; $year--)
            <option value="{{ $year }}" @if($year == $currentYear) selected @endif>{{ $year }}</option>
          @endfor
        </select>
      </div>
      <div class="filter-group">
        <label for="monthFilter">Month:</label>
        <select id="monthFilter" class="filter-select">
          <option value="">All Months</option>
          <option value="01">January</option>
          <option value="02">February</option>
          <option value="03">March</option>
          <option value="04">April</option>
          <option value="05">May</option>
          <option value="06">June</option>
          <option value="07">July</option>
          <option value="08">August</option>
          <option value="09">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
      </div>
      <button id="applyFilters" class="filter-btn">Apply Filters</button>
      <button id="resetFilters" class="filter-btn reset">Reset</button>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="charts-section">
    <div class="chart-container">
      <div class="chart-header">
        <h3>Product Sales Performance</h3>
        <div class="chart-controls">
          <button class="chart-toggle active" data-chart="sales">Items Sold</button>
          <button class="chart-toggle" data-chart="wishlist">Wishlist</button>
          <button class="chart-toggle" data-chart="stock">Stock Levels</button>
        </div>
      </div>
      <div class="chart-wrapper">
        <canvas id="salesChart" class="chart-canvas"></canvas>
        <canvas id="wishlistChart" class="chart-canvas" style="display: none;"></canvas>
        <canvas id="stockChart" class="chart-canvas" style="display: none;"></canvas>
      </div>
    </div>
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
      <h4>Product Stocks</h4>
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
<div class="modal" id="generatePdfModal">
  <div class="modal-content pdf-modal">
    <div class="modal-header">
      <div class="modal-icon">
        <i class="fa-solid fa-file-pdf"></i>
      </div>
      <div class="modal-title-section">
        <h3>Generate Sales Report</h3>
        <p>Select date range to generate your comprehensive sales report</p>
      </div>
      <span class="close">&times;</span>
    </div>
    
    <form id="pdfForm" action="{{ route('generate.pdf') }}" method="GET" target="_blank">
      @csrf
      <div class="form-content">
        <div class="input-group" style="margin-bottom: 1rem;">
          <label for="reportType" style="font-weight: 600; margin-bottom: 0.25rem; display: block;">Report to Generate</label>
          <select id="reportType" name="reportType" required style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc;">
            <option value="all" selected>All Sections</option>
            <option value="pben">PBEN Section Only</option>
            <option value="student_org">Student Org Section Only</option>
          </select>
        </div>
        <div class="date-inputs">
          <div class="input-group">
            <label for="fromDate">
              <i class="fa-solid fa-calendar-days"></i>
              From Date
            </label>
            <input type="date" id="fromDate" name="fromDate" required min="2025-01-01" max="" placeholder="Select start date">
          </div>
          
          <div class="input-group">
            <label for="toDate">
              <i class="fa-solid fa-calendar-days"></i>
              To Date
            </label>
            <input type="date" id="toDate" name="toDate" required min="2025-01-01" max="" placeholder="Select end date">
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="document.getElementById('generatePdfModal').style.display='none'">
          <i class="fa-solid fa-times"></i>
          Cancel
        </button>
        <button type="submit" class="btn-generate">
          <i class="fa-solid fa-download"></i>
          Generate Report
        </button>
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
    const minDate = '2025-01-01';
    
    fromDate.max = today;
    toDate.max = today;
    fromDate.min = minDate;
    toDate.min = minDate;

    fromDate.addEventListener('change', () => {
      if(fromDate.value) {
        toDate.min = fromDate.value;
        if(toDate.value && toDate.value < fromDate.value) {
          toDate.value = '';
        }
      } else {
        toDate.min = minDate;
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

// Chart and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Chart instances
    let salesChart = null;
    let wishlistChart = null;
    let stockChart = null;
    
    // Sample data - replace with actual data from your controller
    const salesData = {
        labels: @json($salesData->pluck('product_name')->take(10)),
        datasets: [{
            label: 'Items Sold',
            data: @json($salesData->pluck('total_quantity')->take(10)),
            backgroundColor: 'rgba(163, 0, 0, 0.8)',
            borderColor: '#a30000',
            borderWidth: 1
        }]
    };

    const wishlistData = {
        labels: @json($wishlistCounts->pluck('name')->take(10)),
        datasets: [{
            label: 'Wishlist Count',
            data: @json($wishlistCounts->pluck('wishlist_count')->take(10)),
            backgroundColor: [
                '#a30000',
                '#dc3545',
                '#fd7e14',
                '#ffc107',
                '#28a745',
                '#20c997',
                '#17a2b8',
                '#6f42c1',
                '#e83e8c',
                '#6c757d'
            ],
            borderWidth: 0
        }]
    };

    const stockData = {
        labels: @json($lowStockProducts->pluck('name')->take(10)),
        datasets: [{
            label: 'Stock Level',
            data: @json($lowStockProducts->pluck('stock')->take(10)),
            backgroundColor: 'rgba(163, 0, 0, 0.8)',
            borderColor: '#a30000',
            borderWidth: 1
        }]
    };

    // Chart configuration
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                enabled: true,
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            }
        }
    };

    // Initialize charts
    function initCharts() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: salesData,
            options: {
                ...chartOptions,
                indexAxis: 'y', // This makes it horizontal
                plugins: {
                    ...chartOptions.plugins,
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // Wishlist Chart
        const wishlistCtx = document.getElementById('wishlistChart').getContext('2d');
        wishlistChart = new Chart(wishlistCtx, {
            type: 'doughnut',
            data: wishlistData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });

        // Stock Chart
        const stockCtx = document.getElementById('stockChart').getContext('2d');
        stockChart = new Chart(stockCtx, {
            type: 'bar',
            data: stockData,
            options: chartOptions
        });
    }

    // Chart toggle functionality
    document.querySelectorAll('.chart-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const chartType = this.dataset.chart;
            
            // Update active button
            document.querySelectorAll('.chart-toggle').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Hide all charts
            document.querySelectorAll('.chart-canvas').forEach(canvas => {
                canvas.style.display = 'none';
            });
            
            // Show selected chart
            document.getElementById(chartType + 'Chart').style.display = 'block';
        });
    });

    // Filter functionality
    document.getElementById('applyFilters').addEventListener('click', function() {
        const year = document.getElementById('yearFilter').value;
        const month = document.getElementById('monthFilter').value;
        
        console.log('Applying filters:', { year, month });
        
        // Update charts with filtered data
        updateChartsWithFilters(year, month);
    });

    document.getElementById('resetFilters').addEventListener('click', function() {
        const currentYear = new Date().getFullYear();
        document.getElementById('yearFilter').value = currentYear;
        document.getElementById('monthFilter').value = '';
        
        // Reset charts to original data
        resetCharts();
    });

    function updateChartsWithFilters(year, month) {
        console.log('Updating charts with filters:', { year, month });
        
        // Show loading state
        showLoadingState();
        
        // Make AJAX request to get filtered data
        fetch('/organization/filter-chart-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                year: year,
                month: month
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Filtered data received:', data);
            
            // Update sales chart
            if (salesChart && data.sales) {
                salesChart.data.labels = data.sales.labels;
                salesChart.data.datasets[0].data = data.sales.data;
                salesChart.update();
            }
            
            // Update wishlist chart
            if (wishlistChart && data.wishlist) {
                wishlistChart.data.labels = data.wishlist.labels;
                wishlistChart.data.datasets[0].data = data.wishlist.data;
                wishlistChart.update();
            }
            
            // Update stock chart
            if (stockChart && data.stock) {
                stockChart.data.labels = data.stock.labels;
                stockChart.data.datasets[0].data = data.stock.data;
                stockChart.update();
            }
            
            // Update chart title
            let title = 'Product Sales Performance';
            if (year && month) {
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
                title = `Product Sales - ${months[parseInt(month) - 1]} ${year}`;
            } else if (year) {
                title = `Product Sales - ${year}`;
            }
            updateChartTitle(title);
            
            hideLoadingState();
        })
        .catch(error => {
            console.error('Error fetching filtered data:', error);
            hideLoadingState();
            
            // Fallback to client-side filtering
            fallbackFiltering(year, month);
        });
    }
    
    function fallbackFiltering(year, month) {
        // Fallback filtering when AJAX fails
        if (year && month) {
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                          'July', 'August', 'September', 'October', 'November', 'December'];
            
            if (salesChart) {
                // For fallback, just show all products (no real filtering)
                salesChart.data.labels = salesData.labels;
                salesChart.data.datasets[0].data = salesData.datasets[0].data;
                salesChart.update();
            }
            
            updateChartTitle('Product Sales - ' + months[parseInt(month) - 1] + ' ' + year);
        } else if (year) {
            if (salesChart) {
                salesChart.data.labels = salesData.labels;
                salesChart.data.datasets[0].data = salesData.datasets[0].data;
                salesChart.update();
            }
            updateChartTitle('Product Sales - ' + year);
        } else {
            resetCharts();
            updateChartTitle('Product Sales Performance');
        }
    }
    
    function showLoadingState() {
        const chartWrapper = document.querySelector('.chart-wrapper');
        if (chartWrapper) {
            chartWrapper.style.opacity = '0.5';
            chartWrapper.style.pointerEvents = 'none';
        }
    }
    
    function hideLoadingState() {
        const chartWrapper = document.querySelector('.chart-wrapper');
        if (chartWrapper) {
            chartWrapper.style.opacity = '1';
            chartWrapper.style.pointerEvents = 'auto';
        }
    }

    function resetCharts() {
        // Reset to original data
        if (salesChart) {
            salesChart.data = salesData;
            salesChart.update();
        }
        if (wishlistChart) {
            wishlistChart.data = wishlistData;
            wishlistChart.update();
        }
        if (stockChart) {
            stockChart.data = stockData;
            stockChart.update();
        }
        updateChartTitle('Product Sales Performance');
    }

    function updateChartTitle(title) {
        const chartHeader = document.querySelector('.chart-header h3');
        if (chartHeader) {
            chartHeader.textContent = title;
        }
    }

    // Initialize everything
    initCharts();
});
</script>
@endsection