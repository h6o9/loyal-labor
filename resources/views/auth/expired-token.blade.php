<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Token Expired') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .expired-icon {
            color: #e74c3c;
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 28px;
        }
        .message {
            color: #e74c3c;
            font-size: 18px;
            margin-bottom: 25px;
            padding: 15px;
            background: #fdf2f2;
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
            text-align: left;
        }
        .description {
            color: #7f8c8d;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #e9ecef;
        }
        .info-box h3 {
            color: #495057;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-box p {
            color: #6c757d;
            margin: 5px 0;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #95a5a6;
            font-size: 14px;
        }
        .clock-icon {
            color: #f39c12;
            font-size: 20px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="expired-icon">&#x23f0;</div>
        <h1>{{ __('Token Expired') }}</h1>
        
        <div class="message">
            <strong>{{ __('Your token has expired') }}</strong><br>
            {{ __('Please request a new password reset link') }}
        </div>
        
        <div class="description">
            {{ __('The password reset link you clicked is no longer valid. This happens for security reasons when the token expires after a certain time period.') }}
        </div>
        
        <div class="info-box">
            <h3><i class="clock-icon">&#x23f1;</i> {{ __('What to do next?') }}</h3>
            <p>1. {{ __('Go back to your email') }}</p>
            <p>2. {{ __('Request a new password reset') }}</p>
            <p>3. {{ __('Click the new link within the time limit') }}</p>
        </div>
        
        <div class="footer">
            {{ __('For security reasons, password reset tokens expire after a limited time') }}
        </div>
    </div>
</body>
</html>
