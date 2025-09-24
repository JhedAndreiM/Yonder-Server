@extends('Front_layouts.app')

@section('title', 'Profile Settings')
@section('head')
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    @vite('resources/css/profileSettings.css')
@endsection
@section('content')
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
                <div class="qr-container">
                @if (empty(Auth::user()->qr_image))
                    <img class="qr" id="currentQr"
                        src="{{ asset('img/default-qr.png') }}"
                        alt="Default QR Code">
                @else
                    <img class="qr" id="currentQr"
                        src="{{ asset('storage/users-qr/' . Auth::user()->qr_image) }}"
                        alt="QR Code">
                @endif
                </div>
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
                    <input type="tel" name="phone_number" id="contact"
                    placeholder="{{ Auth::user()->phone_number }}"
                    value="{{ session('pending_phoneNumber') }}"
                    maxlength="11"
                    pattern="^09\d{9}$"
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.setCustomValidity('');"
                    oninvalid="this.setCustomValidity('Enter a valid PH phone number starting with 09 (11 digits)')">
                    @else
                    <input type="tel" name="phone_number" id="contact"
                    placeholder="{{ Auth::user()->phone_number }}"
                    value="{{ Auth::user()->phone_number }}"
                    pattern="^09\d{9}$"
                    maxlength="11"
                    required
                    oninvalid="this.setCustomValidity('Please enter a valid 11-digit PH mobile number starting with 09')"
                    oninput="this.setCustomValidity('')">
                    @endif
                </div>

                <div class="formGroup shortSelect">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" disabled>
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
      <div class="changePass" id="openChangePasswordModal">
        <p>Change password</p>
        <img src="{{asset('img/lock.svg')}}" alt="" />
      </div>
      <div class="signOut" id="signOut">
        <p>Sign Out</p>
        <img src="{{asset('img/signout.svg')}}" alt="" />
      </div>
    </div>
