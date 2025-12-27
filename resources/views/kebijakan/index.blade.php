<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border dark:bg-gray-800 overflow-x-auto shadow-sm sm:rounded-3xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="table-responsive">
                        <table class="datatable display table table-striped w-full">
                            <thead>
                                <tr>
                                    <th class="text-center">Maksimal Waktu Peminjaman</th>
                                    <th class="text-center">Maximal Jumlah Buku</th>
                                    <th class="text-center">Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- MAX WAKTU --}}
                                    <td class="text-center">
                                        <span onclick="editField(this)" class="cursor-pointer">
                                            {{ $data->max_waktu_peminjaman }}
                                        </span>
                                        <form action="{{ route('kebijakan.update', $data->id) }}" method="POST"
                                            class="hidden inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="max_waktu_peminjaman"
                                                value="{{ $data->max_waktu_peminjaman }}"
                                                class="border rounded px-2 py-1 w-20" onblur="this.form.submit()">
                                        </form>
                                    </td>

                                    {{-- MAX JUMLAH --}}
                                    <td class="text-center">
                                        <span onclick="editField(this)" class="cursor-pointer">
                                            {{ $data->max_jml_buku }}
                                        </span>
                                        <form action="{{ route('kebijakan.update', $data->id) }}" method="POST"
                                            class="hidden inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="max_jml_buku" value="{{ $data->max_jml_buku }}"
                                                class="border rounded px-2 py-1 w-20" onblur="this.form.submit()">
                                        </form>
                                    </td>

                                    {{-- DENDA --}}
                                    <td class="text-center">
                                        <span onclick="editField(this)" class="cursor-pointer">
                                            {{ number_format($data->denda) }}
                                        </span>
                                        <form action="{{ route('kebijakan.update', $data->id) }}" method="POST"
                                            class="hidden inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="denda" value="{{ $data->denda }}"
                                                class="border rounded px-2 py-1 w-24" onblur="this.form.submit()">
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function editField(el) {
            const form = el.nextElementSibling;
            el.classList.add('hidden');
            form.classList.remove('hidden');
            form.querySelector('input').focus();
        }
    </script>

</x-app-layout>
