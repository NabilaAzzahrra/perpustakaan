<?php

namespace App\Exports;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaksi::with(['anggota', 'petugas'])
            ->withCount('detailTransaksi');

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        return $query->get()->map(function ($transaction) {
            return [
                $transaction->transaksi_kode,
                $transaction->anggota->name,
                $transaction->petugas->name,
                date('d-m-Y', strtotime($transaction->tgl_pinjam)),
                date('d-m-Y', strtotime($transaction->tgl_batas)),
                $transaction->detail_transaksi_count,
                $transaction->status
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Anggota',
            'Petugas',
            'Tanggal Pinjam',
            'Tanggal Batas',
            'Jumlah Pinjam',
            'Status',
        ];
    }
}
