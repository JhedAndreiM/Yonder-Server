<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile Settings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    @vite('resources/css/profileSettings.css')
  </head>
  <body>
    <!-- nav bar -->

    <div class="navBar">
      <div class="navBarLeft" id="logoClick"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>

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
        <a href="{{ route('show.wishlist') }}">
            <img class="hover" src="{{ asset('img/wishlist.png') }}" alt="Wishlist"/>
        </a>
        <a href="{{ route('show.cart') }}">
            <img class="hover" src="{{ asset('img/cart.png') }}" alt="Cart"/>
        </a>
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

    <div class="mainContainer">
<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
    @csrf
    @method('PUT')
    <img src="{{ asset('img/bannerProfile.png') }}" alt="" class="profileBanner" />
    
    <!-- Hidden inputs for cropped images -->
    <input type="hidden" name="cropped_avatar" id="croppedAvatar">
    <input type="hidden" name="cropped_qr" id="croppedQr">
    
    <div class="topPart">
        <div class="leftPart">
            <div class="avatarAndName">
                <div class="Avatar">
                    <img class="avatar" id="currentAvatar" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
                    <div class="texts avatar-controls">
                        <h3 id="changeAvatar">Change</h3>
                        <h3 id="deleteAvatar">Delete</h3>
                        <h3 id="cancelAvatarChanges" class="cancel-btn" style="display: none;">Cancel</h3>
                    </div>
                </div>
                <div class="name">
                    <h2>{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h2>
                    <p>Student</p>
                </div>
            </div>
            <div class="rightPart">
                <img class="qr" id="currentQr" src="{{ asset('storage/users-qr/' . Auth::user()->qr_image) }}" alt="" />
                <div class="texts qr-controls">
                    <h3 id="uploadQr">Upload</h3>
                    <h3 id="deleteQr">Delete</h3>
                    <h3 id="cancelQrChanges" class="cancel-btn" style="display: none;">Cancel</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="details">
        <div class="formContainer">
            <div class="formRow">
                <div class="formGroup">
                    <label for="firstname">First Name</label>
                    <input type="text" name="name" id="firstname" placeholder="{{ Auth::user()->name }}" value="{{ Auth::user()->name }}" disabled/>
                </div>

                <div class="formGroup">
                    <label for="lastname">Last Name</label>
                    <input type="text" name="last_name" id="lastname" placeholder="{{ Auth::user()->last_name }}" value="{{ Auth::user()->last_name }}" disabled/>
                </div>

                <div class="formGroup smallInput">
                    <label for="middleinitial">Middle Name.</label>
                    <input type="text" name="middle_name" id="middleinitial" placeholder="{{ Auth::user()->middle_name }}" value="{{ Auth::user()->middle_name }}" disabled/>
                </div>
            </div>

            <div class="formRow">
                <div class="formGroup mediumInput">
                    <label for="contact">Contact Number</label>
                    @if(session('otp_required'))
                    <input type="text" name="phone_number" id="contact" placeholder="{{ Auth::user()->phone_number }}" value="{{ session('pending_phoneNumber') }}"/>
                    @else
                    <input type="text" name="phone_number" id="contact" placeholder="{{ Auth::user()->phone_number }}" value="{{ Auth::user()->phone_number }}"/>
                    @endif
                </div>

                <div class="formGroup shortSelect">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="" disabled {{ Auth::user()->gender ? '' : 'selected' }}>-- Select Gender --</option>
                        <option value="Male" {{ old('gender', Auth::user()->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', Auth::user()->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="formGroup alignRight">
                    <button type="submit" class="submitBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden file inputs -->
    <input type="file" id="avatarInput" accept="image/*" style="display: none;">
    <input type="file" id="qrInput" accept="image/*" style="display: none;">
</form>

<!-- Cropper Modal -->
<div id="cropperModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Crop Your Image</h3>
            <span class="close" id="closeModal">×</span>
        </div>
        <div class="modal-body">
            <div class="cropper-container">
                <img id="cropperImage" style="max-width: 100%;">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="cancelCrop" class="btn btn-secondary">Cancel</button>
            <button type="button" id="applyCrop" class="btn btn-primary">Apply Crop</button>
        </div>
    </div>
</div>
    </div>
    <!-- end of main -->
    <div class="lowerRight">
      <div class="changePass">
        <p>Change password</p>
        <img src="imgs/lock.svg" alt="" />
      </div>
      <div class="signOut">
        <p>Change password</p>
        <img src="imgs/signout.svg" alt="" />
      </div>
    </div>
    @if(session('otp_required'))
<!-- OTP Modal -->
<div id="otpModal" class="otp-modal">
    <div class="otp-modal-content">
        <span class="otp-modal-close" id="otpModalClose">&times;</span>

        <h2 class="otp-modal-title">Confirm OTP</h2>

        <form method="POST" action="{{route('profile.phoneNumber')}}" class="otp-modal-form">
            @csrf
            <label class="otp-modal-label">Enter OTP sent to your number</label>

            <div class="otp-input-group">
                <input type="text" maxlength="1" class="otp-box" name="otp[]" required>
                <input type="text" maxlength="1" class="otp-box" name="otp[]" required>
                <input type="text" maxlength="1" class="otp-box" name="otp[]" required>
                <input type="text" maxlength="1" class="otp-box" name="otp[]" required>
                <input type="text" maxlength="1" class="otp-box" name="otp[]" required>
                <input type="text" maxlength="1" class="otp-box" name="otp[]" required>
                <input type="hidden" name="otp_combined" id="otp_combined">
            </div>
            @if($errors->has('otp')) 
              <p class="otp-error">{{ $errors->first('otp') }}</p> 
            @endif
            <button type="submit" class="otp-modal-button">Confirm</button>
        </form>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const otpModal = document.getElementById("otpModal");
    const otpModalClose = document.getElementById("otpModalClose");
    const inputs = document.querySelectorAll(".otp-box");
    const otpCombined = document.getElementById("otp_combined");

    otpModal.style.display = "flex";

    otpModalClose.onclick = function () {
        otpModal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target === otpModal) {
            otpModal.style.display = "none";
        }
    };

    function updateOtpCombined() {
        let combined = '';
        inputs.forEach(input => {
            combined += input.value.toUpperCase();
        });
        otpCombined.value = combined;
    }

    inputs.forEach((input, index) => {
        input.addEventListener("input", () => {
            input.value = input.value.toUpperCase();
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateOtpCombined();
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
            setTimeout(updateOtpCombined, 0);
        });
    });

    updateOtpCombined();
});
</script>
@endif

@if(session('success'))
<div id="successBar" class="success-bar">
    {{session('success')}}
</div>
<script>
    const bar = document.getElementById('successBar');
    bar.classList.add('show');

    // Hide after 3 seconds
    setTimeout(() => {
        bar.classList.remove('show');
    }, 3000);
</script>
@endif
    <script>
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
        // Remove 'current' from all filter buttons
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
      notifDropdown.style.display = "none"; // close notifications if open
    });
    if(closeNotif){
    closeNotif.addEventListener("click", function () {
      notifDropdown.style.display = "none";
    });
    }
    document.getElementById('logoClick').addEventListener('click', function() {
    window.location.href = "{{ route('student.dashboard') }}";
    });
    // Optional: Close dropdowns if clicked outside
    window.addEventListener("click", function (e) {
      if (!e.target.closest(".dropdown-container")) {
        notifDropdown.style.display = "none";
        profileDropdown.style.display = "none";
      }
    });

        // wishlist button
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function () {
                window.location.href = "{{ route('show.wishlist') }}";
            });
        });
         // cart button
        cartButton.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('show.cart')}}";
                
            })
        });

  });

