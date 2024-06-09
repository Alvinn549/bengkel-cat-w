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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #0073e6;
            border-radius: 5px;
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
        <h2>Status Perbaikan Berubah</h2>
        <p>Halo {{ $perbaikan->kendaraan->pelanggan->nama }},</p>
        <p>Status perbaikan kendaraan Anda telah berubah. Berikut adalah detail terbaru perbaikan Anda:</p>
        <table>
            <tr>
                <th>Kode</th>
                <td>{{ $perbaikan->kode_unik }}</td>
            </tr>
            <tr>
                <th>Nomor Plat Kendaraan</th>
                <td>{{ $perbaikan->kendaraan->no_plat }}</td>
            </tr>
            <tr>
                <th>Nama Perbaikan</th>
                <td>{{ $perbaikan->nama }}</td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $perbaikan->keterangan }}</td>
            </tr>
            <tr>
                <th>Estimasi Durasi</th>
                <td>{{ $perbaikan->durasi }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $perbaikan->status }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ $perbaikan->updated_at }}</td>
            </tr>
        </table>
        <a href="{{ route('home.detail-perbaikan', $perbaikan->id) }}" class="button" style="color: white;">Lihat
            Detail</a>
        <p>Terima kasih telah mempercayakan perbaikan kendaraan Anda pada Bengkel Cat Wijayanto. Jika Anda memiliki
            pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.</p>
        <p>Salam,</p>
        <p>- Tim Bengkel Cat Wijayanto</p>
        <div class="footer">
            &copy; <?php echo date('Y'); ?> Bengkel Cat Wijayanto. Semua hak dilindungi.
        </div>
    </div>
</body>

</html>
