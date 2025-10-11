@extends('Front_layouts.app')

@section('title', 'Profile')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
     <!-- Bootstrap CSS -->
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/cancelReasonModal.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @vite('resources/css/profile.css')
    @vite('resources/js/profile.js')    
@endsection
@section('content')
<div class="mainContainer">
      <div class="left">
        <div class="profile">
          <div class="top">
            <div class="name profileStalkClick" >
              <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
              <h3>{{Auth::user()->name .' ' . Auth::user()->last_name}}</h3>
            </div>
            <div class="ratings">
              <img class="ratingLogo" src="{{ asset('img/rating.svg') }}" alt="" />
              <p class="rating">{{ number_format($sellerRating->avg_rating, 1) }}</p>
            </div>
          </div>
          <div class="myPurchases">
            <img src="{{asset('img/MyPurchases.svg')}}" alt="" />
            <h2 class="current">My Purchases</h2>
          </div>
          <div class="myVouchers">
            <img class="notCurrent" src="{{asset('img/MyVouchers.svg')}}" alt="" />
            <h2 class="notCurrent">My Vouchers</h2>
          </div>
        </div>
      </div>
      <div class="right">
        <div class="nav">
          <button id="btnAll" class="btn-filter navCurrent" data-tab="all">All</button>
          <button id="btnPending" class="btn-filter" data-tab="pending">Pending</button>
          <button id="btnReceive" class="btn-filter" data-tab="receive">To recieve</button>
          <button id="btnCancelled" class="btn-filter" data-tab="cancelled">Cancelled</button>
           <button id="btnCompleted" class="btn-filter" data-tab="completed">Completed</button>
        </div>
        <div class="items" id="itemsContainer">
          @include('partials.profileProduct', ['cartItems' => $items])
        </div>
      </div>
    </div>

        <!-- Rating Modal -->
        <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ratingModalLabel">Submit Your Review</h5>
                    </div>
                    <div class="modal-body">
                        <form id="reviewForm" action="{{ route('review.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" id="itemId">

                            <div class="rating-stars mb-3">
                                <div class="stars">
                                    <i class="fas fa-star" data-rating="1"></i>
                                    <i class="fas fa-star" data-rating="2"></i>
                                    <i class="fas fa-star" data-rating="3"></i>
                                    <i class="fas fa-star" data-rating="4"></i>
                                    <i class="fas fa-star" data-rating="5"></i>
                                </div>
                                <input type="hidden" name="rating" id="selectedRating">
                            </div>

                            <div class="form-group">
                                <label for="comment">Your Comment</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required placeholder="We'd love to hear more!"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary _close-modal"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary _submit-modal">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END NG RATING MODAL -->

    <!--  FOR MODALS  -->
      @if (session('error'))
        <div id="errorBar" class="error-bar">
                {{session('error')}} <img src="{{ asset('imgModal/barCrossLogo.svg') }}" alt="error" class="error-icon">
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
        @elseif (session('successfull'))
        <div id="successBar" class="success-bar">
            {{session('successfull')}} <img src="{{ asset('imgModal/barCheckLogo.svg') }}" alt="success" class="success-icon">
        </div>
        <script>
            const bar = document.getElementById('successBar');
            bar.classList.add('show');

            // Hide after 3 seconds
            setTimeout(() => {
                bar.classList.remove("show");
                setTimeout(() => bar.remove(), 400);
            }, 5000);
        </script>
        @endif
        <!-- END NG MODALS -->
        <!-- For receipt modal -->
        <div id="myModal" class="modal">
            <div class="modal-wrapper">
                <div class="modal-content-overlay">
                    <div class="modal-top">
                        <span class="close"><img src="{{ asset('img/back-button.svg') }}" alt=""></span>
                        <img class="downloadBtn" src="{{ asset('img/Download-Button.svg') }}" alt=""
                            onclick="screenshot()">
                    </div>
                    <div class="modal-middle">
                        <div class="middle-top">
                            <h2 style="margin:0;">Invoice</h2>
                            <h3 style="margin:4px 0 0;">Order ID # <span id="productID"></span></h3>
                            <h6 style="margin:6px 0 0;"><span id="receiptDate"></span></h6>
                        </div>
                    </div>
                    <div class="modal-bottom">
                        <div class="bottom-top">
                            <h1>Details</h1>
                        </div>
                        <div class="bottom-bottom">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="left-align" style="text-align:left;">Item</th>
                                        <th class="center-align">Qty</th>
                                        <th class="center-align">Unit Price</th>
                                        <th class="right-align">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="dotted-bottom"><span id="productName"></span></td>
                                        <td class="dotted-bottom center-align"><span id="productQuantity"></span></td>
                                        <td class="dotted-bottom center-align">P <span id="productPrice"></span></td>
                                        <td class="dotted-bottom right-align">P <span id="productLineSubtotal"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="dotted-bottom" colspan="3">Voucher Used</td>
                                        <td class="dotted-bottom right-align">P <span id="productVoucherPrice"></span></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="total center-align" colspan="2">Total</td>
                                        <td class="total right-align">P <span id="productTotal"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
         <!-- End ng Receipt Modal -->
        <!-- Unique modal container -->
