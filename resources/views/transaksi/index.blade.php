<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-3xl py-4 px-6 mb-4">
                <div class="flex items-center justify-between">
                    <form action="{{ route('book.index') }}" method="get" class="w-full">
                        <div class="flex gap-2 w-full">
                            <div class="w-full">
                                <select name="jenis_koleksi"
                                    class="js-example-placeholder-single js-states form-control w-full m-6">
                                    <option value="">Pilih...</option>
                                    <option value="FIKSI" {{ request('jenis_koleksi') == 'FIKSI' ? 'selected' : '' }}>
                                        FIKSI
                                    </option>
                                    <option value="NON FIKSI"
                                        {{ request('jenis_koleksi') == 'NON FIKSI' ? 'selected' : '' }}>
                                        NON FIKSI
                                    </option>
                                </select>
                            </div>
                            <div class="w-full">
                                <select name="status"
                                    class="js-example-placeholder-single js-states form-control w-full m-6">
                                    <option value="">Pilih...</option>
                                    <option value="Ada" {{ request('status') == 'Ada' ? 'selected' : '' }}>
                                        ADA
                                    </option>
                                    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>
                                        DIPINJAM
                                    </option>
                                    <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>
                                        RUSAK
                                    </option>
                                </select>
                            </div>
                            {{-- <div class="w-full">
                                <select class="js-example-placeholder-single js-states form-control w-full m-6"
                                    id="tahun" name="tahun" data-placeholder="Tahun">
                                    <option value="">Pilih...</option>
                                    @foreach ($tahun as $t)
                                        <option value="{{ $t }}"
                                            {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <button type="submit" class="border px-4 pb-2 pt-2 rounded-xl text-gray-500">
                                Filter
                            </button>

                            <a href="{{ route('book.index') }}" class="border px-4 pb-2 pt-2 rounded-xl text-gray-500">
                                Reset
                            </a>
                        </div>
                    </form>
                    <div class="mt-2 w-full flex justify-end gap-2">
                        <a href="{{ route('book.export', request()->query()) }}"
                            class="border px-4 pb-2 pt-3 rounded-xl">
                            <i class="fi fi-sr-file-excel text-gray-500"></i>
                        </a>
                        <a href="{{ route('book.create') }}" class="border px-4 pb-2 pt-3 rounded-xl"><i
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
                                    <th class="text-center">Buku</th>
                                    <th class="text-center">Tanggal Pinjam</th>
                                    <th class="text-center">Tanggal Batas Peminjaman</th>
                                    <th class="text-center">Tanggal Kembali</th>
                                    <th class="text-center">Total Denda</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center w-32">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp

                                @forelse($data as $i)
                                    @php
                                        $statusClass = match ($i->status) {
                                            'Ada' => 'bg-emerald-100 text-emerald-600',
                                            'Dipinjam' => 'bg-amber-100 text-amber-600',
                                            'Rusak' => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="text-center">{{ $i->id_buku }}</td>
                                        <td class="text-center">{{ $i->judul }}</td>
                                        <td class="text-center">{{ $i->jenis_koleksi }}</td>
                                        <td class="text-center">{{ $i->media }}</td>
                                        <td class="text-center">{{ $i->pengarang }}</td>
                                        <td class="text-center">{{ $i->penerbit }}</td>
                                        <td class="text-center">{{ $i->tahun }}</td>
                                        <td class="text-center"><span
                                                class="{{ $statusClass }} px-4 text-sm py-1 rounded-full font-bold">{{ $i->status }}</span>
                                        </td>
                                        <td class="text-center"><span
                                                class="bg-red-100 text-red-500 px-4 text-sm pt-1 rounded-full font-bold flex items-center justify-center">
                                                <i class="fi fi-sr-user mr-1 text-xs"></i> {{ $i->user->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('book.edit', $i->id) }}" class="mr-4" title="edit">
                                                <i class="fi fi-sr-pen-square text-xl mt-1 text-gray-500"></i>
                                            </a>

                                            <button type="button"
                                                onclick="return bookDelete('{{ $i->id }}','{{ $i->judul }}')"
                                                class="mr-3" title="delete">
                                                <i class="fi fi-sr-trash text-xl mt-1 text-gray-500"></i>
                                            </button>

                                            <button type="button"
                                                onclick="confirmStatus({{ $i->id }}, '{{ $i->judul }}')"
                                                title="update status">
                                                <i class="fi fi-sr-minus-circle text-xl mt-1 text-gray-500"></i>
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
    {{-- <form id="status-form-{{ $i->id }}" action="{{ route('book.status', $i->id) }}" method="POST"
        class="hidden">
        @csrf
        @method('PATCH')
    </form> --}}
    <script>
        const bookDelete = async (id, judul) => {
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
                    await axios.post(`/book/${id}`, {
                            '_method': 'DELETE',
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        })
                        .then(function(response) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: `Data ${judul} berhasil dihapus.`,
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

        function confirmStatus(id, judul) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Status "${judul}" akan diupdate`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, update',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('status-form-' + id).submit();
                }
            });
        }
    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}'
            });
        </script>
    @endif
</x-app-layout>

