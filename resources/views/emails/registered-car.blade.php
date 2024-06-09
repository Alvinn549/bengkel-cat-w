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
        <h2>Kendaraan Anda Telah Terdaftar</h2>
        <p>Halo {{ $kendaraan->pelanggan->nama }},</p>
        <p>Kendaraan Anda telah berhasil terdaftar dengan detail berikut:</p>
        <ul>
            <li><strong>Nomor Plat:</strong> {{ $kendaraan->no_plat }}</li>
            <li><strong>Tipe:</strong> {{ $kendaraan->tipe->nama_tipe }}</li>
            <li><strong>Merek:</strong> {{ $kendaraan->merek->nama_merek }}</li>
            <li><strong>Tanggal:</strong> {{ $kendaraan->created_at }}</li>
        </ul>
        <p>Terima kasih telah mendaftarkan kendaraan Anda di Bengkel Cat Wijayanto.</p>
        <p>Salam,</p>
        <p>-Tim Bengkel Cat Wijayanto</p>
        <div class="footer">
            &copy; <?php echo date('Y'); ?> Bengkel Cat Wijayanto. Semua hak dilindungi.
        </div>
    </div>
</body>

</html>
