<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        .verification-code {
            font-size: 24px;
            font-weight: bold;
            color: #3490dc;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            color: #777777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Email Verification</h2>
        <p>{{$systemSettings?->system_name ?? config('app.name')}}.</p>
    </div>
    <div class="content">
        <p>Hoi {{ $user->name ?? 'there' }},</p>

        <p>Bedankt voor het registreren! Gebruik onderstaande code om je e-mailadres te verifiëren:</p>

        <div class="verification-code">{{ $otp }}</div>

        <p>Je weg terugvinden naar jouw innerlijke kompas begint vandaag. Ik wens je veel plezier met mijn app {{$systemSettings?->system_name ?? config('app.name')}}.</p>

        <p>Groetjes,<br>Ruud van de Wiel van {{$systemSettings?->system_name ?? config('app.name')}}.</p>
    </div>
    <div class="footer">
        <p>{{$systemSettings?->address ?? ""}}</p>
        <p>© {{ date('Y') }} {{$systemSettings?->system_name ?? config('app.name')}}. All rights reserved.</p>
    </div>
</div>
</body>
</html>
