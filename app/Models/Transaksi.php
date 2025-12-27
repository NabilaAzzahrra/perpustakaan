<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_kode',
        'id_anggota',
        'id_petugas',
        'tgl_pinjam',
        'tgl_batas',
        'tgl_kembali',
        'id_buku',
        'id_kebijakan',
        'total_denda',
        'status',
    ];

    protected $table = 'transaksis';

    public function anggota()
    {
        return $this->belongsTo(User::class, 'id_anggota', 'id');
    }
    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas', 'id');
    }
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_code', 'transaksi_kode');
    }
}
