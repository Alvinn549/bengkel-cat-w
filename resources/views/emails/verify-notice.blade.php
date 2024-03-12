<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Email Verification</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/dashboard/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/dashboard/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Custom Styles -->
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .verification-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
        }

        .verification-image {
            margin-bottom: 20px;
        }

        .verification-image img {
            width: 200px;
            height: auto;
        }

        .verification-text {
            margin-top: 25px;
            margin-bottom: 25px;
            color: #6c757d;
        }

        .verification-button button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .verification-button button:hover {
            background-color: #0056b3;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="verification-container">
            <div class="verification-image">
                <img src="{{ asset('assets/landing/img/verification.png') }}" alt="Verification Image">
            </div>
            <div class="verification-text">
                <p>Before proceeding, please check your email for a verification link. If you did not receive the email,
                    click the button below to request another.</p>
            </div>
            <div class="verification-button">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit">Request Another</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
