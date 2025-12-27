<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Kebijakan;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\User;
use App\Exports\TransactionExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['anggota', 'petugas'])
            ->withCount('detailTransaksi');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $data = $query->get();
        return view('transaksi.index')->with([
            'data' => $data,
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(
            new TransactionExport($request),
            'data-transaction.xlsx'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Transaksi::all();
        $anggota = User::where('role', 'U')->get();
        $kebijakan = Kebijakan::first();
        $buku = Book::all();
        return view('transaksi.create')->with([
            'data' => $data,
            'anggota' => $anggota,
            'kebijakan' => $kebijakan,
            'buku' => $buku,
        ]);
    }

    public function searchAnggota(Request $request)
    {
        return User::where('role', 'U')
            ->where('name', 'like', '%' . $request->q . '%')
            ->limit(10)
            ->get(['id', 'name']);
    }

    public function searchBuku(Request $request)
    {
        return Book::where('judul', 'like', '%' . $request->q . '%')
            ->where('status', 'Ada')
            ->limit(10)
            ->get(['id', 'judul', 'id_buku']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $transaksi = Transaksi::create([
                'transaksi_kode' => date('YmdHis'),
                'id_anggota' => $request->user_id,
                'id_petugas' => $request->petugas_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_batas' => $request->tgl_batas,
                'id_kebijakan' => $request->id_kebijakan,
                'status' => 'Aktif',
            ]);

            foreach ($request->buku as $item) {

                $buku = Book::lockForUpdate()->findOrFail($item['id_buku']);

                if ($buku->status === 'Dipinjam') {
                    throw new \Exception("Buku {$buku->judul} sedang dipinjam");
                }

                DetailTransaksi::create([
                    'transaksi_code' => $transaksi->transaksi_kode,
                    'id_buku' => $item['id_buku'],
                    'total_denda' => 0,
                    'status_buku' => 'Dipinjam',
                ]);

                $buku->update([
                    'status' => 'Dipinjam'
                ]);
            }

            DB::commit();

            return redirect()
                ->route('transaction.index')
                ->with('message_insert', 'Transaksi berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = Transaksi::with(['anggota', 'petugas'])
            ->withCount('detailTransaksi')
            ->where('id', $id);
        $data = $query->first();
        $transaksiCode = $data->transaksi_kode;
        $detail = DetailTransaksi::where('transaksi_code', $transaksiCode)->get();
        $kebijakan = Kebijakan::first();
        return view('transaksi.detail')->with([
            'data' => $data,
            'detail' => $detail,
            'kebijakan' => $kebijakan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $query = Transaksi::with(['anggota', 'petugas'])
            ->withCount('detailTransaksi')
            ->where('id', $id);
        $data = $query->first();
        $transaksiCode = $data->transaksi_kode;
        $detail = DetailTransaksi::where('transaksi_code', $transaksiCode)->get();
        $kebijakan = Kebijakan::first();
        return view('transaksi.print')->with([
            'data' => $data,
            'detail' => $detail,
            'kebijakan' => $kebijakan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // update detail transaksi + status buku
        foreach ($request->detail_id as $i => $detailId) {

            $tglKembali = $request->tgl_kembali[$i];

            // update detail transaksi
            DetailTransaksi::where('id', $detailId)->update([
                'tgl_kembali' => $tglKembali,
                'total_denda' => $request->total_denda[$i],
                'status_buku' => 'Ada',
            ]);

            // 👉 JIKA buku sudah dikembalikan → status buku = Ada
            if (!empty($tglKembali)) {
                $detail = DetailTransaksi::find($detailId);

                Book::where('id', $detail->id_buku)->update([
                    'status' => 'Ada',
                ]);
            }
        }

        $transaksi = Transaksi::findOrFail($id);
        $kode = $transaksi->transaksi_kode;

        // 👉 CEK: masih ada buku yang BELUM dikembalikan?
        $masihDipinjam = DetailTransaksi::where('transaksi_code', $kode)
            ->whereNull('tgl_kembali')
            ->exists();

        // update status transaksi hanya jika semua sudah kembali
        if (!$masihDipinjam) {
            Transaksi::where('id', $id)->update([
                'status' => 'Selesai',
            ]);
        }

        return redirect()
            ->route('transaction.index')
            ->with('success', 'Pengembalian berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {

            $transaksi = Transaksi::findOrFail($id);
            $details = DetailTransaksi::where('transaksi_code', $transaksi->transaksi_kode)->get();
            foreach ($details as $detail) {
                Book::where('id', $detail->id_buku)->update([
                    'status' => 'Ada',
                ]);
            }
            DetailTransaksi::where('transaksi_code', $transaksi->transaksi_kode)->delete();
            $transaksi->delete();
        });

        return back()->with('message_delete', 'Data sudah dihapus');
    }
}