<!-- Change Password Modal -->
<div id="changePasswordModal" class="change-password-modal">
  <div class="change-password-modal-content">
    @if(!session('force_password_change'))
    <span class="change-password-modal-close" id="closeChangePasswordModal">&times;</span>
    @endif
    <h2 class="change-password-modal-title">
        @if(session('force_password_change'))
        @else
            Change Password
        @endif
    </h2>

    @if(session('force_password_change'))
    <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
        <strong>Security Notice:</strong> You are using a default password. Please change it to a secure password for your account safety.
    </div>
    @endif

    <form method="POST" action="{{ route('profile.update-password') }}" class="change-password-form" id="changePasswordForm">
      @csrf
      @if(session('force_password_change'))
        <input type="hidden" name="force_password_change" value="1">
      @endif
      <div class="change-password-form-group">
        <label for="current_password">Current Password</label>
        <div class="change-password-input-wrapper">
          <input type="password" name="current_password" id="current_password" required>
          <span class="toggle-password-visibility" data-target="current_password">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </span>
        </div>
        @error('current_password')
            <p class="change-password-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="change-password-form-group">
        <label for="new_password">New Password</label>
        <div class="change-password-input-wrapper">
          <input type="password" name="new_password" id="new_password" required>
          <span class="toggle-password-visibility" data-target="new_password">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </span>
        </div>
        <div class="password-requirements">
          <small>Password must contain:</small>
          <ul>
            <li id="req-length">At least 8 characters</li>
            <li id="req-letters">At least one letter</li>
            <li id="req-mixed">Both uppercase and lowercase letters</li>
            <li id="req-numbers">At least one number</li>
          </ul>
        </div>
        @error('new_password')
            <p class="change-password-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="change-password-form-group">
        <label for="new_password_confirmation">Confirm New Password</label>
        <div class="change-password-input-wrapper">
          <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
          <span class="toggle-password-visibility" data-target="new_password_confirmation">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </span>
        </div>
        @error('new_password_confirmation')
            <p class="change-password-error">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit" class="change-password-submit">Update Password</button>
    </form>
    @if ($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation') || session('force_password_change'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("changePasswordModal").style.display = "flex";
                // Re-initialize password toggles when modal is forced open
                setTimeout(function() {
                    if (typeof initializePasswordToggles === 'function') {
                        initializePasswordToggles();
                    }
                }, 100);
            });
        </script>
    @endif
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
    // Function to initialize password visibility toggles (global)
    function initializePasswordToggles() {
        document.querySelectorAll(".toggle-password-visibility").forEach(icon => {
            // Remove any existing event listeners to prevent duplicates
            icon.replaceWith(icon.cloneNode(true));
        });
        
        // Re-query after cloning to get fresh elements
        document.querySelectorAll(".toggle-password-visibility").forEach(icon => {
            icon.addEventListener("click", () => {
                const targetId = icon.getAttribute("data-target");
                const input = document.getElementById(targetId);
                const svg = icon.querySelector('svg');

                if (input && svg) {
                    if (input.type === "password") {
                        input.type = "text";
                        icon.style.color = "#4CAF50"; // green when active
                        // Change to eye-off icon
                        svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                    } else {
                        input.type = "password";
                        icon.style.color = "#555";
                        // Change back to eye icon
                        svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                    }
                }
            });
        });
    }

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
        const confirmModal = document.getElementById('uniqueConfirmModal'); // renamed
        const confirmMessage = document.getElementById('uniqueConfirmMessage');
        const modalIcon = document.getElementById('uniqueModalIcon');
        const btnYes = document.getElementById('uniqueConfirmYes');
        const btnNo = document.getElementById('uniqueConfirmNo');

        confirmMessage.textContent = "Are you sure you want to delete your " + type + "? This action cannot be undone.";

        confirmModal.style.display = 'flex';

        btnYes.onclick = () => {
            confirmModal.style.display = 'none';
            if (type === 'avatar') {
                elements.currentAvatar.src = '{{ asset("storage/users-avatar/avatar.png") }}';
                elements.croppedAvatarInput.value = 'delete';
                showChanges('avatar');
            } else {
                elements.currentQr.src = '{{ asset("img/default-qr.png") }}';
                elements.croppedQrInput.value = 'delete';
                showChanges('qr');
            }
        };

        btnNo.onclick = () => {
            confirmModal.style.display = 'none';
        };
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

    // for change password modal
    const openBtn = document.getElementById("openChangePasswordModal");
    const modal = document.getElementById("changePasswordModal");
    const closeBtn = document.getElementById("closeChangePasswordModal");
    const form = document.getElementById("changePasswordForm");

    // Open modal
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    });

    // Close modal + reset form
    function closeModal() {
        // Check if modal is forced open (user needs to change password)
        @if(session('force_password_change'))
            // Don't allow closing if password change is forced
            return;
        @endif
        
        modal.style.display = "none";
        form.reset(); // reset input fields
        // Reset password inputs to type "password"
        form.querySelectorAll("input[type='text']").forEach(input => {
        input.type = "password";
        });
    }
    if(closeBtn){
    closeBtn.addEventListener("click", closeModal);
    }
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
        closeModal();
        }
    });

    // Initialize password toggles on page load
    initializePasswordToggles();

    // Real-time password validation
    const newPasswordInput = document.getElementById('new_password');
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            const requirements = {
                length: password.length >= 8,
                letters: /[a-zA-Z]/.test(password),
                mixed: /[a-z]/.test(password) && /[A-Z]/.test(password),
                numbers: /\d/.test(password)
            };

            // Update requirement indicators
            document.getElementById('req-length').style.color = requirements.length ? '#4CAF50' : '#ff4444';
            document.getElementById('req-letters').style.color = requirements.letters ? '#4CAF50' : '#ff4444';
            document.getElementById('req-mixed').style.color = requirements.mixed ? '#4CAF50' : '#ff4444';
            document.getElementById('req-numbers').style.color = requirements.numbers ? '#4CAF50' : '#ff4444';
        });
    }
});

    </script>
    
<!-- Unique modal container -->
<div id="uniqueConfirmModal" class="unique-modal-overlay" style="display:none;">
  <div class="unique-modal-content">
    <div id="uniqueModalHeader" class="unique-modal-header">
        <div class="imageWrapper" id="imageWrapper">
            <img id="uniqueModalIcon" src="{{asset('imgModal/cancelLogo.svg')}}" alt="icon" />
        </div>
    </div>
    <h3 id="uniqueHeaderMessage">Confirm Delete?</h3>
    <p id="uniqueConfirmMessage">Are you sure you want to delete this Photo?</p>
    <div class="unique-modal-buttons">
      <button id="uniqueConfirmNo" class="unique-modal-btn unique-modal-no">Cancel</button>
      <button id="uniqueConfirmYes" class="unique-modal-btn unique-modal-yes">Save</button>
    </div>

  </div>
</div>
@endsection
