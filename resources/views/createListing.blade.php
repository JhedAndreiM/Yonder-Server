<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>List an Item</title>
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
</head>

<body>
  <!-- nav bar -->
  <div class="navBar">
    <div class="navBarLeft"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>

    <!-- <div class="navBarMiddle">
        <div class="searchBtnImg"><img id="magnifying"class="searchBtn" src="{{ asset('img/search-icon.svg') }}" alt="" /></div>
        <div class="searchInput"><input id="searchInput" class="search" type="text" placeholder="search" /></div>
      </div> -->

    <div class="navBarRight">
      <img class="hover" src="{{ asset('img/help.png') }}" alt="" />
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
      <img class="hover" src="{{ asset('img/wishlist.png') }}" alt="" />
      <img class="hover" src="{{ asset('img/cart.png') }}" alt="" />
      <div class="dropdown-container">
        <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
        <div class="profile-dropdown" id="profileDropdown" style="display: none;">
          <ul>
            <li><a href="">My Profile</a></li>
            <li><a href=" ">Wishlist</a></li>
            <li><a href="">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- nav bar -->
  <form id="createListingForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h1 class="topText">List an Item</h1>

    <div class="mainContainer">
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
        <h5>Item Name</h5>
        <input name="name" id="product_name" class="boxes" type="text" placeholder="Item name" value="{{ old('name') }}" />
        @error('name')
            <div class="form-error">{{ $message }}</div>
        @enderror
      </div>
      <div class="section">
        <h5>Item Description</h5>
        <div id="editor" style="height: 300px;"></div>
        <input type="hidden" name="description" id="description" value="{{ old('description') }}">
        @error('description')
        <div class="form-error">{{ $message }}</div>
        @enderror
      </div>


      <div class="section" id="priceStocks">
        <div class="priceContainer">
          <h5>Price</h5>
          <input name="price" class="boxes" type="number" placeholder="₱100" value="{{ old('price') }}"/>
          @error('price')
            <div class="form-error">{{ $message }}</div>
          @enderror
        </div>
        <div class="stocksContainer">
          <h5>Stocks</h5>
          <input id="stock-input"name="stock" class="boxes" type="number" placeholder="100" value="{{ old('stock') }}"/>
          @error('stock')
            <div class="form-error">{{ $message }}</div>
          @enderror
        </div>
      </div>

      {{-- File upload to --}}
      <div class="section">
        <h5>Upload Product Images</h5>

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

      <div class="section" id="collegeSection">
        <h5>What college(s) is this item for?</h5>
        <div class="college-buttons">
              @foreach($colleges as $college)
                  <button type="button" class="college-btn" data-code="{{ $college->code }}" data-id="{{ $college->id }}">{{ $college->code }}</button>
              @endforeach
              <input type="hidden" name="colleges_json" id="colleges_json" value="[]">
          </div>
      </div>

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
        <input type="text" name="tags_json" id="tags_json" value="[]">
        
  
      </div>

      <div class="section action-buttons">
        <button class="button cancel-btn">Cancel</button>
        <button class="button confirm-btn">Confirm</button>
      </div>
  </form>
  <!-- END OF MAIN -->
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
  </script>
</body>

</html>