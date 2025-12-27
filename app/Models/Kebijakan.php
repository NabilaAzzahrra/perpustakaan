<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kebijakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'max_waktu_peminjaman',
        'max_jml_buku',
        'denda',
    ];

    protected $table = 'kebijakans';
}
