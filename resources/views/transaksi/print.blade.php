<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faktur Peminjaman</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="flex h-screen items-center justify-center bg-gray-100">
    <div class="bg-white shadow">
        <div>Peminjaman</div>
        <hr>
        <table>
            <tr>
                <td>Kode Peminjaman</td>
                <td>:</td>
                <td>{{ $data->transaksi_kode }}</td>
            </tr>
            <tr>
                <td>Anggota</td>
                <td>:</td>
                <td>{{ $data->anggota->name }}</td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>:</td>
                <td>{{ $data->petugas->name }}</td>
            </tr>
            <tr>
                <td>Tanggal Peminjaman</td>
                <td>:</td>
                <td>{{ date('d-m-Y', strtotime($data->tgl_pinjam)) }}</td>
            </tr>
            <tr>
                <td>Tanggal Batas Pengembalian</td>
                <td>:</td>
                <td>{{ date('d-m-Y', strtotime($data->tgl_kembali)) }}</td>
            </tr>
        </table>
        <hr>
        <table>
            @foreach ($detail as $d)
                <tr>
                    <td>{{ $d->book->judul }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>

</html>
