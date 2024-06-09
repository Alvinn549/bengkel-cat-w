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
        <h2>Perbaikan Selesai - Pembayaran Diperlukan</h2>
        <p>Halo {{ $transaksi->pelanggan->nama }},</p>
        <p>Perbaikan kendaraan Anda dengan kode <strong>{{ $transaksi->perbaikan->kode_unik }}</strong> telah selesai.
        </p>
        <p>Silakan lanjutkan pembayaran untuk menyelesaikan transaksi Anda.</p>
        <p>Berikut adalah detail transaksi Anda:</p>
        <table>
            <tr>
                <th>Nama Depan</th>
                <td>{{ $transaksi->first_name ?? '' }}</td>
            </tr>
            <tr>
                <th>Nama Belakang</th>
                <td>{{ $transaksi->last_name ?? '' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $transaksi->email }}</td>
            </tr>
            <tr>
                <th>Telepon</th>
                <td>{{ $transaksi->phone }}</td>
            </tr>
            <tr>
                <th>ID Pesanan</th>
                <td>{{ $transaksi->order_id ?? '' }}</td>
            </tr>
            <tr>
                <th>Jumlah Total</th>
                <td>Rp. {{ number_format($transaksi->gross_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ $transaksi->created_at }}</td>
            </tr>
        </table>
        <a href="{{ route('dashboard.pelanggan.my-transaksi-detail', $transaksi->id) }}" class="button"
            style="color: white;">Lihat Detail</a>
        <p>Terima kasih telah mempercayakan perbaikan kendaraan Anda pada Bengkel Cat Wijayanto. Jika Anda memiliki
            pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.</p>
        <p>Salam,</p>
        <p>Tim Bengkel Cat Wijayanto</p>
        <div class="footer">
            &copy; <?php echo date('Y'); ?> Bengkel Cat Wijayanto. Semua hak dilindungi.
        </div>
    </div>
</body>

</html>
