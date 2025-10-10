@extends('Front_layouts.app')

@section('title', 'Order Page')
@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@vite('resources/css/orderPage.css')
@vite('resources/js/profile.js')
@endsection
@section('content')
@include('partials.chat-recent')
    <div class="floating">
      <a href="#" id="chatWidgetToggle"><img src="{{ asset('img/message.png') }}" alt="" /></a>
    </div>
<div class="mainContainer">
            <div class="container">
                <div class="containerLeft">
                    <ul class="sidebar-menu">
                        <li>
                            <a href="{{ route('organization.dashboard') }}" 
                            class="{{ request()->routeIs('organization.dashboard') ? 'active' : '' }}">
                                <i class="fa-solid fa-cart-shopping"></i> My Products
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('order.page') }}" 
                            class="{{ request()->routeIs('order.page') ? 'active' : '' }}">
                                <i class="fa-solid fa-pencil"></i> Product Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('review.page') }}" 
                            class="{{ request()->routeIs('review.page') ? 'active' : '' }}">
                                <i class="fa-solid fa-star"></i> Product Reviews
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('org.report') }}" 
                            class="{{ request()->routeIs('org.report') ? 'active' : '' }}">
                                <i class="fa-solid fa-paperclip"></i> Inventory
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('create.listing') }}" 
                            class="{{ request()->routeIs('create.listing') ? 'active' : '' }}">
                                <i class="fa-solid fa-plus"></i> Add Product
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="containerRight">
                    <div class="categories">
                        <button id="btnAll" class="btn-filter active" data-tab="all">All</button>
                        <button id="btnPending" class="btn-filter" data-tab="pending">Pending</button>
                        <button id="btnReceive" class="btn-filter" data-tab="receive">To Pickup</button>
                        <button id="btnCancelled" class="btn-filter" data-tab="cancelled">Cancelled</button>
                        <button id="btnCompleted" class="btn-filter" data-tab="completed">Completed</button>
                    </div>
                    <div class="itemsContainer" id="itemsContainer">
                        @include('partials.profileProduct', ['cartItems' => $items])
                    </div>
                </div>
            </div>
         </div>
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
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary _close-modal"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary _submit-modal">Submit Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

        function closeFailedModal() {
            failedModal.style.display = "none";
        }
        const buttons = document.querySelectorAll('.btn-filter');
        //para pag ka load gana agad all filter
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const filters = urlParams.get('filters');
            const cancelledButton = document.getElementById('btnCancelled');
            const allButton = document.getElementById('btnAll');
            const pendingButton = document.getElementById('btnPending');
            const receiveButton = document.getElementById('btnReceive');
            const completedButton = document.getElementById('btnCompleted');
            if (true) {
                buttons.forEach(btn => btn.classList.remove('active'));
                filter = "cancelled"
                if (filters === 'all') {
                    allButton.classList.add('active');
                    console.log('all');
                }
                if (filters === 'pending') {
                    pendingButton.classList.add('active');
                    console.log('pending');
                }
                if (filters === 'receive') {
                    receiveButton.classList.add('active');
                    console.log('receive');
                }
                if (filters === 'cancelled') {
                    cancelledButton.classList.add('active');
                    console.log('cancelled');
                }
                if (filters === 'completed') {
                    completedButton.classList.add('active');
                    console.log('completed');
                }

                fetchFilter(filters);
                // basically remove yung ?filters= sa url para malinis
                const url = new URL(window.location);
                url.searchParams.delete('filters');
                window.history.replaceState({}, '', url);
            }
            //para sa modal
            console.log('modal');



        });
        // modal opem
        function openProductModalSeller(button) {
            var modal = document.getElementById("myModal");
            modal.style.display = "flex";
            document.getElementById('productName').textContent = button.dataset.names;
            document.getElementById('productQuantity').textContent = button.dataset.qtys;
            document.getElementById('productPrice').textContent = Number(button.dataset.prices || 0).toFixed(2);
            document.getElementById('productVoucherPrice').textContent = Number(button.dataset.vouchers || 0).toFixed(2);
            document.getElementById('productID').textContent = button.dataset.id;
            document.getElementById('receiptDate').textContent = button.dataset.date;

            const unitPrice = parseFloat(button.dataset.prices) || 0;
            const qty = parseInt(button.dataset.qtys) || 0;
            const voucher = parseFloat(button.dataset.vouchers) || 0;
            const lineSubtotal = unitPrice * qty;
            const total = lineSubtotal - voucher;
            const lineSubtotalEl = document.getElementById('productLineSubtotal');
            if (lineSubtotalEl) lineSubtotalEl.textContent = lineSubtotal.toFixed(2);
            document.getElementById('productTotal').textContent = total.toFixed(2);
        }
        // modal close
        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
        function screenshot() {
            const captureElement = document.querySelector(".modal-content-overlay");
            if (!captureElement) return;
            const controls = Array.from(captureElement.querySelectorAll('.modal-top .close, .modal-top .downloadBtn'));
            const previousDisplay = controls.map(el => el.style.display);
            controls.forEach(el => { el.style.display = 'none'; });
            if (typeof html2canvas !== 'function') {
                controls.forEach((el, i) => { el.style.display = previousDisplay[i] || ''; });
                alert('Capture library not loaded. Please try again.');
                return;
            }
            html2canvas(captureElement,{backgroundColor: '#ffffff'})
                .then(function(c) {
                    const url = c.toDataURL('image/png');
                    const linkEl = document.createElement("a");
                    linkEl.href = url;
                    linkEl.download = 'receipt.png';
                    document.body.appendChild(linkEl);
                    linkEl.click();
                    linkEl.remove();
                })
                .catch(() => {
                    alert('Failed to capture. Please try again.');
                })
                .finally(() => {
                    controls.forEach((el, i) => { el.style.display = previousDisplay[i] || ''; });
                });
        }

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove .active from all buttons para isang button lagn active
                buttons.forEach(btn => btn.classList.remove('active'));

                // Add .active sa clicked button
                button.classList.add('active');

                const tab = button.getAttribute('data-tab');

                fetchFilter(tab);
            });
        });

        function fetchFilter(tab) {
            let url = "?filter=";
            if (tab) {
                url += tab;
            }
            // kukunin current url tapos dadagdag yung let url tapos send sa sarili so mapunta sa route.

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                //basically converts yung raw HTTP Req to Html tapos nilalagay sa id div ko
                .then(response => response.text())
                .then(data => {
                    const itemsContainer = document.getElementById('itemsContainer');
                    itemsContainer.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching filtered products:', error);
                })
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Rate button click
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('rate-btn')) {
                    const itemId = e.target.dataset.itemid;
                    document.getElementById('itemId').value = itemId;
                    const modal = new bootstrap.Modal(document.getElementById('ratingModal'));
                    modal.show();
                }
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
        });
        document.body.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('viewReceiptBtn')) {
                const imageUrl = e.target.getAttribute('data-image');
                const modalView = document.getElementById('gcashReceiptModalView');
                const receiptView = document.getElementById('receiptView');

                if (modalView && receiptView) {
                    receiptView.src = imageUrl;
                    modalView.style.display = 'flex';
                }
            }

            // Close View Image modal
            if (e.target && e.target.id === 'closeReceiptView') {
                const modalView = document.getElementById('gcashReceiptModalView');
                if (modalView) {
                    modalView.style.display = 'none';
                }
            }
        });
    </script>
@endsection