document.addEventListener('DOMContentLoaded', function() {
    // Cropper instance
    let cropper = null;
    let currentImageType = null; // 'avatar' or 'qr'
    
    // Get all elements
    const elements = {
        // Inputs
        avatarInput: document.getElementById('avatarInput'),
        qrInput: document.getElementById('qrInput'),
        croppedAvatarInput: document.getElementById('croppedAvatar'),
        croppedQrInput: document.getElementById('croppedQr'),
        
        // Images
        currentAvatar: document.getElementById('currentAvatar'),
        currentQr: document.getElementById('currentQr'),
        cropperImage: document.getElementById('cropperImage'),
        
        // Modal
        cropperModal: document.getElementById('cropperModal'),
        modalTitle: document.getElementById('modalTitle'),
        closeModal: document.getElementById('closeModal'),
        cancelCrop: document.getElementById('cancelCrop'),
        applyCrop: document.getElementById('applyCrop'),
        
        // Controls
        changeAvatar: document.getElementById('changeAvatar'),
        deleteAvatar: document.getElementById('deleteAvatar'),
        cancelAvatarChanges: document.getElementById('cancelAvatarChanges'),
        uploadQr: document.getElementById('uploadQr'),
        deleteQr: document.getElementById('deleteQr'),
        cancelQrChanges: document.getElementById('cancelQrChanges'),
        
        // Form
        profileForm: document.getElementById('profileForm')
    };
    
    // Store original image sources
    const originalSources = {
        avatar: elements.currentAvatar.src,
        qr: elements.currentQr.src
    };
    
    // State management
    const state = {
        hasAvatarChanges: false,
        hasQrChanges: false
    };
    
    // Initialize event listeners
    initEventListeners();
    
    function initEventListeners() {
        // Avatar controls
        elements.changeAvatar.addEventListener('click', () => triggerFileInput('avatar'));
        elements.deleteAvatar.addEventListener('click', () => deleteImage('avatar'));
        elements.cancelAvatarChanges.addEventListener('click', () => cancelChanges('avatar'));
        
        // QR controls
        elements.uploadQr.addEventListener('click', () => triggerFileInput('qr'));
        elements.deleteQr.addEventListener('click', () => deleteImage('qr'));
        elements.cancelQrChanges.addEventListener('click', () => cancelChanges('qr'));
        
        // File inputs
        elements.avatarInput.addEventListener('change', (e) => handleFileSelection(e, 'avatar'));
        elements.qrInput.addEventListener('change', (e) => handleFileSelection(e, 'qr'));
        
        // Modal controls
        elements.closeModal.addEventListener('click', hideModal);
        elements.cancelCrop.addEventListener('click', hideModal);
        elements.applyCrop.addEventListener('click', applyCrop);
        
        // Close modal on outside click
        elements.cropperModal.addEventListener('click', (e) => {
            if (e.target === elements.cropperModal) {
                hideModal();
            }
        });
        
        // Form submission
        elements.profileForm.addEventListener('submit', () => {
            state.hasAvatarChanges = false;
            state.hasQrChanges = false;
        });
        
        // Warn about unsaved changes
        window.addEventListener('beforeunload', (e) => {
            if (state.hasAvatarChanges || state.hasQrChanges) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                return e.returnValue;
            }
        });
    }
    
    function triggerFileInput(type) {
        currentImageType = type;
        if (type === 'avatar') {
            elements.avatarInput.click();
        } else {
            elements.qrInput.click();
        }
    }
    
    function handleFileSelection(event, type) {
        const file = event.target.files[0];
        if (!file) return;
        
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            elements.cropperImage.src = e.target.result;
            currentImageType = type;
            updateModalTitle(type);
            showModal();
            initCropper(type);
        };
        reader.readAsDataURL(file);
    }
    
    function updateModalTitle(type) {
        elements.modalTitle.textContent = type === 'avatar' ? 'Crop Your Avatar' : 'Crop Your QR Code';
    }
    
    function showModal() {
        elements.cropperModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function hideModal() {
        elements.cropperModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Reset file inputs
        elements.avatarInput.value = '';
        elements.qrInput.value = '';
        currentImageType = null;
    }
    
    function initCropper(type) {
        if (cropper) {
            cropper.destroy();
        }
        
        cropper = new Cropper(elements.cropperImage, {
            aspectRatio: 1, // Square for both avatar and QR
            viewMode: 2,
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            minCropBoxWidth: 100,
            minCropBoxHeight: 100
        });
    }
    
    function applyCrop() {
        if (!cropper || !currentImageType) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        canvas.toBlob(function(blob) {
            const reader = new FileReader();
            reader.onload = function() {
                const base64Data = reader.result;
                
                if (currentImageType === 'avatar') {
                    elements.currentAvatar.src = base64Data;
                    elements.croppedAvatarInput.value = base64Data;
                    showChanges('avatar');
                } else if (currentImageType === 'qr') {
                    elements.currentQr.src = base64Data;
                    elements.croppedQrInput.value = base64Data;
                    showChanges('qr');
                }
                
                hideModal();
            };
            reader.readAsDataURL(blob);
        }, 'image/jpeg', 0.9);
    }
    
    function deleteImage(type) {
        const confirmMessage = `Are you sure you want to delete your ${type === 'avatar' ? 'avatar' : 'QR code'}?`;
        
        if (!confirm(confirmMessage)) return;
        
        if (type === 'avatar') {
            elements.currentAvatar.src = '{{ asset("img/default-avatar.png") }}';
            elements.croppedAvatarInput.value = 'delete';
            showChanges('avatar');
        } else {
            elements.currentQr.src = '{{ asset("imgs/default-qr.png") }}';
            elements.croppedQrInput.value = 'delete';
            showChanges('qr');
        }
    }
    
    function cancelChanges(type) {
        const confirmMessage = `Are you sure you want to cancel your ${type === 'avatar' ? 'avatar' : 'QR code'} changes?`;
        
        if (!confirm(confirmMessage)) return;
        
        if (type === 'avatar') {
            elements.currentAvatar.src = originalSources.avatar;
            elements.croppedAvatarInput.value = '';
            hideChanges('avatar');
        } else {
            elements.currentQr.src = originalSources.qr;
            elements.croppedQrInput.value = '';
            hideChanges('qr');
        }
    }
    
    function showChanges(type) {
        if (type === 'avatar') {
            state.hasAvatarChanges = true;
            elements.cancelAvatarChanges.style.display = 'inline-block';
            elements.currentAvatar.classList.add('changed');
        } else {
            state.hasQrChanges = true;
            elements.cancelQrChanges.style.display = 'inline-block';
            elements.currentQr.classList.add('changed');
        }
    }
    
    function hideChanges(type) {
        if (type === 'avatar') {
            state.hasAvatarChanges = false;
            elements.cancelAvatarChanges.style.display = 'none';
            elements.currentAvatar.classList.remove('changed');
        } else {
            state.hasQrChanges = false;
            elements.cancelQrChanges.style.display = 'none';
            elements.currentQr.classList.remove('changed');
        }
    }
});

    </script>
  </body>
</html>
