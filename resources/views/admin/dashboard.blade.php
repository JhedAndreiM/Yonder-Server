@extends('Front_layouts.org')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - File Upload</title>
    @vite('resources/css/admin-younder.css')
    <style>
        body {
            background-image: url("{{ asset('img/background.svg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top center;
        }
    </style>
@endsection

@section('maincontent')
<div class="container">
    <div class="upload-container">
        <!-- Featured Image Upload Section -->
        <div class="upload-section">
            <h2>Upload Featured Images</h2>
            @if (session('image_success'))
                <div class="alert alert-success">
                    {{ session('image_success') }}
                </div>
            @endif

            <form action="{{ route('admin.featured.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="image">Select Images</label>
                    <input type="file" name="image[]" id="image" required multiple accept="image/*">
                </div>
                <button type="submit" class="btn">Upload Images</button>
            </form>

            <!-- Image Preview Section -->
            <div class="preview-section">
                <h3>Current Featured Images</h3>
                <div class="image-preview">
                    @foreach ($featuredImages as $image)
                        <img src="{{ asset('Featured/' . $image->image_path) }}" alt="Featured"
                            style="max-width: 200px; margin: 10px;">
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Excel Upload Section -->
        <div class="upload-section">
            <h2>Upload User Data (Excel)</h2>
            
            @if (session('excel_success'))
                <div class="alert alert-success">
                    {{ session('excel_success') }}
                </div>
            @endif
            @if (session('excel_error'))
                <div class="alert alert-danger">
                    {{ session('excel_error') }}
                </div>
            @endif

            <form action="{{ route('upload.users') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Choose Excel File</label>
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls">
                </div>
                <button type="submit" class="btn">Upload Excel</button>
            </form>
        </div>

        <div class="approval-product">
            <div class="section-two">
                <h2>Unapproved Products</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>View Details</th>
                            <th>Approve</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="perRow"id="product-row-{{ $product->product_id }}">
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td><a href="javascript:void(0);" onclick="openModal({{ $product->product_id }})">view
                                        details</a></td>
                                <td>
                                    <button type="button"
                                        onclick="approveProduct({{ $product->product_id }})">Approve</button>
                                    <button type="button"
                                        onclick="showRejectModal({{ $product->product_id }})">Reject</button>
                                </td>
                            </tr>

                            <div id="modal-{{ $product->product_id }}" class="modal">
                                <div class="modal-content">
                                    <span class="close" onclick="closeModal({{ $product->product_id }})">&times;</span>
                                    <h2>{{ $product->name }}</h2>
                                    <p>{{ $product->description }}</p>

                                    <div class="image-gallery">
                                        @php
                                            $images = explode(',', $product->image_path);
                                        @endphp
                                        @if ($images)
                                            @foreach ($images as $img)
                                                <img src="{{ asset('images/' . $img) }}" alt="Product Image">
                                            @endforeach
                                        @else
                                            <img src="{{ asset('images/' . $product->image_path) }}" alt="Product Image">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <!-- USER ROLE MANAGEMENT -->
        <div class="user-role-management">
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
                            <form action="{{ route('admin.changeRole') }}" method="POST">
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
</div>
<!-- REPORTS -->
<div class="report-show">
    <div class="section-two">
        <h2>Reported Products</h2>

        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Product Name</th>
                    <th>Reported By</th>
                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr class="perRow" id="report-row-{{ $report->report_id }}">
                        <td>{{ $report->report_id }}</td>
                        <td>{{ $report->product_name }}</td>
                        <td>{{ $report->reporter_name }} {{ $report->reporter_last_name }}</td>
                        <td>
                            <a href="javascript:void(0);" onclick="openModal({{ $report->report_id }})">View Details</a>
                            <button onclick="allowReport({{ $report->report_id }})">Allow</button>
                            <button onclick="deleteProduct({{ $report->report_id_item }}, {{ $report->report_id }})">Delete</button>
                            <input type="text" value="{{ $report->report_id_item }},{{ $report->report_id }}">
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div id="modal-{{ $report->report_id }}" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal({{ $report->report_id }})">&times;</span>
                        <h2>{{ $report->product_name }}</h2>
                        <p>{{ $report->description }}</p>
                        <div style="
                            max-height: 200px;
                            overflow-y: auto;
                            word-wrap: break-word;
                            white-space: pre-wrap;
                            border: 1px solid #ccc;
                            border-radius: 8px;">
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
    </div>
</div>
    </div>


    <!-- Reject Modal -->
    <div id="rejectModal" class="rejectModal">
        <div class="modal-contents">
            <h3>Reject Product</h3>
            
            <form id="rejectForm">
                @csrf
                <input type="hidden" name="product_id" id="rejectProductId">
                <label for="message">Reason:</label><br>
                <textarea name="message" id="rejectMessage" rows="4" cols="50" required></textarea><br><br>
                <button type="submit">Send Rejection</button>
                <button type="button" onclick="hideRejectModal()">Cancel</button>
            </form>
        </div>
    </div>
</div>
    <script>
        function openModal(productId) {
            document.getElementById('modal-' + productId).style.display = "flex";
        }

        function closeModal(productId) {
            document.getElementById('modal-' + productId).style.display = "none";
        }

        // Optional: Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll(".modal");
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        }

        function approveProduct(productId) {
            fetch(`/admin/approve/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => {
                    if (response.ok) {
                        document.getElementById(`product-row-${productId}`).remove();
                    }
                });
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
        document.getElementById('modal-' + id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).style.display = 'none';
    }

    // Close modal on outside click (optional)
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
    </script>
@endsection
