@extends('Front_layouts.app')

@section('title', 'List an Item')
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
  @vite('resources/css/listAnItem.css')
  @vite('resources/js/listAnItem.js')
      <style>
      body {
      background-image: url("{{ asset('img/background.svg') }}");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: top center;
      }
  </style>
@endsection
@section('content')
<form id="createListingForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="tab-buttons">
      <button class="listAnItemBtn active-button" id="tabBtnDetails" type="button">
          <h1 class="topText">List an Item</h1>
      </button>

       <div class="button-divider"></div>

      <button class="ProductPolicyBtn" id="tabBtnOther" type="button">
          <h1 class="topText">Posting Policies</h1>
      </button>
    </div>

    <div id="tab-details" class="mainContainer tab-content active-tab-content">
      @if(auth()->user()->role === 'organization')
      <div class="section" id="supplierType">
        <h5>Supplier Type</h5>
        <div class="supplier-buttons" id="supplierButtons">
          <button type="button" class="supplier-btn active" data-type="pben">PBEN</button>
          <button type="button" class="supplier-btn" data-type="student-org">Student Organization</button>
        </div>

        <input type="hidden" name="supplier_type" id="supplier_type" value="pben">

        <div id="organizationSelect" style="display:none;margin-top:10px;">
          <select name="organization_id" id="organization_id">
            <option value="" disabled selected>Select Organization</option>
            @foreach($student_orgs as $student_org)
            <option value="{{ $student_org->id }}">{{ $student_org->code }}</option>
            @endforeach
          </select>
          @error('organization_id')
            <div class="form-error">{{ $message }}</div>
          @enderror
        </div>

      </div>
      @else
      <input type="hidden" name="supplier_type" id="supplier_type" value="marketplace">
      @endif

      <div class="section">
        <h5>Item Name<span class="required" title="Required">*</span></h5>
        <input name="name" id="product_name" class="boxes" type="text" placeholder="Item name" value="{{ old('name') }}" />
        @error('name')
            <div class="form-error">{{ $message }}</div>
        @enderror
      </div>
      <div class="section">
        <h5>Item Description<span class="required" title="Required">*</span></h5>
        <div id="editor" style="height: 300px;"></div>
        <input type="hidden" name="description" id="description" value="{{ old('description') }}">
        @error('description')
        <div class="form-error">{{ $message }}</div>
        @enderror
      </div>


      <div class="section" id="priceStocks">
        <div class="priceContainer">
          <h5><span id="priceLabel">Price</span><span class="required" title="Required">*</span></h5>
          <input name="price" class="boxes" type="number" placeholder="₱100" value="{{ old('price') }}"/>
          @error('price')
            <div class="form-error">{{ $message }}</div>
          @enderror
        </div>
        <div class="stocksContainer">
          <h5>Stocks<span class="required" title="Required">*</span></h5>
          <input id="stock-input"name="stock" class="boxes" type="number" placeholder="100" value="{{ old('stock') }}"/>
          @error('stock')
            <div class="form-error">{{ $message }}</div>
          @enderror
        </div>
      </div>

      {{-- File upload to --}}
      <div class="section">
        <h5>Upload Product Images<span class="required" title="Required">*</span></h5>

        <input type="file" name="images[]" id="images" multiple>

        {{-- Order of files after drag & drop (JSON array of FilePond ids mapped to original names) --}}
        <input type="hidden" name="image_order" id="image_order" value="[]">

        @error('images')
            <div class="form-error">{{ $message }}</div>
        @enderror

        @error('images.*')
            <div class="form-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="section">
        <div class="section-header">
          <h5>Add custom variants (optional)</h5>
          <label class="switch">
            <input type="checkbox" id="toggleVariant" />
            <span class="slider"></span>
          </label>
        </div>

        <div class="variants" id="variants" style="display:none;margin-top:15px;">
          <input name="variant_name" type="text" id="variantName" placeholder="Name of variant (E.g. Size)" />

          <div id="optionsContainer">
            <div class="option-row">
              <input type="text" class="option-input" placeholder="Option 1" />
              <input type="number" class="option-input-stock" placeholder="Stock 1" min="1"/>
              <button type="button" id="addOptionBtn">+ Add Option</button>
            </div>
          </div>

          <input type="hidden" name="variants_json" id="variants_json" value="">
        </div>

      @if(auth()->user()->role === 'student')
      <div class="section" id="TradeOrSell">
        <h5>Is this Item for Trade or for Sell?<span class="required" title="Required">*</span></h5>
        <div class="Trade-SellButtons">
            <button type="button" class="filter-btn button active" name="forSaleTrade"data-filter="sale"
              data-filter-type="forSaleTrade">Sale</button>
            <button type="button" class="filter-btn button" name="forSaleTrade"data-filter="trade"
              data-filter-type="forSaleTrade">Trade</button>
              <input type="hidden" name="tradeOrSell" id="tradeOrSell" value="sale">
          </div>
      </div>

      <div class="section" id="qualityOfProduct">
        <h5>Product Condition<span class="required" title="Required">*</span></h5>
        <div class="Trade-SellButtons">
            <button type="button" class="filter-btn-quality button active" name="forSaleTrade"data-filter="new"
              data-filter-type="forSaleTrade">New</button>
            <button type="button" class="filter-btn-quality button" name="forSaleTrade"data-filter="like-new"
              data-filter-type="forSaleTrade">Like-new</button>
            <button type="button" class="filter-btn-quality button" name="forSaleTrade"data-filter="used"
              data-filter-type="forSaleTrade">Used</button>
              <input type="hidden" name="productQuality" id="productQuality" value="new">
          </div>
      </div>
      @endif

      <div class="section" id="tags">
        <h5>Tags</h5>

        <div class="tagsButton" id="presetTags">
          @foreach($tags->where('is_admin', true) as $tag)
            <button
              type="button"
              class="button tag-btn"
              data-id="{{ $tag->id }}"
              data-name="{{ $tag->name }}"
            >
              {{ $tag->name }}
            </button>
          @endforeach
        </div>
        <details class="user-tags-dropdown">
        <summary class="summaryDropdown">
          User Tags <span style="font-size: 14px; color: #555;">(click to expand)</span>
        </summary>

        <div class="user-tags-list">
          @foreach($tags->where('is_admin', false)->sortByDesc('usage_count') as $tag)
            <button
              type="button"
              class="button tag-btn"
              data-id="{{ $tag->id }}"
              data-name="{{ $tag->name }}"
            >
              {{ $tag->name }} ({{ $tag->usage_count }})
            </button>
          @endforeach
        </div>
        </details>
            <input type="text" id="tagInput" placeholder="+ add a tag" />
            <div class="tagsButton" id="tagsContainer"></div>
            <input type="hidden" name="tags_json" id="tags_json" value="[]">
            
  
      </div>

      <div class="section action-buttons">
        <button id="cancelBtn" type="button" class="button cancel-btn">Cancel</button>
        <button class="button confirm-btn">Confirm</button>
      </div>
    </div>
    </div>
    </form>
  <!-- END OF MAIN -->
  @php
    $allowedPolicy = $productPolicies->firstWhere('type', 'allowed');
    $prohibitedPolicy = $productPolicies->firstWhere('type', 'prohibited');
  @endphp
     <div id="tab-other" class="mainContainer tab-content">
      <div class="policyContainer">
        <div class="policyContainer-left">
          <!-- bali pag nisasave sa DB nagiging ol siya so nicoconvert natin to ul ulit -->
          {!! str_replace(['<ol>', '</ol>'], ['<ul>', '</ul>'], $allowedPolicy->content) !!}
        </div>
        <div class="policyContainer-right">
          {!! str_replace(['<ol>', '</ol>'], ['<ul>', '</ul>'], $prohibitedPolicy->content) !!}
        </div>
      </div>
    </div>
  @if($errors->any())
      <div id="errorBar" class="error-bar">
            All required fields must be filled out!<img src="{{ asset('imgModal/barCrossLogo.svg') }}" alt="error" class="error-icon">
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
  @endif
