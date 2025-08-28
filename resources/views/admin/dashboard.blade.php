<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Page</title>
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
    @vite('resources/css/admin-younder.css')
</head>
<body>
    <!-- nav bar -->

    <div class="navBar">
      <div class="navBarLeft"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>
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
          <div class="dropdown-container">
    <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
    <div class="profile-dropdown" id="profileDropdown" style="display: none;">
      <ul>
        <li><a href="{{ route('student.profile') }}">My Profile</a></li>
        <li><a href=" ">Wishlist</a></li>
        <li><a href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>
      </div>
    </div>

    <!-- nav bar -->
<div class="container">

    <div class="upload-container">

        <!-- Featured Image Upload -->
        <section class="upload-section">
            <h2>Featured Images</h2>
            @if (session('image_success'))
                <div class="alert alert-success">{{ session('image_success') }}</div>
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

                            {{-- Delete Button --}}
                            <form action="{{ route('admin.featured.delete', $image->id) }}" 
                                method="POST" 
                                onsubmit="return confirm('Delete this image?')" 
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

        <!-- Excel Upload -->
        <section class="upload-section">
            <h2>Upload User Data (Excel)</h2>
            @if (session('excel_success'))
                <div class="alert alert-success">{{ session('excel_success') }}</div>
            @endif
            @if (session('excel_error'))
                <div class="alert alert-danger">{{ session('excel_error') }}</div>
            @endif
            <form action="{{ route('upload.users') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Choose Excel File</label>
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls">
                </div>
                <button type="submit" class="btn">Upload Excel</button>
            </form>
        </section>

        <!-- Product Policy -->
        <section class="productPolicy">
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
                    <input type="text" id="code" name="code" placeholder="Ex. CCST" required>
                </div>
                <div>
                    <label for="name">College Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter college name" required>
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
                               <button type="submit" class="deleteBtn" onclick="return confirm('Are you sure?')">Delete</button>
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
                    <input type="text" class="code" id="code" name="code" placeholder="Short Name" required>
                </div>
                <div>
                    <label for="name">Student Organization Name</label>
                    <input type="text" class="name" id="name" name="name" placeholder="Enter Student Organization Name" required>
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
                                <button type="submit" class="deleteBtnStudOrg" onclick="return confirm('Are you sure?')">Delete</button>
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
        <!-- Product Approval -->
        <section class="approval-product">
            <h2>Unapproved Products</h2>
            <table>
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
                                <button type="button" onclick="approveProduct({{ $product->product_id }})">Approve</button>
                                <button type="button" onclick="showRejectModal({{ $product->product_id }})">Reject</button>
                            </td>
                        </tr>
                        <div id="modal-{{ $product->product_id }}" class="modal" style="display:none; align-items:center; justify-content:center;">
                            <div class="modal-content" style="position:relative;">
                                <span class="close" onclick="closeModal({{ $product->product_id }})">&times;</span>
                                <h2 style="margin-bottom:0.5rem;">{{ $product->name }}</h2>
                                <div style="color:#771217; font-weight:600; margin-bottom:1rem;">₱ {{ $product->price }}</div>
                                <p style="margin-bottom:1.5rem;">{!! $product->description !!}</p>
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

        <!-- Voucher Management -->
        <section class="addingOfVoucher">
            @if (session('credit_success'))
                <div class="alert alert-success">{{ session('credit_success') }}</div>
            @endif
            <form class="creditPercentageForm" method="POST" action="{{route('admin.credit')}}">
                @csrf
                <h2>Credit Percentage</h2>
                <span>Current Exchange Rate: {{ $creditPercentage->percentage }}%</span>
                <div class="example">
                    <small>
                        Example: 100 pesos = 
                        {{ 100 * ($creditPercentage->percentage / 100) }} credits
                    </small>
                </div>
                <div class="creditLabel">
                    <label for="percentage">Credit Percentage: </label>
                    <input type="number" step="0.01" name="percentage" value="{{ $creditPercentage->percentage }}">
                </div>
                <button type="submit">Save</button>
            </form>
            @if (session('voucher_success'))
                <div class="alert alert-success">{{ session('voucher_success') }}</div>
            @endif
            <form class="formAddingOfVoucher" action="{{route('admin.voucher')}}" method="POST">
            @csrf
                <h2>Add a Voucher</h2>
                <div class="formAddingOfVoucher-input">
                    <label for="voucherAmount">Voucher Amount: </label>
                    <input type="number" name="voucherAmount" id="voucherAmount" min="1">
                    <label for="voucherPrice">Voucher Price: </label>
                    <input type="number" name="voucherPrice" id="voucherPrice" min="1">
                </div>
                <div class="formAddingOfVoucher-buttons">
                    <button class="btn">Submit</button>
                    <button type="reset" class="voucherResetBtn">Cancel</button>
                </div>
            </form>
            <div class="voucherList">
                <h2>Current Redeemable Vouchers</h2>
                @foreach($voucherList as $voucher)
                <div class="voucherCard">
                    <form class="voucherListForm"action="">
                    <div class="voucherInfo">
                        <h3>P {{$voucher->amount}}</h3>
                        <p class="voucherCost">Cost: {{$voucher->price}} Credits</p>
                    </div>
                    <div class="voucherDelete">
                        <button class="btn">Delete</button>
                    </div>
                    </form>
                </div>
                @endforeach
            </div>
        </section>

        <!-- User Role Management -->
        <section class="user-role-management">
            <h2>User Role Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr id="user-row-{{ $user->id }}">
                            <td>{{ $user->name }}</td>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- Reports -->
        <section class="report-show">
            <h2>Reported Products</h2>
            <table>
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
                                <a href="javascript:void(0);" onclick="openModal({{ $report->report_id }})">View</a>
                                <button onclick="allowReport({{ $report->report_id }})">Allow</button>
                                <button onclick="deleteProduct({{ $report->report_id_item }}, {{ $report->report_id }})">Delete</button>
                            </td>
                        </tr>
                        <div id="modal-{{ $report->report_id }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal({{ $report->report_id }})">&times;</span>
                                <h2>{{ $report->product_name }}</h2>
                                <p>{{ $report->description }}</p>
                                <div style="max-height: 200px; overflow-y: auto; word-wrap: break-word; white-space: pre-wrap; border: 1px solid #eee; border-radius: 8px; margin-bottom: 1rem;">
                                    {{ $report->message }}
                                </div>
                                <div class="image-gallery">
                                    @php
                                        $images = explode(',', $report->image_path);
                                    @endphp
                                    @foreach ($images as $img)
                                        <img src="{{ asset('images/' . trim($img)) }}" alt="Product Image">
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
                <label for="message">Reason:</label>
                <textarea name="message" id="rejectMessage" required></textarea>
                <div style="margin-top:1rem;">
                    <button type="submit" class="btn">Send Rejection</button>
                    <button type="button" onclick="hideRejectModal()">Cancel</button>
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

            fetch(`/admin/reject/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message
                    })
                })
                .then(response => {
                    if (response.ok) {
                        document.getElementById(`product-row-${productId}`).remove();
                        hideRejectModal();
                    }
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
        if (!confirm('Are you sure you want to allow this product and remove the report?')) return;

        $.ajax({
            url: '/admin/reports/' + reportId + '/allow',
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                $('#report-row-' + reportId).remove();
                alert('Report removed.');
            },
            error: function () {
                alert('Something went wrong while removing the report.');
            }
        });
    }

    function deleteProduct(productId, reportId) {
        console.log('went here');
        if (!confirm('Are you sure you want to delete this product? This cannot be undone.')) return;

        $.ajax({
            url: '/admin/products/' + productId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                $('#report-row-' + reportId).remove();
                alert('Product deleted.');
            },
            error: function () {
                alert('Something went wrong while deleting the product.');
            }
        });
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


    // FOR NOTIFS
     document.addEventListener("DOMContentLoaded", function () {
    const notifBtn = document.querySelector(".notificationBtn");
    const notifDropdown = document.getElementById("notificationDropdown");
    const profileBtn = document.querySelector(".profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");
    const closeNotif = document.querySelector(".closeButton");
    const wishlistButtons = document.querySelectorAll('.wishlistBtn');
    const cartButton = document.querySelectorAll('.cartBtn');
    let category = 'featured';

    document.querySelectorAll(".mainFilterButtons").forEach(button => {
    button.addEventListener("click", () => {
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
      notifDropdown.style.display = "none"; 
    });
    if(closeNotif){
    closeNotif.addEventListener("click", function () {
      notifDropdown.style.display = "none";
    });
    }
    // close dropdowns if clicked outside
    window.addEventListener("click", function (e) {
      if (!e.target.closest(".dropdown-container")) {
        notifDropdown.style.display = "none";
        profileDropdown.style.display = "none";
      }
    });
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

        if (!confirm('Are you sure?')) return; // confirm before delete

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

        if (!confirm('Are you sure?')) return; // confirm before delete

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
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: '/faq-categories-delete/' + categoryId, // Adjust the URL as needed
                type: 'DELETE', // Use DELETE method
                data: {
                    _token: $('input[name="_token"]').val()
                },
                success: function(response) {
                    // Remove the category row from the table
                    $('#collegeRow' + categoryId).remove();
                    $('#ajaxMessagesCategoryList').html(`
                    <div class="alert alert-success">
                        ${response.message ?? 'Category deleted successfully!'}
                    </div>
                `);
                },
                error: function(xhr) {
                    // Handle errors
                    $('#ajaxMessagesCategoryList').html(`
                    <div class="alert alert-danger">
                        ${xhr.responseJSON.message ?? 'An error occurred while deleting the category.'}
                    </div>
                `);
                }
            });
        }
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
            
            if (confirm('Are you sure you want to delete this FAQ?')) {
                saveScrollPosition();
                const form = document.querySelector('.faq-delete-form-' + faqId);
                if (form) {
                    // Submit directly without triggering other event listeners
                    form.submit();
                }
            }
        });
    });

});
    </script>
</body>
</html>

