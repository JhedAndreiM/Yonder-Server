{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden</title>
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/error.css')
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <h1 class="error-code">403</h1>
            <h2 class="error-message">Access Denied</h2>
            <p class="error-description">
                Sorry, you don't have permission to access this page.
            </p>
            <a href="{{ url('/') }}" class="back-button">Back to Home</a>
        </div>
    </div>
</body>
</html>
