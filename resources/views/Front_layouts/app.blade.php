<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Yonder')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    @vite('resources/css/navbar.css')
    @vite('resources/js/navbar.js')
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
  console.log('test');
  const notifBtn = document.querySelector(".notificationBtn");
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
</script>
</body>
</html>