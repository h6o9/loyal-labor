<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .error-code {
            font-size: 72px;
            color: #dc3545;
            margin: 0;
        }
        .error-title {
            font-size: 24px;
            color: #333;
            margin: 10px 0;
        }
        .error-message {
            font-size: 16px;
            color: #666;
            margin: 20px 0;
        }
        .back-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-title">Forbidden</div>
        <div class="error-message">
            {{ $exception->getMessage() ?: 'You don\'t have permission to access this page. If you believe this is an error, let us know.' }}
        </div>
    </div>
</body>
</html>
