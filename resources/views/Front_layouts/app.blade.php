<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Yonder')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/navbar.css')
    @vite('resources/js/navbar.js')
    @include('partials.common-scripts')
    @yield('head')
    @stack('styles')
    <style>
      body {
      background-image: url("{{ asset('img/background.svg') }}");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: top center;
      }
  </style>
</head>
<body>
    @include('partials.navbar')
    <main>
        @yield('content')
    </main>
<script>
    document.addEventListener("DOMContentLoaded", function () {
  const notifBtn = document.getElementById("notif-icon");
  const notifDropdown = document.getElementById("notificationDropdown");
  const profileBtn = document.querySelector(".profileBtn");
  const profileDropdown = document.getElementById("profileDropdown");
  const closeNotif = document.querySelector(".closeButton");
  const wishlistButtons = document.querySelectorAll('.wishlistBtn');
  const cartButton = document.querySelectorAll('.cartBtn');
  const faqsBtn = document.querySelectorAll('.faqsBtn');
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
notifBtn.addEventListener("click", function (e) {
  e.preventDefault();
  e.stopPropagation();

  // toggle dropdown
  const isOpen = notifDropdown.style.display === "block";
  notifDropdown.style.display = isOpen ? "none" : "block";
  profileDropdown.style.display = "none"; 

  if (!isOpen) {
    // If dropdown just opened, wait 1 second before marking as read
    setTimeout(() => {
      // remove badge
      const badge = document.querySelector('.notif-badge');
      if (badge) {
        badge.remove();
      }

      // mark all as read (frontend only)
      document.querySelectorAll('.notification.unread').forEach(notif => {
        notif.classList.remove('unread');
      });

      // 🔥 send to backend (AJAX)
      fetch("{{ route('notifications.markAllRead') }}", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']")?.content,
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then(res => res.json())
        .then(data => {
        })
        .catch(err => {
          console.error("❌ Failed to sync with backend:", err);
        });

    }, 1000);
  }
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
  document.getElementById('logoClick').addEventListener('click', function() {
        @if(auth()->check())
            @if(auth()->user()->role === 'student')
                window.location.href = "{{ route('student.dashboard') }}";
            @elseif(auth()->user()->role === 'organization')
                window.location.href = "{{ route('organization.dashboard') }}";
            @elseif(auth()->user()->role === 'admin')
                window.location.href = "{{ route('admin.dashboard') }}";
            @endif
        @else
            window.location.href = "{{ route('landing') }}";
        @endif
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
      faqsBtn.forEach(button=>{
            button.addEventListener('click', function(){
                window.location.href= "{{route('FAQs')}}";
                
            })
        });
        document.querySelectorAll('#profileDropdown li').forEach(li => {
        li.addEventListener('click', () => {
            window.location.href = li.dataset.url;
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const seeMoreBtn = document.getElementById("see-more-btn");
    const notificationList = document.querySelector(".notification-list");

    if (seeMoreBtn) {
        seeMoreBtn.addEventListener("click", async function () {
            const offset = parseInt(this.dataset.offset, 10);

            try {
                const response = await fetch(`/notifications/load?offset=${offset}`);
                const newNotifications = await response.json();

                if (newNotifications.length > 0) {
                    newNotifications.forEach(n => {
                        const titleHtml =
                            n.title === "Product Approved"
                                ? `<span style="color:Green;">${n.title}</span>`
                                : n.title === "Product Rejected"
                                ? `<span style="color:red;">${n.title}</span>`
                                : n.title;

                        const html = `
                            <div class="notification ${n.is_read ? '' : 'unread'}">
                                <div class="notification-content">
                                    <h1>${titleHtml}</h1>
                                    <div class="Message">${n.message}</div>
                                </div>
                                <div class="notification-time">${n.time_ago}</div>
                            </div>
                        `;

                        this.insertAdjacentHTML('beforebegin', html);
                    });

                    // update offset for next load
                    this.dataset.offset = offset + newNotifications.length;

                    // hide if no more
                    if (newNotifications.length < 10) {
                        this.style.display = "none";
                    }
                } else {
                    this.style.display = "none";
                }
            } catch (err) {
                console.error("Failed to load more notifications:", err);
            }
        });
    }
});
</script>
</body>
</html>