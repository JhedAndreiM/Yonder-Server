
@extends('Front_layouts.org')

@section('head')
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    
    @vite('resources/css/accountPage.css')
@endsection

@section('maincontent')
<div class="container">
    
    <div class="top">
        <h1 style="color:#ae0505;">My Account</h1>
    </div>
    <div class="bottom">
        <div class="image-placeholder">
            <img src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="">
        </div>
        @if (session('message'))
        <div class="alert alert-danger">
        {{ session('message') }}
        </div>
        @endif
        <div class="image-button">
            <form action="{{route('update.avatar')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="avatar" id="avatar" accept="image/*" hidden>
                <input type="hidden" name="cropped_avatar" id="cropped_avatar">
                <button type="button" onclick="document.getElementById('avatar').click()">Choose Image</button>
                <button type="submit" id="save-button" style="display: none;">Save</button>
                <button type="button" id="cancel-button-image" style="display: none;">Cancel</button>
            </form>
            <form action="{{ route('delete.avatar') }}" method="POST" id="delete-form">
                @csrf
                <button id="delete-button"type="submit">Delete</button>
            </form>
        </div>
        <form action="{{route('update.userInfo')}}" method="POST">
        @csrf

        <div class="profile-name">
            <div class="div-firstname">
                <label for="firstname">First Name</label>
                <input type="text" name="firstname" id="" value="{{ Auth::user()->name }}" placeholder="First Name">
            </div>
            <div class="div-middlename">
                <label for="middlename">Middle Name</label>
                <input type="text" name="middlename" id="" value="{{ Auth::user()->middle_name }}" placeholder="Middle Name">
            </div>
            <div class="div-lastname">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" id="" value="{{ Auth::user()->last_name }}" placeholder="Last Name">

            </div>
        </div>
        <div class="profile-info">
            <div class="div-contactNum">
                <label for="phonenumber">Phone Number</label>
                <input type="text" name="phonenumber" id="" value="{{ Auth::user()->phone_number }}" placeholder="(e.g. 09123456789)">
            </div>
            <div class="div-gender">
                <label for="gender">Gender:</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="" disabled {{ Auth::user()->gender ? '' : 'selected' }}>-- Select Gender --</option>
                    <option value="Male" {{ old('gender', Auth::user()->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', Auth::user()->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>
        <div class="button-group">
            @if(session('error'))
                <div class="alert alert-danger" style="color: red;">
                    {{ session('error') }}
                </div>
            @elseif (session('success'))
                <div class="alert alert-success" style="color: green;">
                    {{ session('success') }}
                </div>
            @endif
            <button type="submit">Save Changes</button>
        </div>
        
        </form>
    </div>
</div>
<!-- Modal for Cropper -->
<div id="cropperModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div id="cropper-container">
            <img id="image-to-crop" src="" alt="Image to crop">
        </div>
        <div class="cropper-buttons">
            <button class="crop-button-crop" type="button" id="crop-button">Crop</button>
            <button class="crop-button-cancel" type="button" id="cancel-button">Cancel</button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    let cropper;
    const avatar = document.getElementById('avatar');
    const cropperModal = document.getElementById('cropperModal');
    const imageToCrop = document.getElementById('image-to-crop');
    const cropButton = document.getElementById('crop-button');
    const cancelButton = document.getElementById('cancel-button');
    const saveButton = document.getElementById('save-button');
    const cancelImageButton = document.getElementById('cancel-button-image');
    const deleteImageButton = document.getElementById('delete-button');
    const profileImage = document.querySelector('.image-placeholder img');

    avatar.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                cropperModal.style.display = 'flex';
                
                // Initialize cropper with modified options
                if (cropper) {
                    cropper.destroy();
                }
                
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    cropBoxResizable: true,    // Allow resizing the crop box
                    cropBoxMovable: true,      // Allow moving the crop box
                    movable: true,             // Allow moving the image
                    zoomable: true,            // Allow zooming the image
                    zoomOnTouch: true,
                    zoomOnWheel: true,
                    rotatable: true,           // Allow rotation if needed
                    guides: true,              // Show grid lines
                    center: true,              // Show center indicator
                    highlight: true,           // Show white modal above the crop box
                    background: true,          // Show grid background
                    responsive: true,
                    restore: true,
                    minContainerWidth: 300,
                    minContainerHeight: 300
                });
            };
            reader.readAsDataURL(file);
        }
    });

    cropButton.addEventListener('click', function() {
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        const croppedImage = croppedCanvas.toDataURL('image/jpeg', 0.9);
        profileImage.src = croppedImage;
        document.getElementById('cropped_avatar').value = croppedImage;
        cropperModal.style.display = 'none';
        saveButton.style.display = 'block';
        cancelImageButton.style.display = 'block';
        deleteImageButton.style.display = 'none';
        cropper.destroy();
    });

    cancelButton.addEventListener('click', function() {
        cropperModal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
        }
        avatar.value = '';
    });

    cancelImageButton.addEventListener('click', function(){
        profileImage.src = "{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}";
        document.getElementById('cropped_avatar').value = '';
        saveButton.style.display = 'none';
        cancelImageButton.style.display = 'none';
        deleteImageButton.style.display = 'block';
    })
});


</script>