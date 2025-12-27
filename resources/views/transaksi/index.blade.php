<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-3xl py-4 px-6 mb-4">
                <div class="flex items-center justify-between">
                    <form action="{{ route('transaction.index') }}" method="get" class="w-full">
                        <div class="flex gap-2 w-full">
                            <div class="w-full">
                                <select name="status"
                                    class="js-example-placeholder-single js-states form-control w-full m-6">
                                    <option value="">Pilih...</option>
                                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>
                                        AKTIF
                                    </option>
                                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>
                                        SELESAI
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="border px-4 pb-2 pt-2 rounded-xl text-gray-500">
                                Filter
                            </button>

                            <a href="{{ route('transaction.index') }}"
                                class="border px-4 pb-2 pt-2 rounded-xl text-gray-500">
                                Reset
                            </a>
                        </div>
                    </form>
                    <div class="mt-2 w-full flex justify-end gap-2">
                        <a href="{{ route('transaction.export', request()->query()) }}"
                            class="border px-4 pb-2 pt-3 rounded-xl">
                            <i class="fi fi-sr-file-excel text-gray-500"></i>
                        </a>
                        <a href="{{ route('transaction.create') }}" class="border px-4 pb-2 pt-3 rounded-xl"><i
                                class="fi fi-sr-multiple text-gray-500"></i></a>
                    </div>
                </div>
            </div>
            <div class="bg-white border dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-3xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="table-responsive">
                        <table class="datatable display table table-striped w-full">
                            <thead>
                                <tr>
                                    <th class="text-center w-10">No</th>
                                    <th class="text-center">Kode Transaksi</th>
                                    <th class="text-center">Anggota</th>
                                    <th class="text-center">Petugas</th>
                                    <th class="text-center">Tanggal Pinjam</th>
                                    <th class="text-center">Tanggal Batas Peminjaman</th>
                                    <th class="text-center">Jumlah Peminjaman</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center w-32">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp

                                @forelse($data as $i)
                                    @php
                                        $statusClass = match ($i->status) {
                                            'Selesai' => 'bg-emerald-100 text-emerald-600',
                                            'Aktif' => 'bg-amber-100 text-amber-600',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="text-center">{{ $i->transaksi_kode }}</td>
                                        <td class="text-center">{{ $i->anggota->name }}</td>
                                        <td class="text-center">{{ $i->petugas->name }}</td>
                                        <td class="text-center">{{ date('d-m-Y', strtotime($i->tgl_pinjam)) }}</td>
                                        <td class="text-center">{{ date('d-m-Y', strtotime($i->tgl_batas)) }}</td>
                                        <td class="text-center">{{ $i->detail_transaksi_count }}</td>
                                        <td class="text-center"><span
                                                class="{{ $statusClass }} px-4 text-sm py-1 rounded-full font-bold">{{ $i->status }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('transaction.show', $i->id) }}" class="mr-4"
                                                title="detail">
                                                <i class="fi fi-ss-info text-xl mt-1 text-gray-500"></i>
                                            </a>

                                            <a href="{{ route('transaction.edit', $i->id) }}" class="mr-4"
                                                title="print faktur peminjaman">
                                                <i class="fi fi-sr-print text-xl mt-1 text-gray-500"></i>
                                            </a>

                                            <button type="button"
                                                onclick="return transactionDelete('{{ $i->id }}','{{ $i->transaksi_kode }}')"
                                                class="mr-3" title="delete">
                                                <i class="fi fi-sr-trash text-xl mt-1 text-gray-500"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="bg-gray-500 text-white p-3 rounded shadow-sm mb-3">
                                        Data Belum Tersedia!
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const transactionDelete = async (id, transaksi_kode) => {
            Swal.fire({
                title: `Apakah Anda yakin?`,
                text: `Data akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await axios.post(`/transaction/${id}`, {
                            '_method': 'DELETE',
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        })
                        .then(function(response) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: `Data ${transaksi_kode} berhasil dihapus.`,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            }).then(() => {
                                // Refresh halaman setelah menekan tombol OK
                                location.reload();
                            });
                        })
                        .catch(function(error) {
                            // Alert jika terjadi kesalahan
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            console.log(error);
                        });
                }
            });
        };
    </script>
</x-app-layout>
