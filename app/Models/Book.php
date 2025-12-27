<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_buku',
        'judul',
        'jenis_koleksi',
        'media',
        'pengarang',
        'penerbit',
        'tahun',
        'cetakan',
        'edisi',
        'status',
        'user_id'
    ];

    protected $table = 'book';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_buku', 'id');
    }
}
