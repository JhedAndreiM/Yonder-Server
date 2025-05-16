@extends('Front_layouts.org')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    @vite('resources/css/admin-younder.css')
    <style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
        .mySlides {
            display:none;
        }
    </style>
@endsection
@section('maincontent')
    
        <div class="section-one">
            <div class="form-holder">
                <form action="{{ route('admin.featured.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="image">Upload Featured Image</label><br>
                    <input type="file" name="image" required multiple><br><br>
                    <button type="submit">Upload</button>
                </form>
                
            </div>
            
            <div class="right-middle">
                
            <button class="w3-button w3-black left-btn slider-btn" onclick="plusDivs(-1)">&#10094;</button>
            <button class="w3-button w3-black right-btn slider-btn" onclick="plusDivs(1)">&#10095;</button>
            <div class="w3-content w3-display-container image-slider-wrapper">
                @foreach ($featuredImages as $image)
                    <img class="mySlides" src="{{ asset('Featured/' . $image->image_path) }}" alt="Featured" style="width: 100%; margin-bottom: 10px;">
                @endforeach
                
            </div>
            
            </div>
           
        </div>
        <div class="section-two">
            <h2>Unapproved Products</h2>

            @if(session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif
            
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Approve</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr id="product-row-{{ $product->product_id }}">
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            <button type="button" onclick="approveProduct({{ $product->product_id }})">Approve</button>
                            <button type="button" onclick="showRejectModal({{ $product->product_id }})">Reject</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
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
        var slideIndex = 1;
        showDivs(slideIndex);
        
        function plusDivs(n) {
          showDivs(slideIndex += n);
        }
        
        function showDivs(n) {
          var i;
          var x = document.getElementsByClassName("mySlides");
          if (n > x.length) {slideIndex = 1}
          if (n < 1) {slideIndex = x.length}
          for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";  
          }
          x[slideIndex-1].style.display = "block";  
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
            body: JSON.stringify({ message })
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