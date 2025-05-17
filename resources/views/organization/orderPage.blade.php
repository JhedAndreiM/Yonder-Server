@extends('Front_layouts.org')

@section('head')
<title>Order</title>
<style>
        body {
        background-image: url("{{ asset('img/background.svg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        }
    </style>
    <!-- Bootstrap CSS -->
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@vite('resources/css/orderPage.css')
@endsection
@section('maincontent')
<div class="container">
        <div class="container-top"></div>
        <div class="container-bottom">
            <div class="container-bottom-left">
                <div class="left-container">
                    <div class="left-one">
                        <h3>PBEN Organization</h3>
                    </div>
                    <div class="left-two">
                        <hr>
                    </div>
                    <div class="left-three"><i class="fa-solid fa-basket-shopping left-icon"></i><a href="{{ route('organization.dashboard') }}">Products</a></div>
                    <div class="left-four"><i class="fa-solid fa-list-check left-icon"></i><span class="currentPage">Orders</span></div>
                    <div class="left-five"><i class="fa-solid fa-star-half-stroke left-icon"></i><a href="{{ route('review.page') }}">Reviews</a></div>
                    <div class="left-six"><i class="fa-solid fa-money-check-dollar left-icon"></i><a href="{{ route('org.report') }}">Dashboard</a></div>
                </div>
            </div>
            <div class="rightPart">
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
        <div id="myModal" class="modal">
            <div class="modal-wrapper">
                <svg width="578" height="732" viewBox="0 0 578 732" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g filter="url(#filter0_d_802_127)">
                        <mask id="path-1-inside-1_802_127" fill="white">
                            <path
                                d="M558 0C569.046 0 578 8.95431 578 20V351.006C577.845 351.002 577.69 351 577.535 351C565.988 351 556.627 361.074 556.627 373.5C556.627 385.926 565.988 396 577.535 396C577.69 396 577.845 395.997 578 395.993V708C578 719.046 569.046 728 558 728H20C8.95431 728 8.85911e-08 719.046 0 708V395.86C10.4537 394.616 18.585 385.081 18.585 373.5C18.585 361.919 10.4538 352.383 0 351.139V20C0 8.95431 8.95431 0 20 0H558Z" />
                        </mask>
                        <path
                            d="M558 0C569.046 0 578 8.95431 578 20V351.006C577.845 351.002 577.69 351 577.535 351C565.988 351 556.627 361.074 556.627 373.5C556.627 385.926 565.988 396 577.535 396C577.69 396 577.845 395.997 578 395.993V708C578 719.046 569.046 728 558 728H20C8.95431 728 8.85911e-08 719.046 0 708V395.86C10.4537 394.616 18.585 385.081 18.585 373.5C18.585 361.919 10.4538 352.383 0 351.139V20C0 8.95431 8.95431 0 20 0H558Z"
                            fill="white" />
                        <path
                            d="M578 20H580H578ZM578 351.006L577.953 353.005L580 353.053V351.006H578ZM577.535 351V349V351ZM577.535 396V398V396ZM578 395.993H580V393.946L577.953 393.994L578 395.993ZM558 728V730V728ZM0 708H-2H0ZM0 395.86L-0.236322 393.874L-2 394.084V395.86H0ZM0 351.139H-2V352.915L-0.23631 353.125L0 351.139ZM558 0V2C567.941 2 576 10.0589 576 20H578H580C580 7.84974 570.15 -2 558 -2V0ZM578 20H576V351.006H578H580V20H578ZM578 351.006L578.047 349.006C577.88 349.003 577.709 349 577.535 349V351V353C577.672 353 577.81 353.002 577.953 353.005L578 351.006ZM577.535 351V349C564.748 349 554.627 360.11 554.627 373.5H556.627H558.627C558.627 362.037 567.228 353 577.535 353V351ZM556.627 373.5H554.627C554.627 386.89 564.748 398 577.535 398V396V394C567.228 394 558.627 384.963 558.627 373.5H556.627ZM577.535 396V398C577.716 398 577.891 397.996 578.047 397.993L578 395.993L577.953 393.994C577.8 393.997 577.665 394 577.535 394V396ZM578 395.993H576V708H578H580V395.993H578ZM578 708H576C576 717.941 567.941 726 558 726V728V730C570.15 730 580 720.15 580 708H578ZM558 728V726H20V728V730H558V728ZM20 728V726C10.0589 726 2 717.941 2 708H0H-2C-2 720.15 7.84974 730 20 730V728ZM0 708H2V395.86H0H-2V708H0ZM0 395.86L0.236322 397.846C11.7969 396.471 20.585 385.989 20.585 373.5H18.585H16.585C16.585 384.173 9.11062 392.762 -0.236322 393.874L0 395.86ZM18.585 373.5H20.585C20.585 361.011 11.797 350.528 0.23631 349.153L0 351.139L-0.23631 353.125C9.11056 354.237 16.585 362.827 16.585 373.5H18.585ZM0 351.139H2V20H0H-2V351.139H0ZM0 20H2C2 10.0589 10.0589 2 20 2V0V-2C7.84974 -2 -2 7.84974 -2 20H0ZM20 0V2H558V0V-2H20V0Z"
                            fill="#1D1D1D" mask="url(#path-1-inside-1_802_127)" />
                    </g>
                    <defs>
                        <filter id="filter0_d_802_127" x="0" y="0" width="578" height="732"
                            filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                            <feColorMatrix in="SourceAlpha" type="matrix"
                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                            <feOffset dy="4" />
                            <feComposite in2="hardAlpha" operator="out" />
                            <feColorMatrix type="matrix" values="0 0 0 0 0.1125 0 0 0 0 0.1125 0 0 0 0 0.1125 0 0 0 1 0" />
                            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_802_127" />
                            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_802_127" result="shape" />
                        </filter>
                    </defs>
                </svg>
                <div class="modal-content-overlay">
                    <div class="modal-top">
                        <span class="close"><img src="{{ asset('img/back-button.svg') }}" alt=""></span>
                        <img class="downloadBtn" src="{{ asset('img/Download-Button.svg') }}" alt=""
                            onclick="screenshot()">
                    </div>
                    <div class="modal-middle">
                        <div class="middle-top">
                            <h3>Order ID # <span id="productID"></span></h3>
                            <h6><span id="receiptDate"></span></h6>
                        </div>
                        <div class="middle-bottom">
                            <h2>Here's your receipt</h2>
                            <img src="{{ asset('img/image 9.svg') }}" alt="">
                        </div>
                    </div>
                    <div class="modal-bottom">
                        <div class="bottom-top">
                            <h1>Details:</h1>
                        </div>
                        <div class="bottom-bottom">
                            <table>
                                <tr>
                                    <td class="dotted-bottom"><span id="productName"></span></td>
                                    <td class="dotted-bottom center-align">x <span id="productQuantity"></span></td>
                                    <td class="dotted-bottom center-align">P <span id="productPrice"></span></td>
                                </tr>
                                <tr>
                                    <td class="dotted-bottom">Voucher Used</td>
                                    <td class="dotted-bottom"></td>
                                    <td class="dotted-bottom center-align"><span id="productVoucherPrice"></span></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="total center-align">Total: </td>
                                    <td class="total center-align">P <span id="productTotal"></span></td>
                                </tr>
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
            <div id="sessionModalFailed" class="sessionModal">

                <!-- Modal content -->
                <div class="sessionModal-content">
                    <div class="top-success">
                    <div class="errorIcon"><img src="{{ asset('img/ErrorIcon.svg') }}" alt="profile"></div>
                </div>
                <div class="middle-success">
                    <h1>Failed!</h1>
                    <h5>Review Failed! Try again!</h5>
                </div>
                <div class="bottom-success">
                    <button onclick="closeFailedModal()">Okay!</button>
                </div>
                </div>

            </div>
        @elseif (session('successfull'))
            <div id="sessionModal" class="sessionModal">

                <!-- Modal content -->
                <div class="sessionModal-content">
                    <div class="top-success">
                    <div class="errorIcon"><img src="{{ asset('img/SuccessIcon.svg') }}" alt="profile"></div>
                </div>
                <div class="middle-success">
                    <h1>Success!</h1>
                    <h5>Review Successfully Recorded!</h5>
                </div>
                <div class="bottom-success">
                    <button onclick="closeSuccessModal()">Okay!</button>
                </div>
                </div>

            </div>
        @endif
<script>
            const successModal = document.getElementById("sessionModal");
            function closeSuccessModal() {
            successModal.style.display = "none";
            }
            const failedModal = document.getElementById("sessionModalFailed");
            function closeFailedModal(){
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
                    filter="cancelled"
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
                modal.style.display = "block";
                document.getElementById('productName').textContent = button.dataset.names;
                document.getElementById('productQuantity').textContent = button.dataset.qtys;
                document.getElementById('productPrice').textContent = button.dataset.prices;
                document.getElementById('productVoucherPrice').textContent = button.dataset.vouchers;
                document.getElementById('productID').textContent = button.dataset.id;
                document.getElementById('receiptDate').textContent = button.dataset.date;
                document.getElementById('productTotal').textContent = ((button.dataset.prices * button.dataset.qtys) - button
                    .dataset.vouchers);
                console.log('wtf');
            }
            // modal close
            var span = document.getElementsByClassName("close")[0];
            span.onclick = function() {
                var modal = document.getElementById("myModal");
                modal.style.display = "none";
            }
            // screenshot
            function screenshot() {
                const captureElement = document.querySelector(".modal-wrapper");
                html2canvas(captureElement).then(function(c) {
                    const url = c.toDataURL();
                    const linkEl = document.createElement("a");
                    linkEl.setAttribute("href", url);
                    linkEl.setAttribute("download", "receipt.png");
                    linkEl.click();
                    linkEl.remove();
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
        </script>
@endsection