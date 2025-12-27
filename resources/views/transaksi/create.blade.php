<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('transaction.store') }}" method="POST">
                    @csrf

                    {{-- ================= ANGGOTA & PETUGAS ================= --}}
                    <div class="flex gap-5 mb-5">
                        <div class="w-full relative">
                            <label class="block mb-2 text-sm font-medium">Nama Anggota</label>
                            <input type="text" id="anggota"
                                class="rounded-lg w-full border-gray-300"
                                placeholder="Ketik nama anggota..." autocomplete="off">
                            <input type="hidden" name="user_id" id="user_id">
                            <ul id="result"
                                class="absolute z-10 bg-white w-full border rounded-lg mt-1 hidden max-h-48 overflow-y-auto"></ul>
                        </div>

                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium">Petugas</label>
                            <input type="text" value="{{ Auth::user()->name }}"
                                class="rounded-lg w-full border-gray-300" readonly>
                            <input type="hidden" name="petugas_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id_kebijakan" value="{{ $kebijakan->id }}">
                        </div>
                    </div>

                    {{-- ================= TANGGAL ================= --}}
                    <div class="flex gap-5 mb-5">
                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium">Tanggal Pinjam</label>
                            <input type="date" id="tgl_pinjam" name="tgl_pinjam"
                                value="{{ date('Y-m-d') }}"
                                class="rounded-lg w-full border-gray-300">
                        </div>

                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium">Tanggal Batas</label>
                            <input type="date" id="tgl_batas" name="tgl_batas"
                                class="rounded-lg w-full border-gray-300 bg-gray-100" readonly>
                        </div>
                    </div>

                    <hr class="my-5">

                    {{-- ================= BUKU ================= --}}
                    <label class="block mb-2 text-sm font-medium">
                        Buku Dipinjam (Max 2)
                    </label>

                    <div id="buku-wrapper" class="space-y-4">
                        {{-- ROW PERTAMA --}}
                        <div class="flex gap-5 buku-row">
                            <div class="w-full relative">
                                <input type="text"
                                    class="judul rounded-lg w-full border-gray-300"
                                    placeholder="Ketik Judul Buku..." autocomplete="off">
                                <input type="hidden" name="buku[0][id_buku]" class="id_buku">
                                <ul
                                    class="resultBuku absolute z-10 bg-white w-full border rounded-lg mt-1 hidden max-h-48 overflow-y-auto"></ul>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btnTambahBuku"
                        class="mt-3 text-sm text-sky-600 hover:underline">
                        + Tambah Buku
                    </button>

                    <div class="mt-6">
                        <button type="submit"
                            class="bg-sky-500 text-white px-4 py-2 rounded-lg">
                            Submit
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

{{-- ================= SCRIPT ================= --}}
<script>
/* ===== KONSTANTA ===== */
const MAX_TOTAL = 2;
const MAX_HARI = {{ $kebijakan->max_waktu_peminjaman }};
const btnTambah = document.getElementById('btnTambahBuku');
const wrapper = document.getElementById('buku-wrapper');

/* ===== TANGGAL BATAS ===== */
const tglPinjam = document.getElementById('tgl_pinjam');
const tglBatas = document.getElementById('tgl_batas');

function hitungTanggalBatas() {
    const d = new Date(tglPinjam.value);
    d.setDate(d.getDate() + MAX_HARI);
    tglBatas.value = d.toISOString().slice(0, 10);
}
hitungTanggalBatas();
tglPinjam.addEventListener('change', hitungTanggalBatas);

/* ===== AUTOCOMPLETE ANGGOTA ===== */
const anggota = document.getElementById('anggota');
const result = document.getElementById('result');
const userId = document.getElementById('user_id');

anggota.addEventListener('keyup', async () => {
    const q = anggota.value.trim();
    if (q.length < 2) return result.classList.add('hidden');

    const res = await fetch(`{{ route('transaction.search.anggota') }}?q=${q}`);
    const data = await res.json();

    result.innerHTML = '';
    data.forEach(u => {
        const li = document.createElement('li');
        li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
        li.innerHTML = `${u.name} <span class="text-sm text-gray-500">(ID:${u.id})</span>`;
        li.onclick = () => {
            anggota.value = u.name;
            userId.value = u.id;
            result.classList.add('hidden');
        };
        result.appendChild(li);
    });
    result.classList.remove('hidden');
});

/* ===== AUTOCOMPLETE BUKU ===== */
function setupAutocomplete(row) {
    const input = row.querySelector('.judul');
    const result = row.querySelector('.resultBuku');
    const idBuku = row.querySelector('.id_buku');

    input.addEventListener('keyup', async () => {
        const q = input.value.trim();
        if (q.length < 2) return result.classList.add('hidden');

        const res = await fetch(`{{ route('transaction.search.buku') }}?q=${q}`);
        const data = await res.json();

        result.innerHTML = '';
        data.forEach(b => {
            const li = document.createElement('li');
            li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
            li.innerHTML = `${b.judul} <span class="text-sm text-gray-500">(ID:${b.id_buku})</span>`;
            li.onclick = () => {
                input.value = b.judul;
                idBuku.value = b.id;
                result.classList.add('hidden');
            };
            result.appendChild(li);
        });
        result.classList.remove('hidden');
    });
}
setupAutocomplete(document.querySelector('.buku-row'));

/* ===== TAMBAH & HAPUS ROW ===== */
btnTambah.addEventListener('click', () => {
    if (wrapper.children.length >= MAX_TOTAL) return;

    const index = wrapper.children.length;
    const row = document.createElement('div');
    row.className = 'flex gap-5 buku-row';
    row.innerHTML = `
        <div class="w-full relative">
            <input type="text" class="judul rounded-lg w-full border-gray-300"
                placeholder="Ketik Judul Buku..." autocomplete="off">
            <input type="hidden" name="buku[${index}][id_buku]" class="id_buku">
            <ul class="resultBuku absolute z-10 bg-white w-full border rounded-lg mt-1 hidden max-h-48 overflow-y-auto"></ul>
        </div>

        <button type="button" onclick="hapusRow(this)"
            class="text-red-500 text-sm mt-2">✕</button>
    `;
    wrapper.appendChild(row);
    setupAutocomplete(row);
    cekLimit();
});

function hapusRow(btn) {
    btn.closest('.buku-row').remove();
    cekLimit();
}

function cekLimit() {
    btnTambah.disabled = wrapper.children.length >= MAX_TOTAL;
}
</script>
</x-app-layout>
