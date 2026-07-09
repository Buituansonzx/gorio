<!DOCTYPE html>
<html>
<head>
    <title>Server Error</title>
</head>
<body>
<h2>🚨 Server Error ({{ $env }})</h2>
<p><strong>Message:</strong> {{ $error['message'] }}</p>
<p><strong>File:</strong> {{ $error['file'] }}</p>
<p><strong>Line:</strong> {{ $error['line'] }}</p>
<pre>{{ $error['trace'] }}</pre>
</body>
</html>
