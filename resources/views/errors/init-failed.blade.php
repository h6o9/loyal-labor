<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Initialization Failed | Contact: websolutionus1@gmail.com</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            * {
                box-sizing: border-box;
            }

            html,
            body {
                margin: 0;
                padding: 0;
                height: 100%;
                font-family: Arial, sans-serif;
                background-color: #f8fafc;
                color: #333;
            }

            .container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
                text-align: center;
                padding: 20px;
            }

            .logo {
                max-width: 150px;
                margin-bottom: 20px;
            }

            h1 {
                font-size: 24px;
                margin: 0 0 10px;
            }

            p {
                font-size: 16px;
                margin: 5px 0;
            }

            .contact {
                margin-top: 20px;
                color: #666;
                font-size: 14px;
            }

            .contact strong {
                color: #000;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <img class="logo" src="{{ asset('website/images/logo.webp') }}" alt="{{ __('Logo') }}">

            @if (isset($error))
                <h1 style="color:red;">{{ __('App Initializition failed...') }}</h1>
                <p style="color:red;">{{ $error }}</p>
            @else
                <h1>{{ __('App is Initializing...') }}</h1>
                <p>{{ __('Please wait while we load configuration.') }}</p>
            @endif

            <div class="contact">
                <!-- <p>{{ __('If this issue persists, please contact the script provider.') }}</p> -->
                <!-- <p>{{ __('Email') }}: <strong>websolutionus1@gmail.com</strong></p> -->
            </div>
        </div>
    </body>

</html>
