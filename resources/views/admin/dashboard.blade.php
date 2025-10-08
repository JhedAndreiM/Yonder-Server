@extends('Front_layouts.app')

@section('title', 'Admin Page')
@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
    <!-- FilePond core -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <!-- FilePond image plugins -->
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>

    <!-- cropper.js :P -->
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
    <script src="https://unpkg.com/cropperjs/dist/cropper.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    <!-- Sortable.js for drag-and-drop ordering -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <!-- Development -->
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

    <!-- Production -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    @vite('resources/css/admin-younder.css')
@endsection
@section('content')

<div class="container">

    <div class="upload-container">
                <!-- Product Approval -->
        <section class="approval-product">
            <h2>Unapproved Products</h2>
            <div class="search-results-count" style="margin-bottom:10px;">
                <span id="unapprovedProductsCount"></span>
            </div>
            <table id="unapprovedProductsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>View</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="perRow" id="product-row-{{ $product->product_id }}">
                            <td>{{ $product->name }}</td>
                            <td>{!! $product->description !!}</td>
                            <td>
                                <a href="javascript:void(0);" onclick="openModal({{ $product->product_id }})">Details</a>
                            </td>
                            <td>
                                <button class="approveBtn" 
                                        type="button" 
                                        onclick="confirmApproveProduct({{ $product->product_id }})">
                                    Approve
                                </button>
                                <button class="rejectBtn"type="button" onclick="showRejectModal({{ $product->product_id }})">Reject</button>
                            </td>
                        </tr>
                        <div id="modal-{{ $product->product_id }}" class="modal" style="display:none; align-items:center; justify-content:center;">
                            <div class="modal-content" style="position:relative;">
                                <span class="close" onclick="closeModal({{ $product->product_id }})">&times;</span>
                                <h2 style="margin-bottom:0.5rem; margin-top:0.2rem !important;">{{ $product->name }}</h2>
                                <div style="color:#771217; font-weight:600; margin-bottom:1rem;">₱ {{ $product->price }}</div>
                                <p style="margin-bottom:0.2rem;">{!! $product->description !!}</p>
                                <span class="badge-pill {{ $product->forSaleTrade }}">
                                    {{ ucfirst($product->forSaleTrade) }}
                                </span>
                                @php
                                    $tags = DB::table('product_tag')
                                        ->join('tags', 'product_tag.tag_id', '=', 'tags.id')
                                        ->where('product_tag.product_id', $product->product_id)
                                        ->select('tags.name')
                                        ->get();
                                @endphp
                                @if($tags->count() > 0)
                                    <div class="product-tags" style="margin-top:1rem;">
                                        <strong>Tags:</strong>
                                        @foreach ($tags as $tag)
                                            <span style="background:#eee; padding:4px 8px; margin:2px; border-radius:5px; display:inline-block;">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="image-gallery">
                                    @php
                                        $images = \Illuminate\Support\Facades\DB::table('product_images')
                                                    ->where('product_id', $product->product_id)
                                                    ->get();
                                    @endphp
                                    @foreach ($images as $img)
                                        <img src="{{ asset('images/' . $img->image_path) }}" alt="Product Image"
                                             style="cursor:pointer;"
                                             onclick="openImageViewer('{{ asset('images/' . $img->image_path) }}')">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </section>
        <!-- Featured Image Upload -->
        <section class="upload-section">
            <h2>Featured Images</h2>
            @if (session('image_success'))
                <div class="alert alert-success">{{ session('image_success') }}</div>
            @endif
            @if (session('link_success'))
                <div class="alert alert-success">{{ session('link_success') }}</div>
            @endif
            <form action="{{ route('admin.featured.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="image">Select Images</label>
                    <input type="file" name="images[]" id="images" required multiple accept="image/*">
                    <input type="hidden" name="image_order" id="image_order" value="[]">
                </div>
                <button type="submit" class="btn">Upload Images</button>
            </form>
            <div class="preview-section">
                <h3>Current Featured Images</h3>
                <div class="image-preview">
                    @foreach ($featuredImages as $image)
                        <div class="image-card">
                            <img src="{{ asset('Featured/' . $image->image_path) }}" alt="Featured">

                            {{-- Product Link Info --}}
                            <div class="product-link-info">
                                @if($image->product)
                                    <span class="linked-product">🔗 {{ $image->product->name }}</span>
                                @else
                                    <span class="no-link">No product linked</span>
                                @endif
                            </div>

                            {{-- Product Selection --}}
                            <div class="product-selection">
                                <div class="searchable-dropdown" data-image-id="{{ $image->id }}">
                                    <input type="text" 
                                           class="dropdown-search" 
                                           placeholder="Search products..." 
                                           data-image-id="{{ $image->id }}"
                                           value="{{ $image->product ? $image->product->name . ' - ₱' . number_format($image->product->price, 2) : '' }}">
                                    <div class="dropdown-options" id="dropdown-{{ $image->id }}" style="display: none;">
                                        <div class="dropdown-option" data-value="">
                                            <span>Select Product</span>
                                        </div>
                                        @foreach($approvedProducts as $product)
                                            @php
                                                $isLinkedToOtherImage = $featuredImages->where('product_id', $product->product_id)->where('id', '!=', $image->id)->count() > 0;
                                            @endphp
                                            <div class="dropdown-option {{ $isLinkedToOtherImage ? 'disabled' : '' }}" 
                                                 data-value="{{ $product->product_id }}"
                                                 data-search="{{ strtolower($product->name . ' ' . $product->price) }}"
                                                 data-linked-elsewhere="{{ $isLinkedToOtherImage ? 'true' : 'false' }}"
                                                 {{ $image->product_id == $product->product_id ? 'data-selected="true"' : '' }}>
                                                <span class="product-name">{{ $product->name }}</span>
                                                <span class="product-price">₱{{ number_format($product->price, 2) }}</span>
                                                @if($isLinkedToOtherImage)
                                                    <span class="already-linked">(Already linked)</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="btn-link-product" onclick="linkProduct({{ $image->id }})">
                                    {{ $image->product ? 'Update Link' : 'Link Product' }}
                                </button>
                                @if($image->product)
                                    <button class="btn-unlink-product" onclick="unlinkProduct({{ $image->id }})">
                                        Unlink
                                    </button>
                                @endif
                            </div>

                            {{-- Delete Button --}}
                            <form action="{{ route('admin.featured.delete', $image->id) }}" 
                                method="POST" 
                                class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">✖</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <style>
            .btn-edit{border:none;border-radius:.5rem;background:#4f46e5!important;color:#fff;cursor:pointer
                font-size: 1rem;
                padding: 0.7rem 2rem;
                font-weight: 600;}
            .btn-edit:hover{background:#4338ca}
            .btn-cancel{padding:.4rem .75rem;border:1px solid #e5e7eb;border-radius:.5rem;background:#f3f4f6;color:#111827;cursor:pointer}
            .btn-cancel:hover{background:#e5e7eb}
            .user-modal-content{max-width:420px;margin:10% auto;background:#fff;border-radius:.75rem;padding:1rem 1.25rem;box-shadow:0 10px 25px rgba(0,0,0,.15)}
            .user-modal-content h3{margin-top:0;margin-bottom:.75rem}
            .user-modal-content label{display:block;font-weight:600;margin-bottom:.25rem}
            .user-modal-content input,.user-modal-content select{width:100%;padding:.5rem .6rem;border:1px solid #e5e7eb;border-radius:.5rem}
            .user-modal-content .close{float:right;font-size:1.5rem;line-height:1;cursor:pointer}
            .change-password-input-wrapper{position:relative;display:flex;align-items:center}
            .change-password-input-wrapper input{padding-right:2.25rem}
            .toggle-password-visibility{position:absolute;right:.5rem;display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;color:#6b7280;cursor:pointer}
            .toggle-password-visibility.eye-open{color:#10b981}
            .password-requirements ul{margin:.25rem 0 0 .9rem;padding:0}
            .password-requirements li{font-size:.85rem;color:#6b7280}
            .password-valid{color:#10b981}
            .password-invalid{color:#ef4444}
            .match-valid{color:#10b981}
            .match-invalid{color:#ef4444}
        </style>

        <script>
        // Unapproved Products Count
        function updateUnapprovedProductsCount() {
            const table = document.getElementById('unapprovedProductsTable');
            if (!table) return;
            const countSpan = document.getElementById('unapprovedProductsCount');
            // Count rows in tbody (excluding those with display:none)
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const visibleRows = rows.filter(row => row.style.display !== 'none');
            countSpan.textContent = visibleRows.length + ' unapproved product' + (visibleRows.length === 1 ? '' : 's');
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateUnapprovedProductsCount();
        });
        // When a product row is deleted, update the count
        function removeUnapprovedProductRow(productId) {
            const row = document.getElementById('product-row-' + productId);
            if (row) row.remove();
            updateUnapprovedProductsCount();
        }
        // Patch approveProduct to update count
        function approveProduct(productId, tagsArray) {
            fetch(`/admin/approve/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    tags: tagsArray
                })
            })
            .then(response => {
                if (!response.ok) throw new Error("Approval failed");
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                removeUnapprovedProductRow(productId);
            })
            .catch(err => console.error("Error:", err));
        }
        // Patch deleteProduct to update count
        function deleteProduct(productId, reportId) {
            console.log('went here');
            confirmAction(
                'Are you sure you want to delete this product?',
                () => {
                    $.ajax({
                        url: '/admin/delete-product/' + productId + '/' + reportId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function () {
                            removeUnapprovedProductRow(productId);
                            showModal('Product deleted.', "info");
                        },
                        error: function () {
                            showModal('Something went wrong while deleting the product.', "info");
                        }
                    });
                },
                'Delete Product'
            );
        }
            function openUserModal(id){
                var modal=document.getElementById('user-modal-'+id);
                if(modal){ 
                    modal.style.display='block'; 
                    if (typeof attachPasswordValidation==='function') attachPasswordValidation(id);
                    if (typeof initializePasswordToggles==='function') initializePasswordToggles();
                }
            }
            function closeUserModal(id){
                var modal=document.getElementById('user-modal-'+id);
                if(modal){ modal.style.display='none'; }
            }
            (function(){
                document.addEventListener('keydown',function(e){
                    if(e.key==='Escape'){
                        document.querySelectorAll('[id^="user-modal-"]').forEach(function(m){ m.style.display='none'; });
                    }
                });
                document.addEventListener('click',function(e){
                    var modal=e.target.closest('[id^="user-modal-"]');
                    if(modal && e.target===modal){ modal.style.display='none'; }
                });
                // Event delegation for eye toggle: switch input type and show visual indicator
                function initializePasswordToggles(){
                    if (window.__pwdToggleBound) return; // bind once
                    document.addEventListener('click', function(e){
                        var toggle = e.target.closest('.toggle-password-visibility');
                        if(!toggle) return;
                        e.preventDefault();
                        var targetId = toggle.getAttribute('data-target');
                        var input = document.getElementById(targetId);
                        if(!input) return;
                        var isOpen = input.type === 'password';
                        input.type = isOpen ? 'text' : 'password';
                        toggle.classList.toggle('eye-open', isOpen);
                        toggle.setAttribute('aria-pressed', isOpen ? 'true' : 'false');
                    });
                    window.__pwdToggleBound = true;
                }
                function attachPasswordValidation(userId){
                    var pwd = document.getElementById('password-'+userId);
                    var conf = document.getElementById('password_confirmation-'+userId);
                    var reqBox = document.getElementById('password-req-'+userId);
                    var saveBtn = document.getElementById('save-user-'+userId);
                    var matchMsg = document.getElementById('password-match-'+userId);
                    if(!pwd || !conf) return;

                    function validate(){
                        var val = pwd.value || '';
                        var hasLen = val.length >= 8;
                        var hasLetter = /[A-Za-z]/.test(val);
                        var hasUpper = /[A-Z]/.test(val);
                        var hasLower = /[a-z]/.test(val);
                        var hasMixed = hasUpper && hasLower;
                        var hasNumber = /\d/.test(val);

                        if (reqBox) reqBox.style.display = (document.activeElement===pwd || val) ? 'block' : 'none';
                        function setState(id, ok){
                            var el = document.getElementById(id+'-'+userId);
                            if(!el) return;
                            el.classList.toggle('password-valid', ok);
                            el.classList.toggle('password-invalid', !ok);
                        }
                        setState('req-length', hasLen);
                        setState('req-letters', hasLetter);
                        setState('req-mixed', hasMixed);
                        setState('req-numbers', hasNumber);

                        var match = (conf.value || '') === val && val.length>0;
                        if (matchMsg){
                            matchMsg.style.display = (conf.value || val) ? 'block' : 'none';
                            matchMsg.textContent = match ? 'Passwords match' : 'Passwords do not match';
                            matchMsg.className = match ? 'match-valid' : 'match-invalid';
                        }

                        if(saveBtn){
                            // Only restrict when password field has content; empty means optional
                            if(val.length>0){
                                saveBtn.disabled = !(hasLen && hasLetter && hasMixed && hasNumber && match);
                            } else {
                                saveBtn.disabled = false;
                            }
                        }
                    }
                    ['input','blur','focus'].forEach(function(evt){
                        pwd.addEventListener(evt, validate);
                        conf.addEventListener(evt, validate);
                    });
                    validate();
                }
                window.initializePasswordToggles = initializePasswordToggles;
                window.attachPasswordValidation = attachPasswordValidation;

                // Initialize on load (delegated, binds once)
                initializePasswordToggles();
                document.querySelectorAll('[id^="user-modal-"]').forEach(function(m){
                    var id = m.id.replace('user-modal-','');
                    attachPasswordValidation(id);
                });
            })();
        </script>

        <!-- Excel Upload -->
        <section class="upload-section">
            <h2>Import User Data (Excel)</h2>
            @if (session('excel_success'))
                <div class="alert alert-success">{{ session('excel_success') }}</div>
            @endif
            @if (session('excel_error'))
                <div class="alert alert-danger">{{ session('excel_error') }}</div>
            @endif
            <form class="importUserData" action="{{ route('upload.users') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Choose Excel File</label>
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls">
                </div>
                <button type="submit" class="btn">Import Excel</button>
            </form>
        </section>

        <!-- Product Policy -->
        <section class="productPolicy">
            @if (session('product_policy_success'))
                <div class="alert alert-success">{{ session('product_policy_success') }}</div>
            @endif
            <form id="policyForm" action="{{ route('admin.productPolicy') }}" method="POST">
                @csrf
                <div class="section">
                    <h5>Allowed Listing</h5>
                    <div id="editor"></div>
                    <input type="hidden" name="descriptionAllowed" id="descriptionAllowed">
                </div>
                <div class="section">
                    <h5>Prohibited</h5>
                    <div id="editorProhibited"></div>
                    <input type="hidden" name="descriptionProhibited" id="descriptionProhibited">
                </div>
                <button type="submit" class="btn">Save Policy</button>
                <button type="button" onclick="window.location.reload()">Cancel</button>
            </form>
        </section>

        <section class="frequentlyAsked">
            <h2>Frequenty Asked Questions</h2>
            <section class="addingOfCategory">
                <h2>Add Category</h2>
                <div id="ajaxMessagesCategory"></div>
                @if (session('college_success'))
                <div class="alert alert-success">{{ session('college_success') }}</div>
                @endif
                @if (session('college_error'))
                    <div class="alert alert-danger">{{ session('college_error') }}</div>
                @endif
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">{{$errors->first()}}</div>
                @endif
                <form id="addCategoryForm" action="{{ route('faq-category.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="categoryName"></label>
                        <input type="text" name="categoryName" placeholder="Ex. Getting Started">
                    </div>
                    <button type="submit">Add Category</button>
                </form>
            </section>
            <!-- Category Table -->
             <section class="categoryList">
                <div id="ajaxMessagesCategoryList"></div>
                <table class="collegeTable">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryList as $category)
                    <tr id="collegeRow{{ $category->id }}"> <!-- Add this ID -->
                        <td class="collegeName">{{ $category->name }}</td> <!-- Add class -->
                        <td>
                            <!-- Edit -->
                            <button type="button" class="editBtnCategory" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                Edit
                            </button>

                            <!-- Delete -->
                            <button type="button" class="deleteBtn" data-id="{{ $category->id }}">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
             </section>
             <!-- Category Modal -->
            <div id="editCategoryModal" class="collegeModal">
                <div class="modal-wrapper">
                    <h3>Edit College</h3>

                    <div id="modalErrorCategory" class="alert alert-danger" style="display:none;"></div>

                    <form id="editCategoryForm">
                        @csrf
                        <input type="hidden" id="editCategoryId">

                        <label for="editCategoryName">Name</label>
                        <input type="text" id="editCategoryName" required>

                        <button type="submit">Save Changes</button>
                        <button type="button" id="closeModalCategory">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
        <section class="addingOfQuestion">
    <h2>Adding Of Question</h2>

    <!-- Form -->
     @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('faq.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="categorySelect">Category</label>
            <select id="categorySelect" name="category_id" required>
                <option value="">-- Select Category --</option>
                @foreach($categoryList as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="questionInput">Question</label>
            <input type="text" id="questionInput" name="question" required>
        </div>

        <div class="form-group">
            <label for="answerInput">Answer</label>
            <textarea id="answerInput" name="answer" rows="3" required></textarea>
        </div>

        <button type="submit">Add FAQ</button>
    </form>

    <!-- FAQ Table -->
    <table class="faqTable">
        <thead>
            <tr>
                <th>Category</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $faq)
            <tr>
                <td>{{ $faq->category->name ?? 'N/A' }}</td>
                <td>{{ $faq->question }}</td>
                <td>{{ $faq->answer }}</td>
                <td>
                    <!-- Edit Button triggers modal -->
                    <button type="button" class="faqEditBtn" data-modal="editModal{{ $faq->id }}">Edit</button>

                    <!-- Delete form -->
                    <form action="{{ route('faq.destroy', $faq) }}" method="POST" style="display:inline;" class="faq-delete-form-{{ $faq->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="faq-delete-btn" data-faq-id="{{ $faq->id }}">Delete</button>
                    </form>

                    <!-- Edit Modal -->
                    <div id="editModal{{ $faq->id }}" class="faqEditModal">
                        <div class="faqModalContent">
                            <span class="faqCloseBtn" data-modal="editModal{{ $faq->id }}">&times;</span>
                            <h3>Edit FAQ</h3>
                            <form action="{{ route('faq.update', $faq) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="categorySelect{{ $faq->id }}">Category</label>
                                    <select id="categorySelect{{ $faq->id }}" name="category_id" required>
                                        @foreach($categoryList as $category)
                                            <option value="{{ $category->id }}" @if($category->id == $faq->category_id) selected @endif>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="questionInput{{ $faq->id }}">Question</label>
                                    <input type="text" id="questionInput{{ $faq->id }}" name="question" value="{{ $faq->question }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="answerInput{{ $faq->id }}">Answer</label>
                                    <textarea id="answerInput{{ $faq->id }}" name="answer" rows="3" required>{{ $faq->answer }}</textarea>
                                </div>
                                <button type="submit">Update FAQ</button>
                            </form>
                        </div>
                    </div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</section>


        <!-- Adding of College -->
        <section class="collegeSection">
            <section class="addingOfCollege">
            <h2>Add College</h2>
            @if (session('college_success'))
                <div class="alert alert-success">{{ session('college_success') }}</div>
            @endif
            @if (session('college_error'))
                <div class="alert alert-danger">{{ session('college_error') }}</div>
            @endif
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">{{$errors->first()}}</div>
            @endif
            <form id="collegeForm" action="{{route('admin.addCollege')}}" method="POST">
                @csrf
                <div>
                    <label for="code">College Code</label>
                    <input type="text" id="college_code" name="code" placeholder="Ex. CCST" required>
                </div>
                <div>
                    <label for="name">College Name</label>
                    <input type="text" id="college_name" name="name" placeholder="Enter college name" required>
                </div>
                <button type="submit">Add College</button>
            </form>
        </section>
        <!-- College Table -->
        <section class="collegeList">
            <div id="ajaxMessages"></div>
            <table class="collegeTable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collegeList as $college)
                    <tr id="collegeRow{{ $college->id }}"> <!-- Add this ID -->
                        <td class="collegeCode">{{ $college->code }}</td> <!-- Add class -->
                        <td class="collegeName">{{ $college->name }}</td> <!-- Add class -->
                        <td>
                            <!-- Edit -->
                            <button type="button" class="editBtn" data-id="{{ $college->id }}" data-code="{{ $college->code }}" data-name="{{ $college->name }}">
                                Edit
                            </button>

                            <!-- Delete -->
                            <form class="deleteForm" data-id="{{ $college->id }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                               <button type="submit" class="deleteBtnCollege">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        <!-- Modal for College -->
        <div id="editCollegeModal" class="collegeModal">
            <div class="modal-wrapper">
                <h3>Edit College</h3>

                <div id="modalError" class="alert alert-danger" style="display:none;"></div>

                <form id="editCollegeForm">
                    @csrf
                    <input type="hidden" id="editCollegeId">

                    <label for="editCode">Code</label>
                    <input type="text" id="editCode" required>

                    <label for="editName">Name</label>
                    <input type="text" id="editName" required>

                    <button type="submit">Save Changes</button>
                    <button type="button" id="closeModalCollege">Cancel</button>
                </form>
            </div>
        </div>
        </section>

        <!-- Adding of Student Orgs -->
        <section class="studOrgsSection">
            <section class="addingOfStudOrg">
            <h2>Add Student Organization</h2>
            @if (session('studOrg_success'))
                <div class="alert alert-success">{{ session('studOrg_success') }}</div>
            @endif
            @if (session('studOrg_error'))
                <div class="alert alert-danger">{{ session('studOrg_error') }}</div>
            @endif
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">{{$errors->first()}}</div>
            @endif
            <form id="studOrgForm" action="{{route('admin.addStudOrg')}}" method="POST">
                @csrf
                <div>
                    <label for="code">Student Organization Code</label>
                    <input type="text" id="studOrg_code" name="code" placeholder="Short Name" required>
                </div>
                <div>
                    <label for="name">Student Organization Name</label>
                   <input type="text" id="studOrg_name" name="name" placeholder="Enter Student Organization Name" required>
                </div>
                <button type="submit">Add Student Org</button>
            </form>
        </section>
        <!-- Stud Table -->
        <section class="collegeList">
            <div id="ajaxMessagesStudOrg"></div>
            <table class="collegeTable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studOrgList as $studOrg)
                    <tr id="studOrgRow{{ $studOrg->id }}"> <!-- Add this ID -->
                        <td class="studOrgCode">{{ $studOrg->code }}</td> <!-- Add class -->
                        <td class="studOrgName">{{ $studOrg->name }}</td> <!-- Add class -->
                        <td>
                            <!-- Edit -->
                            <button type="button" class="editBtnStudOrg" data-id="{{ $studOrg->id }}" data-code="{{ $studOrg->code }}" data-name="{{ $studOrg->name }}">
                                Edit
                            </button>

                            <!-- Delete -->
                            <form class="delFormStudOrg" data-id="{{ $studOrg->id }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="deleteBtnStudOrg">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        <!-- Modal for Student org -->
        <div id="editStudModal" class="collegeModal">
            <div class="modal-wrapper">
                <h3>Edit Student Org</h3>

                <div id="modalErrorStudOrg" class="alert alert-danger" style="display:none;"></div>

                <form id="editStudOrgForm">
                    @csrf
                    <input type="hidden" id="editStudOrgId">

                    <label for="editCode">Code</label>
                    <input type="text" id="editStudOrgCode" required>

                    <label for="editName">Name</label>
                    <input type="text" id="editStudOrgName" required>

                    <button type="submit">Save Changes</button>
                    <button type="button" id="closeModalStudOrg">Cancel</button>
                </form>
            </div>
        </div>
        </section>
        <!-- Voucher Management -->
        <section class="addingOfVoucher">
            @if (session('credit_success'))
                <div class="alert alert-success">{{ session('credit_success') }}</div>
            @endif
            <form class="creditPercentageForm" method="POST" action="{{route('admin.credit')}}">
                @csrf
                <h2>Points Percentage</h2>
                <span>Current Exchange Rate: {{ $creditPercentage->percentage }}%</span>
                <div class="example">
                    <small>
                        Example: 100 pesos = 
                        {{ 100 * ($creditPercentage->percentage / 100) }} points
                    </small>
                </div>
                <div class="creditLabel">
                    <label for="percentage">Points Percentage: </label>
                    <input type="number" min="1" step="0.01" name="percentage" value="{{ $creditPercentage->percentage }}">
                </div>
                <button type="submit">Save</button>
            </form>
            @if (session('voucher_success'))
                <div class="alert alert-success" style="margin-top:1rem;">{{ session('voucher_success') }}</div>
            @endif
            <form class="formAddingOfVoucher" action="{{route('admin.voucher')}}" method="POST">
            @csrf
                <h2>Add a Voucher</h2>
                <div class="formAddingOfVoucher-input">
                    <label for="voucherAmount">Voucher Amount: </label>
                    <input type="number" name="voucherAmount" id="voucherAmount" min="1" max="1000" required>
                    <label for="voucherPrice">Points to Redeem: </label>
                    <input type="number" name="voucherPrice" id="voucherPrice" min="1" max="1000" required>
                </div>
                <div class="formAddingOfVoucher-buttons">
                    <button class="btn">Submit</button>
                    <button type="reset" class="voucherResetBtn">Cancel</button>
                </div>
            </form>
            <div class="voucherList">
                <h2>Current Redeemable Vouchers</h2>
                @foreach($voucherList as $voucher)
                <div class="voucherCard" id="voucher-card-{{$voucher->id}}">
                    <div class="voucherInfo">
                        <h3>P {{$voucher->amount}}</h3>
                        <p class="voucherCost">Cost: {{$voucher->price}} Points</p>
                    </div>
                    <div class="voucherDelete">
                        <button class="btn deleteVoucherBtn" data-id="{{$voucher->id}}">Delete</button>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- User Role Management -->
        <section class="user-role-management">
            <h2>User Management</h2>
            @if (session('user_success'))
                <div class="alert alert-success">{{ session('user_success') }}</div>
            @endif
            
            <!-- Search Input -->
            <div class="search-container">
                <input type="text" 
                       id="userSearch" 
                       placeholder="Search by name, email, or role..." 
                       class="search-input">
                <div class="search-results-count">
                    <span id="userSearchResultsCount"></span>
                </div>
            </div>
            
            <table id="userManagementTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Role Action</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr id="user-row-{{ $user->id }}">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                @if ($user->role !== 'organization')
                                    <form action="{{ route('admin.changeRole') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="role" value="organization">
                                        <button type="submit">Make Organization</button>
                                    </form>
                                @else
                                    <span>Already Organization</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-edit" onclick="openUserModal({{ $user->id }})">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @foreach ($users as $user)
                <div id="user-modal-{{ $user->id }}" class="modal" style="display:none;">
                    <div class="modal-content user-modal-content">
                        <span class="close" onclick="closeUserModal({{ $user->id }})">&times;</span>
                        <h3>Edit User</h3>
                        <form action="{{ route('admin.users.updateDetails', $user->id) }}" method="POST">
                            @csrf
                            <div style="margin-bottom:.75rem;">
                                <label for="name-{{ $user->id }}">Name</label>
                                <input id="name-{{ $user->id }}" type="text" name="name" value="{{ $user->name }}" required>
                                <label for="middle_name">Middle Name</label>
                                <input id="middle_name-{{ $user->id }}" type="text" name="middle_name" value="{{ $user->middle_name }}">
                                <label for="last_name">Last Name</label>
                                <input id="last_name-{{ $user->id }}" type="text" name="last_name" value="{{ $user->last_name }}" required>
                            </div>
                            <div style="margin-bottom:.75rem;">
                                <label for="gender-{{ $user->id }}">Sex</label>
                                <select id="gender-{{ $user->id }}" name="gender">
                                    <option value="" {{ empty($user->gender) ? 'selected' : '' }}>--</option>
                                    <option value="male" {{ (isset($user->gender) && strcasecmp($user->gender, 'male')===0) ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ (isset($user->gender) && strcasecmp($user->gender, 'female')===0) ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div style="margin-bottom:.75rem;">
                                <label for="password-{{ $user->id }}">New Password (optional)</label>
                                <div class="change-password-input-wrapper">
                                    <input id="password-{{ $user->id }}" type="password" name="password" placeholder="Leave blank to keep current">
                                    <span class="toggle-password-visibility" data-target="password-{{ $user->id }}">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                          <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </span>
                                </div>
                                <div class="password-requirements" id="password-req-{{ $user->id }}" style="display:none; margin-top:.5rem;">
                                  <small>Password must contain:</small>
                                  <ul>
                                    <li id="req-length-{{ $user->id }}">At least 8 characters</li>
                                    <li id="req-letters-{{ $user->id }}">At least one letter</li>
                                    <li id="req-mixed-{{ $user->id }}">Both uppercase and lowercase letters</li>
                                    <li id="req-numbers-{{ $user->id }}">At least one number</li>
                                  </ul>
                                </div>
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label for="password_confirmation-{{ $user->id }}">Confirm Password</label>
                                <div class="change-password-input-wrapper">
                                    <input id="password_confirmation-{{ $user->id }}" type="password" name="password_confirmation" placeholder="Confirm new password">
                                    <span class="toggle-password-visibility" data-target="password_confirmation-{{ $user->id }}">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                          <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </span>
                                </div>
                                <small id="password-match-{{ $user->id }}" style="display:none;"></small>
                            </div>
                            <div>
                                <button type="submit" class="btn" id="save-user-{{ $user->id }}">Save Changes</button>
                                <button type="button" class="btn btn-cancel" onclick="closeUserModal({{ $user->id }})">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </section>
<section class="tags-section">
    <h2>Tag Management</h2>

    @if (session('tag_success'))
        <div class="alert alert-success">{{ session('tag_success') }}</div>
    @endif
    @if ($errors->has('name'))
        <div class="alert alert-danger">{{ $errors->first('name') }}</div>
    @endif
    
    
    <!-- Create Tag -->
    <form id="tagForm" action="{{ route('admin.tags.store') }}" method="POST" style="margin-bottom:1rem;">
        @csrf
        <label for="tagName" style="display:block; font-weight:600;">New Tag</label>
        <input id="tagName" type="text" name="name" placeholder="e.g. electronics" required>
        <button type="submit">Add Tag</button>
    </form>

    <div id="ajaxMessagesTags"></div>

    <!-- Tag List -->
    <div class="tag-filters" style="display:flex; gap:.5rem; align-items:center; margin:.5rem 0 1rem;">
    <input id="tagSearch" type="text" placeholder="Search tag name…" />
    <select id="tagCreatedByFilter">
        <option value="">All creators</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select>
    <button type="button" id="tagFilterReset">Reset</button>
    </div>
    <table class="collegeTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Usage</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tagTableBody">
        @foreach($tagss as $tag)
        <tr id="tagRow{{ $tag->id }}" 
            data-created-by="{{ $tag->is_admin ? 'admin' : 'user' }}">
            <td class="tagName">{{ $tag->name }}</td>
            <td>{{ $tag->usage_count }}</td>
            <td>{{ $tag->is_admin ? 'Admin' : 'User' }}</td>
            <td>
            <button type="button" class="btn-edit editBtnTag" data-id="{{ $tag->id }}" data-name="{{ $tag->name }}">Edit</button>
            <button type="button" class="rejectBtn deleteBtnTag" data-id="{{ $tag->id }}">Delete</button>
            </td>
        </tr>
        @endforeach

        <!-- No results row (hidden by default, shown via JS) -->
        <tr id="tagNoResultsRow" style="display:none;">
        <td colspan="4" style="text-align:center; color:#6b7280;">No matching tags</td>
        </tr>
        </tbody>
    </table>

    <!-- Edit Tag Modal (mirrors your existing modal style) -->
    <div id="editTagModal" class="collegeModal">
        <div class="modal-wrapper">
            <h3>Edit Tag</h3>
            <div id="modalErrorTag" class="alert alert-danger" style="display:none;"></div>
            <form id="editTagForm">
                @csrf
                <input type="hidden" id="editTagId">
                <label for="editTagName">Name</label>
                <input type="text" id="editTagName" required>
                <button type="submit">Save Changes</button>
                <button type="button" id="closeModalTag">Cancel</button>
            </form>
        </div>
    </div>
</section>
        <!-- REPORTED USER -->
        <section class="report-user">
            <h2>Reported Users</h2>
            <!-- Search Input -->
            <div class="search-container">
                <input type="text" 
                       id="reportedUsersSearch" 
                       placeholder="Search by reported user, reporter, or reason..." 
                       class="search-input">
                <div class="search-results-count">
                    <span id="reportedUsersCount"></span>
                </div>
            </div>
            <div id="ajaxMessagesReportedUser"></div>

            <table class="collegeTable" id="reportedUsersTable">
                <thead>
                    <tr>
                        <th>Reported User</th>
                        <th>Reporter</th>
                        <th>Reason</th>
                        <th>Details</th>
                        <th>Evidence</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userReports as $report)
                        <tr id="user-report-row-{{ $report->id }}">
                            <td>{{ $report->reportedUser->name ?? 'Unknown' }}</td>
                            <td>{{ $report->reporter->name ?? 'Unknown' }}</td>
                            <td>{{ $report->reason }}</td>
                            <td>{{ $report->details }}</td>
                            <td>
                                @if($report->evidence)
                                    <img src="{{ asset($report->evidence) }}"
                                        alt="Evidence"
                                        style="max-height:60px; border:1px solid #ccc; border-radius:5px; cursor:pointer;"
                                        onclick="openImageViewer('{{ asset($report->evidence) }}')">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <button class="btn-edit approveUserReportBtn" data-id="{{ $report->id }}">Allow</button>
                                <button class="btn banUserReportBtn" data-id="{{ $report->id }}">Ban User</button>
                                <button class="btn suspendUserReportBtn" data-id="{{ $report->id }}">Suspend User</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
<section class="report-user-unban">
    <h2>Banned & Suspended Users</h2>
    
    <!-- Search Input -->
    <div class="search-container">
        <input type="text" 
               id="bannedUsersSearch" 
               placeholder="Search by name, email, or status..." 
               class="search-input">
        <div class="search-results-count">
            <span id="searchResultsCount"></span>
        </div>
    </div>
    
    <table id="bannedUsersTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Suspension Until</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @if($user->role == 'suspended' || $user->role == 'banned')
                <tr id="banned-user-row-{{ $user->id }}">
                    <td>{{ $user->name }} {{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="status-badge status-{{ $user->role }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        @if($user->role == 'suspended' && $user->suspension_until)
                            {{ $user->suspension_until->format('M j, Y g:i A') }}
                            @if($user->suspension_until->isPast())
                                <span class="expired-label">(Expired)</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($user->role == 'banned')
                            <button class="btn-edit" onclick="unbanUser({{ $user->id }})">Unban</button>
                        @elseif($user->role == 'suspended')
                            <button class="btn-edit" onclick="unsuspendUser({{ $user->id }})">Unsuspend</button>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</section>
<!-- Reports -->
<section class="report-show">
    <h2>Reported Products</h2>
    <!-- Search Input -->
    <div class="search-container">
        <input type="text" 
               id="reportedProductsSearch" 
               placeholder="Search by product name, reporter, or report ID..." 
               class="search-input">
        <div class="search-results-count">
            <span id="reportedProductsCount"></span>
        </div>
    </div>
    <table id="reportedProductsTable">
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Product Name</th>
                <th>Reported By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr class="perRow" id="report-row-{{ $report->report_id }}">
                    <td>{{ $report->report_id }}</td>
                    <td>{{ $report->product_name }}</td>
                    <td>{{ $report->reporter_name }} {{ $report->reporter_last_name }}</td>
                    <td>
                        <a href="javascript:void(0);" onclick="openModal({{ $report->report_id }})" style="margin-right: 50px;">View</a>
                        <span style="display:inline-block; margin-right: 5px;">
                            <button class="btn-edit" onclick="allowReport({{ $report->report_id }})">Allow</button>
                        </span>
                        <span style="display:inline-block;">
                            <button class="btn reportProdDelete" onclick="deleteProduct({{ $report->product_id }}, {{ $report->report_id }})">Delete</button>
                        </span>
                    </td>
                </tr>

                <!-- Modal -->
                <div id="modal-{{ $report->report_id }}" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal({{ $report->report_id }})">&times;</span>
                        <h2>{{ $report->product_name }}</h2>
                        <p>{!! $report->description !!}</p>
                        <h3 style="margin-bottom: 0px;">Reason for Reporting</h3>
                        <p style="margin-top: 5px;">{{ $report->message}}</p>

                        <div class="image-gallery">
                            @php
                                $images = $report->images ? explode(',', $report->images) : [];
                            @endphp
                            @foreach ($images as $img)
                                <img src="{{ asset('images/' . trim($img)) }}" alt="Product Image" onclick="openImageViewer('{{ asset('images/' . trim($img)) }}')">
                                
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</section>

    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="rejectModal">
        <div class="modal-contents">
            <h3>Reject Product</h3>
            <form id="rejectForm">
                @csrf
                <input type="hidden" name="product_id" id="rejectProductId">
                <div class="selectRejectionReason">
                <label for="messageRejection">Reason For Rejecting:</label>
                <select name="message" id="messageRejection">
                    <option value="poor_image_quality">Poor Image Quality</option>
                    <option value="incorrect_category">Incorrect Category</option>
                    <option value="prohibited_item">Prohibited Item</option>
                    <option value="duplicate_listing">Duplicate Listing</option>
                    <option value="pricing_error">Pricing Error</option>
                    <option value="misleading_description">Misleading Description</option>
                    <option value="policy_violation">Violation of Policies</option>
                    <option value="others">Others</option>
                </select>
                </div>
                <textarea hidden name="message" id="rejectMessage"></textarea>
                <div>
                    <button type="submit" class="btn">Send</button>
                    <button type="button" class="btn" onclick="hideRejectModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Suspension Duration Modal -->
    <div id="suspensionModal" class="rejectModal" style="display: none;">
        <div class="modal-contents">
            <h3>Suspend User</h3>
            <form id="suspensionForm" class="suspensionForm">
                @csrf
                <input type="hidden" name="report_id" id="suspensionReportId">
                <div class="suspendUserDiv">
                <label for="suspensionDuration">Suspension Duration:</label>
                <select name="duration" id="suspensionDuration" required>
                    <option value="">Select Duration</option>
                    <option value="1">1 Hour</option>
                    <option value="6">6 Hours</option>
                    <option value="12">12 Hours</option>
                    <option value="24">1 Day</option>
                    <option value="72">3 Days</option>
                    <option value="168">1 Week</option>
                    <option value="336">2 Weeks</option>
                    <option value="720">1 Month</option>
                    <option value="2160">3 Months</option>
                </select>
                </div>
                <div style="margin-top:1rem;">
                    <button type="submit" class="btn">Suspend User</button>
                    <button type="button" class="btn" onclick="hideSuspensionModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Show/Hide Student Org & Marketplace -->
    <section class="disableStudOrgMarketplace">
        <form method="POST" action="{{ route('admin.disableButtons') }}">
            @csrf
            <label>
                <input type="checkbox" name="show_student_org" {{ \App\Models\disableButtons::getValue('show_student_org') ? 'checked' : '' }}>
                Show Student Organization
            </label>
            <label>
                <input type="checkbox" name="show_marketplace" {{ \App\Models\disableButtons::getValue('show_marketplace') ? 'checked' : '' }}>
                Show Marketplace
            </label>
            <button type="submit">Save Settings</button>
        </form>
    </section>

</div>
<!-- Image Viewer Modal -->
<div id="imageViewerModal" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
            background:rgba(0,0,0,0.8); align-items:center; justify-content:center; z-index:9999;">
    <span onclick="closeImageViewer()" 
          style="position:absolute; top:20px; right:30px; color:#fff; font-size:2rem; cursor:pointer;">&times;</span>
    
    <img id="imageViewerImg" src="" 
         style="max-width:90%; max-height:90%; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.5);">
</div>
        <script>
        // Reported Users Count
        function updateReportedUsersCount() {
            const table = document.getElementById('reportedUsersTable');
            if (!table) return;
            const countSpan = document.getElementById('reportedUsersCount');
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const visibleRows = rows.filter(row => row.style.display !== 'none');
            countSpan.textContent = visibleRows.length + ' reported user' + (visibleRows.length === 1 ? '' : 's');
        }
        // Reported Products Count
        function updateReportedProductsCount() {
            const table = document.getElementById('reportedProductsTable');
            if (!table) return;
            const countSpan = document.getElementById('reportedProductsCount');
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const visibleRows = rows.filter(row => row.style.display !== 'none');
            countSpan.textContent = visibleRows.length + ' reported product' + (visibleRows.length === 1 ? '' : 's');
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateUnapprovedProductsCount();
            updateReportedUsersCount();
            updateReportedProductsCount();

            // Reported Users Search
            const ruSearchInput = document.getElementById("reportedUsersSearch");
            const ruTable = document.getElementById("reportedUsersTable");
            if (ruSearchInput && ruTable) {
                const ruRows = Array.from(ruTable.querySelectorAll("tbody tr"));
                ruSearchInput.addEventListener("input", function() {
                    const searchTerm = ruSearchInput.value.trim().toLowerCase();
                    ruRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? "" : "none";
                    });
                    updateReportedUsersCount();
                });
            }

            // Reported Products Search
            const rpSearchInput = document.getElementById("reportedProductsSearch");
            const rpTable = document.getElementById("reportedProductsTable");
            if (rpSearchInput && rpTable) {
                const rpRows = Array.from(rpTable.querySelectorAll("tbody tr"));
                rpSearchInput.addEventListener("input", function() {
                    const searchTerm = rpSearchInput.value.trim().toLowerCase();
                    rpRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? "" : "none";
                    });
                    updateReportedProductsCount();
                });
            }
        });
        // When a reported user row is deleted, update the count
        function removeReportedUserRow(userReportId) {
            const row = document.getElementById('user-report-row-' + userReportId);
            if (row) row.remove();
            updateReportedUsersCount();
        }
        // When a reported product row is deleted, update the count
        function removeReportedProductRow(reportId) {
            const row = document.getElementById('report-row-' + reportId);
            if (row) row.remove();
            updateReportedProductsCount();
        }
        function confirmAction(message, onConfirm, title = "Confirm Action") {
            const overlay = document.getElementById("confirm-action-overlay");
            const msg = document.getElementById("confirm-action-message");
            const titleEl = document.getElementById("confirm-action-title");
            const yesBtn = document.getElementById("confirm-action-yes");
            const noBtn = document.getElementById("confirm-action-no");

            titleEl.textContent = title;
            msg.textContent = message;
            overlay.style.display = "flex";

            // Reset previous click listeners
            const newYesBtn = yesBtn.cloneNode(true);
            yesBtn.parentNode.replaceChild(newYesBtn, yesBtn);

            newYesBtn.addEventListener("click", () => {
                overlay.style.display = "none";
                if (typeof onConfirm === "function") onConfirm();
            });

            noBtn.onclick = () => {
                overlay.style.display = "none";
            };
        }
        // Function to save scroll position
        function saveScrollPosition() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        }

        // Function to restore scroll position
        function restoreScrollPosition() {
            const scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition, 10));
                sessionStorage.removeItem('scrollPosition'); // Clear the stored position
            }
        }

        // Add event listener to all forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', saveScrollPosition);
            });

            // Restore scroll position after page load
            restoreScrollPosition();
        });
        function openModal(productId) {
            document.getElementById('modal-' + productId).style.display = "flex";
        }

        function closeModal(productId) {
            document.getElementById('modal-' + productId).style.display = "none";
        }

        window.onclick = function(event) {
            const modals = document.querySelectorAll(".modal");
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        }

        function approveProduct(productId, tagsArray) {
            fetch(`/admin/approve/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    tags: tagsArray
                })
            })
            .then(response => {
                if (!response.ok) throw new Error("Approval failed");
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                document.getElementById(`product-row-${productId}`).remove();
            })
            .catch(err => console.error("Error:", err));
        }
        function confirmApproveProduct(productId, tagsArray = []) {
            confirmAction(
                "Are you sure you want to approve this product?",
                () => {
                    approveProduct(productId, tagsArray);
                },
                "Approve Product"
            );
        }
        function showRejectModal(productId) {
            console.log('wtf');
            document.getElementById('rejectModal').style.display = 'flex';
            document.getElementById('rejectProductId').value = productId;
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('rejectMessage').value = '';
        }

        document.getElementById('rejectForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const productId = document.getElementById('rejectProductId').value;
            const message = document.getElementById('rejectMessage').value;
            
            confirmAction(
                "Are you sure you want to reject this product?",
                () => {
                    // Proceed only if confirmed
                    fetch(`/admin/reject/${productId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ message })
                    })
                    .then(response => {
                        if (response.ok) {
                            // Remove product row & close modal
                            const row = document.getElementById(`product-row-${productId}`);
                            if (row) row.remove();
                            hideRejectModal();
                        }
                    })
                    .catch(error => console.error('Reject error:', error));
                },
                "Reject Product"
            );
        });

        // Suspension Modal Functions
        function showSuspensionModal(reportId) {
            document.getElementById('suspensionModal').style.display = 'flex';
            document.getElementById('suspensionReportId').value = reportId;
        }

        function hideSuspensionModal() {
            document.getElementById('suspensionModal').style.display = 'none';
            document.getElementById('suspensionDuration').value = '';
        }

        document.getElementById('suspensionForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const reportId = document.getElementById('suspensionReportId').value;
            const duration = document.getElementById('suspensionDuration').value;

            if (!duration) {
                alert('Please select a suspension duration.');
                return;
            }

            fetch(`/admin/user-reports/${reportId}/suspend`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    duration: parseInt(duration)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('user-report-row-' + reportId);
                    if (row) row.remove();
                    document.getElementById('ajaxMessagesReportedUser').innerHTML = 
                        '<div class="alert alert-success">' + data.message + '</div>';
                    hideSuspensionModal();
                } else {
                    alert(data.message || 'Failed to suspend user');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Failed to suspend user');
                });
        });

        // reports
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById('modal-' + id).style.display = 'none';
        }

    window.onclick = function(event) {
        document.querySelectorAll('.modal').forEach(function(modal) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // ajax to para i allow saka delete product
    function allowReport(reportId) {
        console.log(reportId);
        confirmAction(
            'Are you sure you want to allow this product and remove the report?',
            () => {
        $.ajax({
            url: '/admin/reports/' + reportId + '/allow',
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                $('#report-row-' + reportId).remove();
                showModal('Report removed.', "info");
            },
            error: function () {
                showModal('Something went wrong while removing the report.', "info");
            }
        });
            },
            'Allow Report'
        );
    }

    // Reported Users: Allow
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.approveUserReportBtn');
        if(!btn) return;
        var id = btn.getAttribute('data-id');
        confirmActionViaModal('Confirm Allow', 'Allow this report (no action on user) and remove it?')
            .then(function(ok){
                if(!ok) return;
                $.ajax({
                    url: '/admin/user-reports/' + id + '/allow',
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(){
                        $('#user-report-row-' + id).remove();
                        $('#ajaxMessagesReportedUser').html('<div class="alert alert-success">Report allowed and removed.</div>');
                    },
                    error: function(xhr){
                        alert((xhr.responseJSON && xhr.responseJSON.message) || 'Failed to allow report');
                    }
                });
            });
    });

    // Universal confirm modal helper
    function confirmActionViaModal(title, message){
        return new Promise(function(resolve){
            var overlay = document.getElementById('confirm-action-overlay');
            var titleEl = document.getElementById('confirm-action-title');
            var messageEl = document.getElementById('confirm-action-message');
            var yesBtn = document.getElementById('confirm-action-yes');
            var noBtn = document.getElementById('confirm-action-no');

            if(titleEl) titleEl.textContent = title || 'Confirm Action';
            if(messageEl) messageEl.textContent = message || 'Are you sure you want to proceed?';
            overlay.style.display = 'flex';

            function cleanup(){
                overlay.style.display = 'none';
                yesBtn.removeEventListener('click', onYes);
                noBtn.removeEventListener('click', onNo);
            }
            function onYes(){ cleanup(); resolve(true); }
            function onNo(){ cleanup(); resolve(false); }
            yesBtn.addEventListener('click', onYes, { once: true });
            noBtn.addEventListener('click', onNo, { once: true });
        });
    }

    // Reported Users: Ban
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.banUserReportBtn');
        if(!btn) return;
        var id = btn.getAttribute('data-id');
        confirmActionViaModal('Confirm Ban', 'Ban this user and remove the report?')
            .then(function(ok){
                if(!ok) return;
                fetch('/admin/user-reports/' + id + '/ban', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                }).then(function(r){ return r.ok ? r.json() : r.json().then(function(j){ throw j; }); })
                .then(function(){
                    var row = document.getElementById('user-report-row-' + id);
                    if(row) row.remove();
                    document.getElementById('ajaxMessagesReportedUser').innerHTML = '<div class="alert alert-success">User banned and report removed.</div>';
                }).catch(function(err){
                    alert((err && err.message) || 'Failed to ban user');
                });
            });
    });

    // Reported Users: Suspend
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.suspendUserReportBtn');
        if(!btn) return;
        var id = btn.getAttribute('data-id');
        showSuspensionModal(id);
    });

    // User Management: Unban User
    function unbanUser(userId) {
        confirmAction(
            'Are you sure you want to unban this user? They will regain access to the platform.',
            () => {
                fetch(`/admin/users/${userId}/unban`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById('banned-user-row-' + userId);
                        if (row) row.remove();
                        showModal(data.message, "success");
                    } else {
                        showModal(data.message || 'Failed to unban user', "error");
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showModal('Failed to unban user', "error");
                });
            },
            'Unban User'
        );
    }

    // User Management: Unsuspend User
    function unsuspendUser(userId) {
        confirmAction(
            'Are you sure you want to lift this user\'s suspension? They will regain access to the platform immediately.',
            () => {
                fetch(`/admin/users/${userId}/unsuspend`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById('banned-user-row-' + userId);
                        if (row) row.remove();
                        showModal(data.message, "success");
                    } else {
                        showModal(data.message || 'Failed to unsuspend user', "error");
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showModal('Failed to unsuspend user', "error");
                });
            },
            'Unsuspend User'
        );
    }

    // Search functionality for banned/suspended users
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('bannedUsersSearch');
        const table = document.getElementById('bannedUsersTable');
        const resultsCount = document.getElementById('searchResultsCount');
        
        if (searchInput && table) {
            // Initial count
            updateResultsCount();
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const rows = table.querySelectorAll('tbody tr');
                let visibleCount = 0;
                
                rows.forEach(row => {
                    const name = row.cells[0].textContent.toLowerCase();
                    const email = row.cells[1].textContent.toLowerCase();
                    const status = row.cells[2].textContent.toLowerCase();
                    const suspensionUntil = row.cells[3].textContent.toLowerCase();
                    
                    const matchesSearch = name.includes(searchTerm) || 
                                        email.includes(searchTerm) || 
                                        status.includes(searchTerm) ||
                                        suspensionUntil.includes(searchTerm);
                    
                    if (matchesSearch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                updateResultsCount(visibleCount, searchTerm);
            });
        }
        
        function updateResultsCount(visible = null, searchTerm = '') {
            const table = document.getElementById('bannedUsersTable');
            const resultsCount = document.getElementById('searchResultsCount');
            
            if (!table || !resultsCount) return;
            
            const totalRows = table.querySelectorAll('tbody tr').length;
            
            if (visible === null) {
                visible = totalRows;
            }
            
            if (searchTerm) {
                resultsCount.textContent = `Showing ${visible} of ${totalRows} users`;
                resultsCount.style.color = visible === 0 ? '#dc2626' : '#059669';
            } else {
                resultsCount.textContent = `${totalRows} total users`;
                resultsCount.style.color = '#6b7280';
            }
        }
    });

    function deleteProduct(productId, reportId) {
        console.log('went here');
        confirmAction(
            'Are you sure you want to delete this product? This cannot be undone.',
            () => {
        $.ajax({
            url: '/admin/products/' + productId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                $('#report-row-' + reportId).remove();
                showModal('Product deleted.', "info");
            },
            error: function () {
                showModal('Something went wrong while deleting the product.', "info");
            }
        });
            },
            'Delete Product'
        );
    }

    // FOR PRODUCT POLICY
    const toolbarOptions = [
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'color': [] }],
    [{ 'list': 'bullet' }],
    ['clean']
    ];

    // Hidden inputs
    const hiddenFieldAllowed = document.getElementById('descriptionAllowed');
    const hiddenFieldProhibited = document.getElementById('descriptionProhibited');
    // separating 'yung contents
    const productPolicies = @json($productPolicies);
    let productPolicyAllowedContent = productPolicies.find(p => p.type === 'allowed')?.content || '';
    let productPolicyProhibitedContent = productPolicies.find(p => p.type === 'prohibited')?.content || '';
    // Init Quill editors
    const quillAllowed = new Quill('#editor', {
    modules: { toolbar: toolbarOptions },
    theme: 'snow'
    });
    quillAllowed.clipboard.dangerouslyPasteHTML(productPolicyAllowedContent);

    const quillProhibited = new Quill('#editorProhibited', {
    modules: { toolbar: toolbarOptions },
    theme: 'snow'
    });
    quillProhibited.clipboard.dangerouslyPasteHTML(productPolicyProhibitedContent);
    // Copy Quill HTML into hidden fields before submit
    const form = document.getElementById('policyForm');
    if (form) {
    form.addEventListener('submit', function () {
        hiddenFieldAllowed.value = quillAllowed.root.innerHTML;
        hiddenFieldProhibited.value = quillProhibited.root.innerHTML;
    });
    }

    function openImageViewer(src) {
        document.getElementById('imageViewerImg').src = src;
        document.getElementById('imageViewerModal').style.display = 'flex';
    }
function closeImageViewer() {
    document.getElementById('imageViewerModal').style.display = 'none';
    document.getElementById('imageViewerImg').src = '';
}


// for Banner File Upload
    document.addEventListener('DOMContentLoaded', function () {

      // ----- Register plugins -----
      FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginImageCrop,
        FilePondPluginImageTransform,
        FilePondPluginImageEdit 
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
        imageCropAspectRatio: '1353:196',

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

      updateImageOrder();
    });

  // for voucher input handler
  const voucherAmount = document.getElementById('voucherAmount');
  const voucherPrice = document.getElementById('voucherPrice');

    voucherAmount.addEventListener('input', function (e){
        if(e.target.value < 1){
            voucherAmount.value=1;
        }
    });
    voucherPrice.addEventListener('input', function (e){
        if(e.target.value < 1){
            voucherPrice.value=1;
        }
    });

    // voucher delete handler
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.deleteVoucherBtn');
        if(!btn) return;
        e.preventDefault();
        var id = btn.getAttribute('data-id');
        if(!id) return;
        confirmAction("Delete this voucher?", () => {
    

        fetch(`/admin/voucher/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(res => res.json())
        .then(data => {
            if(data.success){
                var card = document.getElementById(`voucher-card-${id}`);
                if(card) card.remove();
            }
        }).catch(err => console.error(err));
        }, "Delete Voucher");
    });


    // for college modal
document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.editBtn');
    const modal = document.getElementById('editCollegeModal');
    const closeModal = document.getElementById('closeModalCollege');
    const editForm = document.getElementById('editCollegeForm');
    const modalError = document.getElementById('modalError');

    // Open modal & populate
    editButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const code = btn.dataset.code;
            const name = btn.dataset.name;

            document.getElementById('editCollegeId').value = id;
            document.getElementById('editCode').value = code;
            document.getElementById('editName').value = name;

            modalError.style.display = 'none';
            modal.style.display = 'flex';
        });
    });

    // Close modal
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
        modalError.style.display = 'none';
    });

    // Submit form via AJAX
editForm.addEventListener('submit', function(e){
    e.preventDefault();

    const id = document.getElementById('editCollegeId').value;
    const code = document.getElementById('editCode').value;
    const name = document.getElementById('editName').value;
    const token = document.querySelector('input[name="_token"]').value;

    fetch(`/admin/update-Student-Org/${id}`, {
        method: 'POST', 
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': token,
            'Accept':'application/json'
        },
        body: JSON.stringify({ code, name })
    })
    .then(res => {
        console.log('Response status:', res.status);
        if(!res.ok) throw res;
        return res.json();
    })
    .then(data => {
        if(data.status === 'success'){
            // Update table row dynamically
            const row = document.querySelector(`#collegeRow${id}`);
            if(row) {
                row.querySelector('.collegeCode').textContent = data.data.code;
                row.querySelector('.collegeName').textContent = data.data.name;
            }
            const editBtn = row.querySelector('.editBtn');
            if(editBtn){
                editBtn.dataset.code = data.data.code;
                editBtn.dataset.name = data.data.name;
            }
            const messagesContainer = document.getElementById('ajaxMessages');
            messagesContainer.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        
            // Auto-hide after 5 seconds
            setTimeout(() => {
                messagesContainer.innerHTML = '';
            }, 5000);
            modal.style.display = 'none';
        }
    })
    .catch(async err => {
        if(err.status === 422){
            const errorData = await err.json();
            modalError.textContent = Object.values(errorData.errors).flat().join(' ');
            modalError.style.display = 'block';
        } else {
            modalError.textContent = 'An error occurred while updating.';
            modalError.style.display = 'block';
        }
    });
});
document.querySelectorAll('.deleteForm').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        confirmAction("Are you sure you want to delete this college?", () => {
            const id = this.getAttribute('data-id');
            const token = this.querySelector('input[name="_token"]').value;

            fetch(`/admin/delete-college/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const messageBox = document.getElementById('ajaxMessages');
                if (data.status === 'success') {
                    messageBox.innerHTML = `<div class="alert alert-success">College deleted successfully!</div>`;
                    this.closest('tr').remove();
                } else {
                    messageBox.innerHTML = `<div class="alert alert-danger">${data.message || 'Error deleting college!'}</div>`;
                }
            })
            .catch(err => console.error(err));
        }, "Delete College");
    });
});


});



// for student Org
document.addEventListener('DOMContentLoaded', () => {
    const editButtonsStudOrg = document.querySelectorAll('.editBtnStudOrg');
    const modalStudOrg = document.getElementById('editStudModal');
    const closeModalStudOrg = document.getElementById('closeModalStudOrg');
    const editFormStudOrg = document.getElementById('editStudOrgForm');
    const modalErrorStudOrg = document.getElementById('modalErrorStudOrg');

    // Open modal & populate
    editButtonsStudOrg.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const code = btn.dataset.code;
            const name = btn.dataset.name;

            document.getElementById('editStudOrgId').value = id;
            document.getElementById('editStudOrgCode').value = code;
            document.getElementById('editStudOrgName').value = name;

            modalErrorStudOrg.style.display = 'none';
            modalStudOrg.style.display = 'flex';
        });
    });

    // Close modal
    closeModalStudOrg.addEventListener('click', () => {
        modalStudOrg.style.display = 'none';
        modalErrorStudOrg.style.display = 'none';
    });

    // Submit form via AJAX
editFormStudOrg.addEventListener('submit', function(e){
    e.preventDefault();

    const idStudOrgId = document.getElementById('editStudOrgId').value;
    const codeStudOrgId = document.getElementById('editStudOrgCode').value;
    const nameStudOrgId = document.getElementById('editStudOrgName').value;
    const token = document.querySelector('input[name="_token"]').value;

    fetch(`/admin/update-Student-Org/${idStudOrgId}`, { 
    method: 'POST', 
    headers: {
        'Content-Type':'application/json',
        'X-CSRF-TOKEN': token,
        'Accept':'application/json'
    },
    body: JSON.stringify({ 
        code: codeStudOrgId, 
        name: nameStudOrgId 
    })
    })
    .then(ress => {
        console.log('Response status:', ress.status);
        if(!ress.ok) throw ress;
        return ress.json();
    })
    .then(data => {
        if(data.status === 'success'){
            // Update table row dynamically
            const row = document.querySelector(`#studOrgRow${idStudOrgId}`);
            if(row) {
                row.querySelector('.studOrgCode').textContent = data.data.code;
                row.querySelector('.studOrgName').textContent = data.data.name;
            }
            const editBtnStudOrg = row.querySelector('.editBtnStudOrg');
            if(editBtnStudOrg){
                editBtnStudOrg.dataset.code = data.data.code;
                editBtnStudOrg.dataset.name = data.data.name;
            }
            const messagesContainer = document.getElementById('ajaxMessagesStudOrg');
            messagesContainer.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        
            // Auto-hide after 5 seconds
            setTimeout(() => {
                messagesContainer.innerHTML = '';
            }, 5000);
            modalStudOrg.style.display = 'none';
        }
    })
    .catch(async err => {

    if (err instanceof Response) {
        const text = await err.text();
        console.error("Error response:", text);

        if (err.status === 422) {
            const errorData = JSON.parse(text);
            modalErrorStudOrg.textContent = Object.values(errorData.errors).flat().join(' ');
            modalErrorStudOrg.style.display = 'block';
        }
    } else {
        modalErrorStudOrg.textContent = 'An unexpected error occurred.';
        modalErrorStudOrg.style.display = 'block';
    }
    });
});
document.querySelectorAll('.delFormStudOrg').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        confirmAction("Delete this Student Org?", () => {
    // fetch delete code here
 // confirm before delete

        const id = this.getAttribute('data-id');
        const token = this.querySelector('input[name="_token"]').value;

        fetch(`/admin/delete-student-org/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const messageBox = document.getElementById('ajaxMessagesStudOrg');
            if (data.status === 'success') {
                messageBox.innerHTML = `
                    <div class="alert alert-success">
                        College deleted successfully!
                    </div>
                `;
                // remove the row from the DOM
                this.closest('tr').remove();
            } else {
                messageBox.innerHTML = `
                    <div class="alert alert-danger">
                        ${data.message || 'Error deleting college!'}
                    </div>
                `;
            }
        })
        .catch(err => console.error(err));
        }, "Delete Student Org");
    });
});




document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let form = e.target;
    let formData = new FormData(form);

    fetch("{{ route('faq-category.store') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
            "Accept": "application/json",
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageBox = document.getElementById('ajaxMessagesCategory');
        if (data.success) {
            messageBox.innerHTML = `
                <div class="alert alert-success">
                    ${data.message}
                </div>
            `;
            form.reset();
            // Optionally append new category to dropdown
            let dropdown = document.getElementById('faqCategoryDropdown');
            if (dropdown) {
                let option = document.createElement('option');
                option.value = data.category.id;
                option.text = data.category.name;
                dropdown.appendChild(option);
            }
            location.reload();
        } else {
            messageBox.innerHTML = `<div style="color:red;">${data.message}</div>`;
        }
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById('messageBox').innerHTML = `<div style="color:red;">Something went wrong.</div>`;
    });
});

$(document).ready(function() {
    $('.editBtnCategory').on('click', function() {
        const categoryId = $(this).data('id');
        const categoryName = $(this).data('name');
        // Set the values in the modal
        $('#editCategoryId').val(categoryId);
        $('#editCategoryName').val(categoryName);
        // Show the modal
        $('#editCategoryModal').show();
    });
    // Handle form submission for editing
    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        const categoryId = $('#editCategoryId').val();
        const categoryName = $('#editCategoryName').val();
        $.ajax({
            url: '/faq-categories-update/' + categoryId, // Adjust the URL as needed
            type: 'POST', // Change to POST
            data: {
                _token: $('input[name="_token"]').val(),
                name: categoryName
            },
            success: function(response) {
                // Update the table row with the new name
                $('#collegeRow' + categoryId + ' .collegeName').text(categoryName);
                $('#editCategoryModal').hide(); // Hide the modal
                $('#ajaxMessagesCategoryList').html(`
                <div class="alert alert-success">
                    ${response.message ?? 'Category updated successfully!'}
                </div>
            `);
            },
            error: function(xhr) {
                // Handle errors
                $('#modalErrorCategory').text(xhr.responseJSON.message).show();
            }
        });
    });
    // Close modal
    $('#closeModalCategory').on('click', function() {
        $('#editCategoryModal').hide();
        $('#modalErrorCategory').hide().text(''); // Clear the error message
    });
    // Handle delete button click
    $('.deleteBtn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        const categoryId = $(this).data('id');
confirmAction("Are you sure you want to delete this category?", () => {
    $.ajax({
        url: '/faq-categories-delete/' + categoryId,
        type: 'DELETE',
        data: {
            _token: $('input[name="_token"]').val()
        },
        success: function(response) {
            $('#collegeRow' + categoryId).remove();
            $('#ajaxMessagesCategoryList').html(`
                <div class="alert alert-success">
                    ${response.message ?? 'Category deleted successfully!'}
                </div>
            `);
        },
        error: function(xhr) {
            $('#ajaxMessagesCategoryList').html(`
                <div class="alert alert-danger">
                    ${xhr.responseJSON.message ?? 'An error occurred while deleting the category.'}
                </div>
            `);
        }
    });
}, "Delete Category");

    });
});

    // Open modal
    document.querySelectorAll('.faqEditBtn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const modalId = btn.dataset.modal;
            const modal = document.getElementById(modalId);
            if(modal) modal.style.display = 'block';
        });
    });

    // Close modal via close button
    document.querySelectorAll('.faqCloseBtn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const modalId = btn.dataset.modal;
            const modal = document.getElementById(modalId);
            if(modal) modal.style.display = 'none';
        });
    });

    // Close modal when clicking outside modal content
    window.addEventListener('click', function(e) {
        document.querySelectorAll('.faqEditModal').forEach(function(modal) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    document.querySelectorAll('.faq-delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            
            const faqId = btn.getAttribute('data-faq-id');
            
            confirmAction("Are you sure you want to delete this FAQ?", () => {
    saveScrollPosition();
    const form = document.querySelector('.faq-delete-form-' + faqId);
    if (form) {
        // Submit directly without triggering other event listeners
        form.submit();
    }
}, "Delete FAQ");
        });
    });

});
    </script>
<!-- Unique Confirmation Modal -->
<div id="confirm-action-overlay" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(17,24,39,0.75); align-items:center; justify-content:center; 
            z-index:10000; font-family:'Inter', sans-serif;">
  <div style="background:#fff; padding:1.75rem; border-radius:.75rem; 
              max-width:380px; width:90%; text-align:center; 
              box-shadow:0 15px 35px rgba(0,0,0,0.2); animation:fadeInScale .2s ease;">
    <h2 id="confirm-action-title" style="margin:0 0 1rem; font-size:1.25rem; font-weight:700; color:#111827;">
      Confirm Action
    </h2>
    <p id="confirm-action-message" style="color:#374151; font-size:.95rem; margin-bottom:1.5rem;">
      Are you sure you want to proceed?
    </p>
    <div style="display:flex; gap:.75rem; justify-content:center;">
      <button id="confirm-action-yes" 
              style="padding:.5rem 1.25rem; border:none; border-radius:.5rem; 
                     background:#dc2626; color:#fff; font-weight:600; cursor:pointer;">
        Yes, Proceed
      </button>
      <button id="confirm-action-no" 
              style="padding:.5rem 1.25rem; border:1px solid #d1d5db; border-radius:.5rem; 
                     background:#f9fafb; color:#111827; font-weight:600; cursor:pointer;">
        Cancel
      </button>
    </div>
  </div>
</div>


<style>
@keyframes fadeInScale {
  from { opacity:0; transform:scale(.9); }
  to   { opacity:1; transform:scale(1); }
}
</style>
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".delete-form").forEach(form => {
        form.addEventListener("submit", function(e) {
            e.preventDefault(); // stop normal submit

            confirmAction("Are you sure you want to delete this image?", () => {
                form.submit(); // proceed if confirmed
            }, "Delete Image");
        });
    });
});
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("userSearch");
    const table = document.getElementById("userManagementTable");
    const resultsCount = document.getElementById("userSearchResultsCount");
    
    if (searchInput && table) {
        const rows = table.querySelectorAll("tbody tr");
        
        // Initial count
        updateUserResultsCount(rows.length, '');

        searchInput.addEventListener("input", function () {
        const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

        rows.forEach(row => {
            const firstName = row.querySelector("td:nth-child(1)")?.textContent.toLowerCase() || "";
            const lastName = row.querySelector("td:nth-child(2)")?.textContent.toLowerCase() || "";
            const fullName = (firstName + " " + lastName).trim();
            const email = row.querySelector("td:nth-child(3)")?.textContent.toLowerCase() || "";
            const role = row.querySelector("td:nth-child(4)")?.textContent.toLowerCase() || "";

                const matchesSearch = firstName.includes(query) ||
                lastName.includes(query) ||
                fullName.includes(query) || 
                email.includes(query) ||
                                    role.includes(query);

                if (matchesSearch) {
                row.style.display = "";
                    visibleCount++;
            } else {
                row.style.display = "none";
            }
        });
            
            updateUserResultsCount(visibleCount, query, rows.length);
        });
    }
    
    function updateUserResultsCount(visible, searchTerm, total = null) {
        const resultsCount = document.getElementById("userSearchResultsCount");
        if (!resultsCount) return;
        
        const table = document.getElementById("userManagementTable");
        if (!table) return;
        
        if (total === null) {
            total = table.querySelectorAll("tbody tr").length;
        }
        
        if (searchTerm) {
            resultsCount.textContent = `Showing ${visible} of ${total} users`;
            resultsCount.style.color = visible === 0 ? '#dc2626' : '#059669';
        } else {
            resultsCount.textContent = `${total} total users`;
            resultsCount.style.color = '#6b7280';
        }
    }
});
function openImageViewer(src) {
    document.getElementById('imageViewerImg').src = src;
    document.getElementById('imageViewerModal').style.display = 'flex';
}
function closeImageViewer() {
    document.getElementById('imageViewerModal').style.display = 'none';
    document.getElementById('imageViewerImg').src = '';
}

// for tags
document.addEventListener('DOMContentLoaded', () => {
    const editButtonsTag = document.querySelectorAll('.editBtnTag');
    const modalTag = document.getElementById('editTagModal');
    const closeModalTag = document.getElementById('closeModalTag');
    const editFormTag = document.getElementById('editTagForm');
    const modalErrorTag = document.getElementById('modalErrorTag');
    const ajaxMsgTags = document.getElementById('ajaxMessagesTags');

    // Open edit modal
    editButtonsTag.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            document.getElementById('editTagId').value = id;
            document.getElementById('editTagName').value = name;
            modalErrorTag.style.display = 'none';
            modalTag.style.display = 'flex';
        });
    });

    // Close edit modal
    if (closeModalTag) {
        closeModalTag.addEventListener('click', () => {
            modalTag.style.display = 'none';
            modalErrorTag.style.display = 'none';
        });
    }

    // Save edit (AJAX)
    editFormTag.addEventListener('submit', function(e){
        e.preventDefault();
        const id = document.getElementById('editTagId').value;
        const name = document.getElementById('editTagName').value.trim();
        const token = document.querySelector('input[name="_token"]').value;

        fetch(`/tags/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': token,
                'Accept':'application/json'
            },
            body: JSON.stringify({ name })
        })
        .then(res => {
            if(!res.ok) throw res;
            return res.json();
        })
        .then(data => {
            const row = document.getElementById(`tagRow${id}`);
            row.querySelector('.tagName').textContent = data.data.name;

            // also update the data-name on the edit button for future edits
            const editBtn = row.querySelector('.editBtnTag');
            if (editBtn) editBtn.dataset.name = data.data.name;

            if (ajaxMsgTags) {
                ajaxMsgTags.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                setTimeout(() => ajaxMsgTags.innerHTML = '', 5000);
            }
            modalTag.style.display = 'none';
        })
        .catch(async err => {
            if (err.status === 422) {
                const errorData = await err.json();
                modalErrorTag.textContent = Object.values(errorData.errors).flat().join(' ');
                modalErrorTag.style.display = 'block';
            } else {
                modalErrorTag.textContent = 'An error occurred while updating the tag.';
                modalErrorTag.style.display = 'block';
            }
        });
    });

    // Delete tag (AJAX + confirmAction)
    document.querySelectorAll('.deleteBtnTag').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            confirmAction("Delete this tag?", () => {
                fetch(`/tags/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (res.ok && data.status === 'success') {
                        const row = document.getElementById(`tagRow${id}`);
                        if (row) row.remove();
                        if (ajaxMsgTags) {
                            ajaxMsgTags.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                            setTimeout(() => ajaxMsgTags.innerHTML = '', 5000);
                        }
                    } else {
                        if (ajaxMsgTags) {
                            ajaxMsgTags.innerHTML = `<div class="alert alert-danger">${data.message || 'Failed to delete tag.'}</div>`;
                            setTimeout(() => ajaxMsgTags.innerHTML = '', 7000);
                        }
                    }
                })
                .catch(() => {
                    if (ajaxMsgTags) {
                        ajaxMsgTags.innerHTML = `<div class="alert alert-danger">Something went wrong while deleting the tag.</div>`;
                        setTimeout(() => ajaxMsgTags.innerHTML = '', 7000);
                    }
                });
            }, "Delete Tag");
        });
    });
});
// tag filter
document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('tagSearch');
  const createdBySelect = document.getElementById('tagCreatedByFilter');
  const resetBtn = document.getElementById('tagFilterReset');
  const tbody = document.getElementById('tagTableBody');
  const noResultsRow = document.getElementById('tagNoResultsRow');

  if (!searchInput || !createdBySelect || !tbody) return;

  const rows = Array.from(tbody.querySelectorAll('tr[id^="tagRow"]'));

  const debounce = (fn, ms=150) => {
    let t; 
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn.apply(null,args), ms); };
  };

  const applyFilters = () => {
    const q = (searchInput.value || '').toLowerCase().trim();
    const createdBy = (createdBySelect.value || '').toLowerCase().trim(); // '', 'admin', 'user'

    let visibleCount = 0;

    rows.forEach(row => {
      const nameCell = row.querySelector('.tagName');
      const name = (nameCell?.textContent || '').toLowerCase();

      const rowCreator = (row.getAttribute('data-created-by') || '').toLowerCase();

      const matchesName = q === '' || name.includes(q);
      const matchesCreator = createdBy === '' || rowCreator === createdBy;

      const show = matchesName && matchesCreator;
      row.style.display = show ? '' : 'none';
      if (show) visibleCount++;
    });

    if (noResultsRow) {
      noResultsRow.style.display = visibleCount === 0 ? '' : 'none';
    }
  };

  const applyFiltersDebounced = debounce(applyFilters, 150);

  searchInput.addEventListener('input', applyFiltersDebounced);
  createdBySelect.addEventListener('change', applyFilters);
  resetBtn.addEventListener('click', () => {
    searchInput.value = '';
    createdBySelect.value = '';
    applyFilters();
  });

  // initial pass
  applyFilters();
});

// Searchable Dropdown Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all searchable dropdowns
    document.querySelectorAll('.dropdown-search').forEach(input => {
        const imageId = input.getAttribute('data-image-id');
        const dropdown = document.getElementById(`dropdown-${imageId}`);
        
        // Set initial selected product if exists
        const selectedOption = dropdown.querySelector('.dropdown-option[data-selected="true"]');
        if (selectedOption) {
            const value = selectedOption.getAttribute('data-value');
            input.setAttribute('data-selected-id', value);
            selectedOption.classList.add('selected');
        }
        
        // Show dropdown on focus
        input.addEventListener('focus', function() {
            dropdown.style.display = 'block';
            filterOptions(imageId, '');
        });
        
        // Filter options on input
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterOptions(imageId, searchTerm);
        });
        
        // Handle option selection
        dropdown.addEventListener('click', function(e) {
            const option = e.target.closest('.dropdown-option');
            if (option) {
                // Check if option is disabled (already linked elsewhere)
                if (option.classList.contains('disabled')) {
                    showModal('This product is already linked to another featured image.', "error");
                    return;
                }
                
                const value = option.getAttribute('data-value');
                const text = option.querySelector('.product-name')?.textContent || 'Select Product';
                const price = option.querySelector('.product-price')?.textContent || '';
                
                input.value = value ? `${text} - ${price}` : '';
                input.setAttribute('data-selected-id', value);
                
                // Update selected state
                dropdown.querySelectorAll('.dropdown-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                option.classList.add('selected');
                
                dropdown.style.display = 'none';
            }
        });
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.searchable-dropdown')) {
            document.querySelectorAll('.dropdown-options').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        }
    });
});

function filterOptions(imageId, searchTerm) {
    const dropdown = document.getElementById(`dropdown-${imageId}`);
    const options = dropdown.querySelectorAll('.dropdown-option');
    
    options.forEach(option => {
        const searchData = option.getAttribute('data-search') || '';
        const productName = option.querySelector('.product-name')?.textContent.toLowerCase() || '';
        
        if (searchTerm === '' || searchData.includes(searchTerm) || productName.includes(searchTerm)) {
            option.style.display = 'flex';
        } else {
            option.style.display = 'none';
        }
    });
}

// Featured Image Product Linking Functions
function linkProduct(imageId) {
    const input = document.querySelector(`.dropdown-search[data-image-id="${imageId}"]`);
    const productId = input.getAttribute('data-selected-id');
    
    if (!productId) {
        showModal('Please select a product to link.', "error");
        return;
    }
    
    // Check if product is already linked elsewhere
    const allDropdowns = document.querySelectorAll('.dropdown-options');
    let isLinkedElsewhere = false;
    
    allDropdowns.forEach(dropdown => {
        if (dropdown.id !== `dropdown-${imageId}`) {
            const linkedOption = dropdown.querySelector(`[data-value="${productId}"][data-linked-elsewhere="true"]`);
            if (linkedOption) {
                isLinkedElsewhere = true;
            }
        }
    });
    
    if (isLinkedElsewhere) {
        showModal('This product is already linked to another featured image.', "error");
        return;
    }
    
    console.log('Linking product:', productId, 'to image:', imageId); // Debug log
    
    confirmAction(
        'Are you sure you want to link this product to the featured image?',
        () => {
            fetch(`/admin/featured-images/${imageId}/link-product`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: parseInt(productId)
                })
            })
            .then(response => {
                console.log('Response status:', response.status); // Debug log
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data); // Debug log
                if (data.success) {
                    // Update UI dynamically without page refresh
                    updateImageCardUI(imageId, data.product_name, productId);
                    updateAllDropdowns(productId, true); // Mark as linked
                    showSuccessAlert(`Featured image successfully linked to '${data.product_name}'`);
                    showModal(data.message, "success");
                } else {
                    showModal(data.message || 'Failed to link product', "error");
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showModal('Failed to link product', "error");
            });
        },
        'Link Product'
    );
}

function unlinkProduct(imageId) {
    const productLinkInfo = document.querySelector(`#image-card-${imageId} .product-link-info .linked-product`);
    const productName = productLinkInfo ? productLinkInfo.textContent.replace('🔗 ', '') : 'product';
    
    // Get the currently linked product ID
    const input = document.querySelector(`.dropdown-search[data-image-id="${imageId}"]`);
    const currentProductId = input.getAttribute('data-selected-id');
    
    confirmAction(
        'Are you sure you want to unlink the product from this featured image?',
        () => {
            fetch(`/admin/featured-images/${imageId}/unlink-product`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI dynamically without page refresh
                    updateImageCardUI(imageId, null, null);
                    if (currentProductId) {
                        updateAllDropdowns(currentProductId, false); // Mark as available
                    }
                    showSuccessAlert(`Featured image successfully unlinked from '${productName}'`);
                    showModal(data.message, "success");
                } else {
                    showModal(data.message || 'Failed to unlink product', "error");
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showModal('Failed to unlink product', "error");
            });
        },
        'Unlink Product'
    );
}

// Helper function to update image card UI
function updateImageCardUI(imageId, productName, productId) {
    const imageCard = document.querySelector(`[data-image-id="${imageId}"]`).closest('.image-card');
    const productLinkInfo = imageCard.querySelector('.product-link-info');
    const linkButton = imageCard.querySelector('.btn-link-product');
    const unlinkButton = imageCard.querySelector('.btn-unlink-product');
    const input = imageCard.querySelector('.dropdown-search');
    
    if (productName && productId) {
        // Linking a product
        productLinkInfo.innerHTML = `<span class="linked-product">🔗 ${productName}</span>`;
        linkButton.textContent = 'Update Link';
        
        if (!unlinkButton) {
            const newUnlinkButton = document.createElement('button');
            newUnlinkButton.className = 'btn-unlink-product';
            newUnlinkButton.textContent = 'Unlink';
            newUnlinkButton.onclick = () => unlinkProduct(imageId);
            linkButton.parentNode.appendChild(newUnlinkButton);
        }
        
        input.setAttribute('data-selected-id', productId);
    } else {
        // Unlinking a product
        productLinkInfo.innerHTML = '<span class="no-link">No product linked</span>';
        linkButton.textContent = 'Link Product';
        
        if (unlinkButton) {
            unlinkButton.remove();
        }
        
        input.value = '';
        input.removeAttribute('data-selected-id');
        
        // Clear selection in dropdown
        const dropdown = imageCard.querySelector('.dropdown-options');
        dropdown.querySelectorAll('.dropdown-option').forEach(opt => {
            opt.classList.remove('selected');
        });
    }
}

// Helper function to update all dropdowns to show/hide already linked products
function updateAllDropdowns(productId, isLinked) {
    document.querySelectorAll('.dropdown-options').forEach(dropdown => {
        const option = dropdown.querySelector(`[data-value="${productId}"]`);
        if (option) {
            if (isLinked) {
                option.classList.add('disabled');
                option.setAttribute('data-linked-elsewhere', 'true');
                if (!option.querySelector('.already-linked')) {
                    const alreadyLinkedSpan = document.createElement('span');
                    alreadyLinkedSpan.className = 'already-linked';
                    alreadyLinkedSpan.textContent = '(Already linked)';
                    option.appendChild(alreadyLinkedSpan);
                }
            } else {
                option.classList.remove('disabled');
                option.setAttribute('data-linked-elsewhere', 'false');
                const alreadyLinkedSpan = option.querySelector('.already-linked');
                if (alreadyLinkedSpan) {
                    alreadyLinkedSpan.remove();
                }
            }
        }
    });
}

// Helper function to show success alert
function showSuccessAlert(message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-success');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-success';
    alert.textContent = message;
    
    // Insert after the h2 in upload section
    const uploadSection = document.querySelector('.upload-section');
    const h2 = uploadSection.querySelector('h2');
    h2.insertAdjacentElement('afterend', alert);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alert.remove();
    }, 5000);
}
document.addEventListener('DOMContentLoaded', function() {
    const rejectOption = document.getElementById('messageRejection');
    const rejectMessage = document.getElementById('rejectMessage');

    rejectOption.addEventListener("change", function(){
        if(rejectOption.value == "others"){
            rejectMessage.hidden = false;
            rejectMessage.value="";
            rejectMessage.setAttribute('required', 'required');
        }
        else{
            rejectMessage.hidden = true;
            if (rejectOption.value === "poor_image_quality") {
                rejectMessage.value = "Rejected due to poor image quality. Please upload clearer and well-lit photos.";
            } 
            else if (rejectOption.value === "incorrect_category") {
                rejectMessage.value = "Rejected because the product is listed in the wrong category.";
            } 
            else if (rejectOption.value === "prohibited_item") {
                rejectMessage.value = "Rejected as it appears to be a prohibited item under our policies.";
            } 
            else if (rejectOption.value === "duplicate_listing") {
                rejectMessage.value = "Rejected due to duplicate listing. Please keep only one active listing per item.";
            } 
            else if (rejectOption.value === "pricing_error") {
                rejectMessage.value = "Rejected because of a pricing error. Please review and correct the price.";
            } 
            else if (rejectOption.value === "misleading_description") {
                rejectMessage.value = "Rejected due to misleading or inaccurate description. Please revise your listing.";
            } 
            else if (rejectOption.value === "policy_violation") {
                rejectMessage.value = "Rejected because the listing violates marketplace policies.";
            } 
            else{
                rejectMessage.value = "Item Rejected";
            }  
        }
    }); 

    document.querySelectorAll('.formAddingOfVoucher').forEach(form => {
        form.addEventListener("submit", function (e){
            e.preventDefault();
            confirmAction("Are you want to upload this voucher?", () => {
                form.submit();
            }, "Upload Voucher");
        });
    });
    document.querySelectorAll('.importUserData').forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            // Get the selected file name
            const fileInput = form.querySelector('#excel_file');
            const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : null;

            if (!fileName) {
                alert("Please select an Excel file before uploading.");
                return;
            }

            confirmAction(
                `Are you sure you want to upload "${fileName}"?`,
                () => {
                    form.submit();
                },
                "Upload Excel File"
            );
        });
    });
    document.querySelectorAll('.suspensionForm').forEach(form => {
        form.addEventListener("submit", function (e){
            e.preventDefault();
            confirmAction("Are you want to suspend this user?", () => {
                form.submit();
            }, "Suspend User");
        });
    });
});
</script>
@endsection

