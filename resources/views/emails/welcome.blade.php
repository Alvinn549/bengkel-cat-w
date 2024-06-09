<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #0073e6;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Selamat Datang di Bengkel Cat Wijayanto</h2>
        <p>Halo {{ $name }},</p>
        <p>Kami sangat senang memiliki Anda bersama kami. Kami berharap Anda memiliki pengalaman yang luar biasa dengan
            layanan kami.</p>
        <h5><a href="{{ route('login') }}">Login Sekarang</a></h5>
        <p>Terima kasih,</p>
        <p>-Tim Bengkel Cat Wijayanto</p>
        <div class="footer">
            &copy; 2024 Bengkel Cat Wijayanto. All rights reserved.
        </div>
    </div>
</body>

</html>
