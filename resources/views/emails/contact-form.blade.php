<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
        }

        p {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .strong {
            font-weight: bold;
            color: #333;
        }

        .signature {
            margin-top: 20px;
            font-style: italic;
            color: #777;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>New Contact Form Submission</h2>
        <p><span class="strong">Name:</span> {{ $name }}</p>
        <p><span class="strong">Email:</span> {{ $email }}</p>
        <p><span class="strong">Message:</span> {{ $pesan }}</p>
    </div>
    <div class="footer">
        &copy; <?php echo date('Y'); ?> Bengkel Cat Wijayanto. Semua hak dilindungi.
    </div>
</body>

</html>
