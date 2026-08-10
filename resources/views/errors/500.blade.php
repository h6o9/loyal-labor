<!DOCTYPE html>

<html>

<head>

    <title>Laravel Error - Debug Mode</title>

    <style>

        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }

        .error-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }

        .error-title { color: #e74c3c; font-size: 24px; margin-bottom: 20px; }

        .error-message { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; }

        .error-details { background: #f8f9fa; padding: 15px; border-radius: 4px; font-family: monospace; }

        .back-link { color: #007bff; text-decoration: none; margin-top: 20px; display: inline-block; }

    </style>

</head>

<body>

    <div class="error-container">

        <h1 class="error-title">🚨 Laravel Error Occurred</h1>

        

        <div class="error-message">

            <strong>Debug Information:</strong><br>

            Please check the Laravel logs for detailed error information.

        </div>

        

        <div class="error-details">

            <strong>Error Details:</strong><br>

            @if(isset($exception))

                Message: {{ $exception->getMessage() }}<br>

                File: {{ $exception->getFile() }}<br>

                Line: {{ $exception->getLine() }}

            @else

                No exception details available. Check Laravel logs.

            @endif

        </div>

        

        <div class="error-details">

            <strong>Quick Debug Steps:</strong><br>

            1. Check storage/logs/laravel.log<br>

            2. Verify database connection<br>

            3. Check route definitions<br>

            4. Verify controller exists and is properly namespaced

        </div>

        


    </div>

</body>

</html>

