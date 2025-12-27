<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- DATA TRANSAKSI --}}
                <h3 class="font-bold mb-2">Data Pinjaman</h3>
                <table class="mb-6">
                    <tr><td>Kode</td><td>:</td><td>{{ $data->transaksi_kode }}</td></tr>
                    <tr><td>Anggota</td><td>:</td><td>{{ $data->anggota->name }}</td></tr>
                    <tr><td>Petugas</td><td>:</td><td>{{ $data->petugas->name }}</td></tr>
                    <tr><td>Tgl Pinjam</td><td>:</td><td>{{ date('d-m-Y', strtotime($data->tgl_pinjam)) }}</td></tr>
                    <tr><td>Tgl Batas</td><td>:</td><td>{{ date('d-m-Y', strtotime($data->tgl_batas)) }}</td></tr>
                    <tr><td>Status</td><td>:</td><td>{{ $data->status }}</td></tr>
                </table>

                {{-- DATA BUKU --}}
                <h3 class="font-bold mb-2">Data Buku</h3>

                <form action="{{ route('transaction.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <table class="w-full border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th>Judul</th>
                                <th>Kode Buku</th>
                                <th>Tanggal Kembali</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail as $d)
                                @php
                                    $total_denda = 0;
                                    if ($d->tgl_kembali === null) {
                                        $hariTerlambat =
                                            (strtotime(date('Y-m-d')) - strtotime($data->tgl_batas)) / 86400;
                                        if ($hariTerlambat > 0) {
                                            $total_denda = floor($hariTerlambat) * $kebijakan->denda;
                                        }
                                    }else{
                                        $total_denda = $d->total_denda;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $d->book->judul }}</td>
                                    <td>{{ $d->book->id_buku }}</td>

                                    <td>
                                        <input type="hidden" name="detail_id[]" value="{{ $d->id }}">

                                        <input type="date"
                                            name="tgl_kembali[]"
                                            value="{{ $d->tgl_kembali }}"
                                            class="tgl-kembali rounded border w-full px-2 py-1">
                                    </td>

                                    <td>
                                        <input type="number"
                                            name="total_denda[]"
                                            value="{{ $total_denda }}"
                                            readonly
                                            class="rounded border w-full px-2 py-1">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit"
                        id="btnSubmit"
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded"
                        >
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
