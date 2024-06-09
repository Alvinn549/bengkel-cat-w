<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 2px solid #ffcc00;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #ff9900;
            text-align: center;
        }

        p {
            font-size: 16px;
            line-height: 1.8;
        }

        .highlight {
            color: #ff9900;
            font-weight: bold;
        }

        .confetti {
            font-size: 50px;
            text-align: center;
            line-height: 1;
            margin: 20px 0;
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
            width: 250px;
            margin: 20px auto;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #ff9900;
            border-radius: 30px;
            font-size: 18px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }

        .footer img {
            width: 30px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Transaksi Anda Sudah Beres! 🎉</h2>
        <p>Halo <span class="highlight">{{ $transaksi->pelanggan->nama }}</span>,</p>
        <p>
            Kami dengan senang hati menginformasikan bahwa transaksi Anda untuk perbaikan kendaraan dengan kode
            <span class="highlight">{{ $transaksi->perbaikan->kode_unik }}</span> telah <span
                class="highlight">selesai</span> dan sudah
            <span class="highlight">lunas</span>.
        </p>
        <div class="confetti">🎉🎉🎉</div>
        <p>Terima kasih telah mempercayakan kendaraan Anda kepada kami. Kami berharap Anda puas dengan layanan kami.</p>
        <p>Berikut adalah ringkasan transaksi Anda:</p>
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
                <td>{{ $transaksi->gross_amount }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ $transaksi->created_at }}</td>
            </tr>
        </table>
        <a href="{{ route('dashboard.pelanggan.history-perbaikan-detail', $transaksi->perbaikan->id) }}" class="button"
            style="color: white;">Lihat Detail</a>
        <p>Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami. Kami selalu siap membantu!
        </p>
        <p>Salam,</p>
        <p>-Tim Bengkel Cat Wijayanto</p>
        <div class="footer">
            &copy; <?php echo date('Y'); ?> Bengkel Cat Wijayanto. Semua hak dilindungi. <br>
            <img src="{{ public_path('assets/letter-w.png') }}" alt="Bengkel Cat Wijayanto">
        </div>
    </div>
</body>

</html>