<div id="uniqueConfirmModal" class="unique-modal-overlay" style="display:none;">
  <div class="unique-modal-content">
    <div id="uniqueModalHeader" class="unique-modal-header">
        <div class="imageWrapper" id="imageWrapper">
            <img id="uniqueModalIcon" src="" alt="icon" />
        </div>
    </div>
    <h3 id="uniqueHeaderMessage"></h3>
    <p id="uniqueConfirmMessage"></p>
    
    <!-- Reason selection for cancel orders -->
    <div id="cancelReasonSection" style="display:none;">
        <div class="form-group mb-3">
            <label for="cancelReason" class="form-label">Reason for Cancellation:</label>
            <select id="cancelReason" name="cancel_reason" class="form-select" required>
                <option value="">Select a reason...</option>
                <option value="changed_mind">Changed my mind</option>
                <option value="found_better_deal">Found a better deal elsewhere</option>
                <option value="seller_unresponsive">Seller is unresponsive</option>
                <option value="product_unavailable">Product is no longer available</option>
                <option value="other">Other (please specify)</option>
            </select>
        </div>
        
        <div class="form-group mb-3" id="customReasonGroup" style="display:none;">
            <label for="customReason" class="form-label">Please specify:</label>
            <textarea id="customReason" name="custom_reason" class="form-control" rows="3" placeholder="Please provide details..."></textarea>
        </div>
    </div>
    
    <div class="unique-modal-buttons">
      <button id="uniqueConfirmNo" class="unique-modal-btn unique-modal-no">Cancel</button>
      <button id="uniqueConfirmYes" class="unique-modal-btn unique-modal-yes">Save</button>
    </div>

  </div>
</div>


    <script>
            const successModal = document.getElementById("sessionModal");
            function closeSuccessModal() {
            successModal.style.display = "none";
            }
            const failedModal = document.getElementById("sessionModalFailed");
            function closeFailedModal(){
            failedModal.style.display = "none";
            }
// Button filter ('yung pending, to receive, cancelled and completed)
const buttons = document.querySelectorAll('.btn-filter');

buttons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active from all
        buttons.forEach(btn => btn.classList.remove('active', 'navCurrent'));

        // Add active to clicked
        button.classList.add('active', 'navCurrent');

        const tab = button.getAttribute('data-tab');
        fetchFilter(tab);
    });
});
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const filters = urlParams.get('filters');

    const allButton = document.getElementById('btnAll');
    const pendingButton = document.getElementById('btnPending');
    const receiveButton = document.getElementById('btnReceive');
    const cancelledButton = document.getElementById('btnCancelled');
    const completedButton = document.getElementById('btnCompleted');

    if (filters) {
        buttons.forEach(btn => btn.classList.remove('active', 'navCurrent'));

        if (filters === 'all') allButton.classList.add('active', 'navCurrent');
        if (filters === 'pending') pendingButton.classList.add('active', 'navCurrent');
        if (filters === 'receive') receiveButton.classList.add('active', 'navCurrent');
        if (filters === 'cancelled') cancelledButton.classList.add('active', 'navCurrent');
        if (filters === 'completed') completedButton.classList.add('active', 'navCurrent');

        fetchFilter(filters);

        // Remove the ?filters=... from URL for cleanliness
        const url = new URL(window.location);
        url.searchParams.delete('filters');
        window.history.replaceState({}, '', url);
    }
});
function fetchFilter(tab) {
    let url = "?filter=";
    if (tab) {
        url += tab;
    }

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        const itemsContainer = document.getElementById('itemsContainer');
        itemsContainer.innerHTML = data;
    })
    .catch(error => {
        console.error('Error fetching filtered products:', error);
    });
}
// End ng button

