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
                    <input type="file" name="image" id="image" required multiple accept="image/*">
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

                @if (session('success'))
                    <p style="color: green;">{{ session('success') }}</p>
                @endif

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
    </script>
@endsection
