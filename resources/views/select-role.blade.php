<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Who are you?</h2>
<a href="{{ route('login.form', ['role' => 'admin']) }}">Admin</a>
<a href="{{ route('login.form', ['role' => 'organization']) }}">Organization</a>
<a href="{{ route('login.form', ['role' => 'student']) }}">Student</a>
</body>
</html>