// Handle Rate button click
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('rate-btn')) {
        console.log("clikced");
        const itemId = e.target.dataset.itemid;
        document.getElementById('itemId').value = itemId;
        const modal = new bootstrap.Modal(document.getElementById('ratingModal'));
        modal.show();
    }
});
document.getElementById('ratingModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('selectedRating').value = '';

    stars.forEach(star => star.classList.remove('active'));

    const commentField = document.getElementById('comment');
    if (commentField) {
        commentField.value = '';
    }

    document.getElementById('itemId').value = '';
});
// Handle star rating
const stars = document.querySelectorAll('.rating-stars .stars i');
stars.forEach(star => {
    star.addEventListener('mouseover', function() {
        const rating = this.getAttribute('data-rating');
        highlightStars(rating);
    });

    star.addEventListener('click', function() {
        const rating = this.getAttribute('data-rating');
        document.getElementById('selectedRating').value = rating;
        highlightStars(rating);
    });
});

// Keep stars highlighted after selection
const starsContainer = document.querySelector('.rating-stars .stars');
starsContainer.addEventListener('mouseout', function() {
    const selectedRating = document.getElementById('selectedRating').value;
    highlightStars(selectedRating);
});

function highlightStars(rating) {
    stars.forEach(star => {
        const starRating = star.getAttribute('data-rating');
        if (starRating <= rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

// receipt JS
// Open receipt modal and populate data
function openProductModal(button) {
    var modal = document.getElementById("myModal");
    modal.style.display = "flex";

    document.getElementById('productName').textContent = button.dataset.name;
    document.getElementById('productQuantity').textContent = button.dataset.qty;
    document.getElementById('productPrice').textContent = Number(button.dataset.price || 0).toFixed(2);
    document.getElementById('productVoucherPrice').textContent = Number(button.dataset.voucher || 0).toFixed(2);
    document.getElementById('productID').textContent = button.dataset.id;
    document.getElementById('receiptDate').textContent = button.dataset.date;

    // Calculate total
    const unitPrice = parseFloat(button.dataset.price) || 0;
    const qty = parseInt(button.dataset.qty) || 0;
    const voucher = parseFloat(button.dataset.voucher) || 0;
    const lineSubtotal = unitPrice * qty;
    const total = lineSubtotal - voucher;
    const lineSubtotalEl = document.getElementById('productLineSubtotal');
    if (lineSubtotalEl) lineSubtotalEl.textContent = lineSubtotal.toFixed(2);
    document.getElementById('productTotal').textContent = total.toFixed(2);
}

// Close receipt modal
function closeReceiptModal() {
  const modal = document.getElementById('myModal');
  if (!modal) return;
  modal.style.display = 'none';
  document.body.classList.remove('modal-open');
  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
}

document.querySelectorAll('#myModal .close').forEach(btn => {
  btn.addEventListener('click', closeReceiptModal);
});

// Screenshot receipt modal
function screenshot() {
    const captureElement = document.querySelector(".modal-content-overlay");
    if (!captureElement) return;

    // Temporarily hide UI controls during capture
    const controls = Array.from(captureElement.querySelectorAll('.modal-top .close, .modal-top .downloadBtn'));
    const previousDisplay = controls.map(el => el.style.display);
    controls.forEach(el => { el.style.display = 'none'; });

    html2canvas(captureElement).then(function(c) {
        const url = c.toDataURL();
        const linkEl = document.createElement("a");
        linkEl.setAttribute("href", url);
        linkEl.setAttribute("download", "receipt.png");
        linkEl.click();
        linkEl.remove();
    }).finally(() => {
        // Restore controls
        controls.forEach((el, i) => { el.style.display = previousDisplay[i] || ''; });
    });
}
// end

// for the upload receipt
document.addEventListener('DOMContentLoaded', function() {
    // Event delegation for dynamically loaded elements
    document.body.addEventListener('change', function(e) {
        if (e.target && e.target.id && e.target.id.startsWith('receiptInput_')) {
            const cartId = e.target.id.split('_')[1];
            const file = e.target.files[0];
            if (file) {
                console.log("File selected for cart:", cartId);
                const receiptModal = document.getElementById('gcashReceiptModal_' + cartId);
                const receiptPreview = document.getElementById('receiptPreview_' + cartId);
                
                if (receiptModal && receiptPreview) {
                    // Show the modal
                    receiptModal.style.display = "flex";
                    // Preview the image
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        receiptPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
    });

    // Similarly for other events
    document.body.addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('cancelReceipt') || e.target.id.startsWith('cancelReceipt_'))) {
            const cartId = e.target.getAttribute('data-cart-id') || e.target.id.split('_')[1];
            const receiptModal = document.getElementById('gcashReceiptModal_' + cartId);
            const receiptInput = document.getElementById('receiptInput_' + cartId);
            if (receiptModal && receiptInput) {
                receiptModal.style.display = "none";
                receiptInput.value = "";
            }
        }
        
        if (e.target && (e.target.classList.contains('submitReceipt') || e.target.id.startsWith('submitReceipt_'))) {
            const cartId = e.target.getAttribute('data-cart-id') || e.target.id.split('_')[1];
            const form = document.querySelector(`form[action*="${cartId}"]`);
            const realCartId = document.getElementById('realCartId_' + cartId);
            console.log('Submitting for cart:', cartId);
            if (form) form.submit();
        }
    });

    // for pop up ng view image button
    document.body.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('viewReceiptBtn')) {
            const imageUrl = e.target.getAttribute('data-image');
            const modalView = e.target.nextElementSibling; 
            const receiptView = modalView.querySelector('.receiptView');

            if (modalView && receiptView) {
                receiptView.src = imageUrl;
                modalView.style.display = 'flex';
            }
        }

        if (e.target && e.target.classList.contains('closeButton_ViewGcash')) {
            const modalView = e.target.closest('.gcashReceiptModalView');
            if (modalView) {
                modalView.style.display = 'none';
            }
        }

        if (e.target && e.target.classList.contains('gcashReceiptModalView')) {
            e.target.style.display = 'none';
        }
    });
    document.body.addEventListener('click', function (e) {
      // Open Seller QR modal 
      if (e.target && e.target.classList.contains('viewSellerQRBtn')) {
        const modalView = e.target.nextElementSibling; 
        if (modalView && modalView.classList.contains('sellerQRModalView')) {
          modalView.style.display = 'flex';
        }
      }

      // Close via close button
      if (e.target && e.target.classList.contains('closeButton_ViewSellerQR')) {
        const modalView = e.target.closest('.sellerQRModalView');
        if (modalView) {
          modalView.style.display = 'none';
        }
      }

      // Close by clicking the overlay
      if (e.target && e.target.classList.contains('sellerQRModalView')) {
        e.target.style.display = 'none';
      }
    });
    // for links
    const myListings = document.querySelectorAll('.myListings');
    myListings.forEach(button =>{
        button.addEventListener('click', function() {
            window.location.href = "{{ route('listing.seller') }}";
        });
    });    
    const myVouchers = document.querySelectorAll('.myVouchers');
    myVouchers.forEach(button =>{
        button.addEventListener('click', function() {
            window.location.href = "{{ route('show.vouchers') }}";
        });
    });    
    const mySales = document.querySelectorAll('.mySales');
    mySales.forEach(button =>{
        button.addEventListener('click', function() {
            window.location.href = "{{ route('student.sales') }}";
        });
    });
    const profileStalkClick = document.querySelectorAll('.profileStalkClick');
    profileStalkClick.forEach(button =>{
        button.addEventListener('click', function() {
            window.location.href = "{{ route('stalk.profile', Auth::id()) }}";
        });
    });  
});
    document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });
//end
</script>
@endsection