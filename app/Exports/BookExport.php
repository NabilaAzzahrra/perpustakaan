<?php

namespace App\Exports;

use App\Models\Book;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Book::with('user');

        if ($this->request->filled('jenis_koleksi')) {
            $query->where('jenis_koleksi', $this->request->jenis_koleksi);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('tahun')) {
            $query->where('tahun', $this->request->tahun);
        }

        return $query->get()->map(function ($book) {
            return [
                $book->judul,
                $book->jenis_koleksi,
                $book->status,
                $book->tahun,
                $book->user->name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Judul',
            'Jenis Koleksi',
            'Status',
            'Tahun',
            'User',
        ];
    }
}