<!-- Unique modal container -->
<div id="uniqueConfirmModal" class="unique-modal-overlay" style="display:none;">
  <div class="unique-modal-content">
    <div id="uniqueModalHeader" class="unique-modal-header">
        <div class="imageWrapper" id="imageWrapper">
            <img id="uniqueModalIcon" src="{{asset('imgModal/createListingLogo.svg')}}" alt="icon" />
        </div>
    </div>
    <h3 id="uniqueHeaderMessage">Ready to List?</h3>
    <p id="uniqueConfirmMessage">Are you sure you want to confirm this listing? This action cannot be undone.</p>
    <div class="unique-modal-buttons">
      <button id="uniqueConfirmNo" class="unique-modal-btn unique-modal-no">Cancel</button>
      <button id="uniqueConfirmYes" class="unique-modal-btn unique-modal-yes">Save</button>
    </div>

  </div>
</div>
  <script>
    const toolbarOptions = [
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'color': [] }],
      [{ 'font': [] }],
      [{ 'align': [] }],

      ['clean']
    ];
    const quill = new Quill('#editor', {
      modules: {
        toolbar: toolbarOptions
      },
      theme: 'snow'
    });
    const hiddenField = document.getElementById('description');
    if (hiddenField && hiddenField.value) {
      quill.root.innerHTML = hiddenField.value;
    }

    // Copy Quill HTML into hidden field before submit
    const form = document.querySelector('form');
    if (form) {
      form.addEventListener('submit', function () {
        hiddenField.value = quill.root.innerHTML;
      });
    }


    
    document.addEventListener('DOMContentLoaded', function () {

      // ----- Register plugins -----
      FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginImageCrop,
        FilePondPluginImageTransform
      );

      // ----- Create instance -----
      const inputElement = document.getElementById('images');
      const pond = FilePond.create(inputElement, {
        allowMultiple: true,
        maxFiles: 10,
         acceptedFileTypes: ['image/png', 'image/jpeg', 'image/webp'],
        labelIdle: 'Drag & Drop your images or <span class="filepond--label-action">Browse</span>',

        // Enable reordering in the FilePond list
        allowReorder: true,

        // Auto-crop to 16:9 based on center — no manual UI
        imageCropAspectRatio: '16:9',

        // Optional: scale images to a max width for performance
        //imageResizeTargetWidth: 1600,
        // /imageResizeMode: 'cover', // maintain crop

        // Process on form submit (no instant upload)
        instantUpload: false,
        allowProcess: false,
        storeAsFile: true  // IMPORTANT: ensures cropped/transformed files replace originals in form POST
      });

      // ----- Update hidden order field whenever files change -----
      const orderField = document.getElementById('image_order');

      function updateImageOrder() {
        // Use file.id to track each file uniquely; map to original name for server pairing
        const order = pond.getFiles().map(f => ({
          id: f.id,
          name: f.file.name
        }));
        orderField.value = JSON.stringify(order);
      }

      pond.on('reorderfiles', updateImageOrder);
      pond.on('updatefiles', updateImageOrder);

      // Run once initially
      updateImageOrder();
    });
      document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });
    document.getElementById('cancelBtn').addEventListener('click', function() {
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
  </script>
@endsection