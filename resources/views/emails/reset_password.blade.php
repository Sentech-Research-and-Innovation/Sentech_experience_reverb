<!DOCTYPE html>
<html>

<head>
    <title>Feedback Form Submission</title>

</head>

<body>
    <h1>Feedback Form Submission</h1>

    <p><strong>Name:</strong> {{ $user['name'] }}</p>
    <p><strong>Email:</strong> {{ $user['email'] }}</p>
    <p><strong>Comment:</strong></p>
    <p>{{ $user['comment'] }}</p>
</body>

</html>
