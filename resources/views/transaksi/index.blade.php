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
                    <form action="{{ route('book.index') }}" method="get" class="w-full">
                        <div class="flex gap-2 w-full">
                            <div class="mb-5 w-full relative">
                                <label class="block mb-2.5 text-sm font-medium text-heading">
                                    Nama Anggota
                                </label>

                                <input type="text" id="anggota" class="rounded-lg w-full border-gray-300"
                                    placeholder="Ketik nama anggota..." autocomplete="off" />

                                <input type="hidden" name="user_id" id="user_id">

                                <ul id="result"
                                    class="absolute z-10 bg-white w-full border rounded-lg mt-1 hidden max-h-48 overflow-y-auto">
                                </ul>
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
                                            'Ada' => 'bg-emerald-100 text-emerald-600',
                                            'Dipinjam' => 'bg-amber-100 text-amber-600',
                                            'Rusak' => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="text-center">{{ $i->judul }}</td>
                                        <td class="text-center">{{ $i->jenis_koleksi }}</td>
                                        <td class="text-center">{{ $i->media }}</td>
                                        <td class="text-center">{{ $i->pengarang }}</td>
                                        <td class="text-center">{{ $i->penerbit }}</td>
                                        <td class="text-center">{{ $i->tahun }}</td>
                                        <td class="text-center"><span
                                                class="{{ $statusClass }} px-4 text-sm py-1 rounded-full font-bold">{{ $i->status }}</span>
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
    <script>
        const input = document.getElementById('anggota');
        const result = document.getElementById('result');
        const userId = document.getElementById('user_id');

        input.addEventListener('keyup', async () => {
            const q = input.value;

            if (q.length < 2) {
                result.classList.add('hidden');
                return;
            }

            const res = await fetch(
                `{{ route('transaction.search.anggota') }}?q=${q}`
            );

            const data = await res.json();
            result.innerHTML = '';

            if (!data.length) {
                result.classList.add('hidden');
                return;
            }

            data.forEach(user => {
                const li = document.createElement('li');
                li.textContent = user.name;
                li.className = 'px-4 py-2 cursor-pointer hover:bg-gray-100';

                li.onclick = () => {
                    input.value = user.name;
                    userId.value = user.id;
                    result.classList.add('hidden');
                };

                result.appendChild(li);
            });

            result.classList.remove('hidden');
        });
    </script>

</x-app-layout>
