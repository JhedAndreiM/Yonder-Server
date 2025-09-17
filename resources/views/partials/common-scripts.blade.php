<!-- Common scripts for real-time features -->
@auth
<script>
    window.userId = {{ auth()->id() }};
</script>
@endauth
@vite(['resources/js/app.js'])