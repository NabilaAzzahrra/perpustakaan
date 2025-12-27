<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex">
                        <div>Form Edit Data Buku</div>
                    </div>
                    <div>
                        <form class="" action="{{ route('book.update', $data->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="flex gap-5">
                                <div class="mb-5 w-full">
                                    <label for="judul"
                                        class="block mb-2.5 text-sm font-medium text-heading">Judul</label>
                                    <input type="text" id="judul" name="judul" value="{{ $data->judul }}"
                                        class="rounded-lg w-full border-gray-300" placeholder="Judul" required />
                                </div>
                                <div class="mb-5 w-full">
                                    <label for="jenis_koleksi" class="block mb-2 text-sm font-medium text-heading">
                                        Jenis Koleksi <span class="text-red-500">*</span>
                                    </label>
                                    <select id="jenis_koleksi" name="jenis_koleksi"
                                        class="js-example-placeholder-single bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
                                        required>
                                        <option value="">Pilih...</option>
                                        <option value="FIKSI"
                                            {{ old('jenis_koleksi', $data->jenis_koleksi) == 'FIKSI' ? 'selected' : '' }}>
                                            FIKSI
                                        </option>
                                        <option value="NON FIKSI"
                                            {{ old('jenis_koleksi', $data->jenis_koleksi) == 'NON FIKSI' ? 'selected' : '' }}>
                                            NON FIKSI
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex gap-5">
                                <div class="mb-5 w-full">
                                    <label for="media"
                                        class="block mb-2.5 text-sm font-medium text-heading">Media</label>
                                    <input type="text" id="media" name="media" value="{{ $data->media }}"
                                        class="rounded-lg w-full border-gray-300" placeholder="Media" required />
                                </div>
                                <div class="mb-5 w-full">
                                    <label for="pengarang"
                                        class="block mb-2.5 text-sm font-medium text-heading">Pengarang</label>
                                    <input type="text" id="pengarang" name="pengarang" value="{{ $data->pengarang }}"
                                        class="rounded-lg w-full border-gray-300" placeholder="Pengarang" required />
                                </div>
                            </div>
                            <div class="flex gap-5">
                                <div class="mb-5 w-full">
                                    <label for="penerbit"
                                        class="block mb-2.5 text-sm font-medium text-heading">Penerbit</label>
                                    <input type="text" id="penerbit" name="penerbit" value="{{ $data->penerbit }}"
                                        class="rounded-lg w-full border-gray-300" placeholder="Penerbit" required />
                                </div>
                                <div class="mb-5 w-full">
                                    <label for="tahun"
                                        class="block mb-2.5 text-sm font-medium text-heading">Tahun</label>
                                    <input type="number" id="tahun" name="tahun" value="{{ $data->tahun }}"
                                        class="rounded-lg w-full border-gray-300" placeholder="Tahun" required />
                                </div>
                            </div>
                            <div class="flex gap-5">
                                <div class="mb-5 w-full">
                                    <label for="cetakan"
                                        class="block mb-2.5 text-sm font-medium text-heading">Cetakan</label>
                                    <input type="text" id="cetakan" name="cetakan" value="{{ $data->cetakan }}"
                                        class="rounded-lg w-full border-gray-300" placeholder="Cetakan" required />
                                </div>
                                <div class="mb-5 w-full">
                                    <label for="edisi"
                                        class="block mb-2.5 text-sm font-medium text-heading">Edisi</label>
                                    <input type="text" id="edisi" name="edisi" value="{{ $data->edisi }}"
                                        class="rounded-lg w-full border-gray-300" placeholder="Edisi" required />
                                </div>
                            </div>
                            <button type="submit"
                                class="text-white bg-sky-500 box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
