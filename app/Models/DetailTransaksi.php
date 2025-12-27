<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_code',
        'id_buku',
        'tgl_kembali',
        'total_denda',
        'status_buku',
    ];

    protected $table='detail_transaksis';

    public function book()
    {
        return $this->belongsTo(Book::class, 'id_buku', 'id');
    }
}
