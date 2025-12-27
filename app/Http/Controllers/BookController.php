<?php

namespace App\Http\Controllers;

use App\Exports\BookExport;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('user');
        if ($request->filled('jenis_koleksi')) {
            $query->where('jenis_koleksi', $request->jenis_koleksi);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        $data = $query->get();
        $tahun = Book::pluck('tahun')->unique()->sort()->values();

        return view('book.index', compact('data', 'tahun'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new BookExport($request),
            'data-buku.xlsx'
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $book_code = date('YmdHis');
        $data = [
            'id_buku' => $book_code,
            'judul' => $request->input('judul'),
            'jenis_koleksi' => $request->input('jenis_koleksi'),
            'media' => $request->input('media'),
            'pengarang' => $request->input('pengarang'),
            'penerbit' => $request->input('penerbit'),
            'tahun' => $request->input('tahun'),
            'cetakan' => $request->input('cetakan'),
            'edisi' => $request->input('edisi'),
            'status' => 'Ada',
            'user_id' => Auth::user()->id
        ];

        Book::create($data);
        return redirect()
            ->route('book.index')
            ->with('message_insert', 'Data Sudah ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Book::findOrFail($id);
        return view('book.index')->with([
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Book::findOrFail($id);
        return view('book.edit')->with([
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'judul' => $request->input('judul'),
            'jenis_koleksi' => $request->input('jenis_koleksi'),
            'media' => $request->input('media'),
            'pengarang' => $request->input('pengarang'),
            'penerbit' => $request->input('penerbit'),
            'tahun' => $request->input('tahun'),
            'cetakan' => $request->input('cetakan'),
            'edisi' => $request->input('edisi'),
            'user_id' => Auth::user()->id
        ];

        $datas = Book::findOrFail($id);
        $datas->update($data);
        return redirect()
            ->route('book.index')
            ->with('message_update', 'Data Sudah diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Book::findOrFail($id);
        $data->delete();
        return back()->with('message_delete', 'Data Sudah dihapus');
    }

    public function status(string $id)
    {
        Book::findOrFail($id)->update([
            'status' => 'Rusak'
        ]);

        return redirect()
            ->route('book.index')
            ->with('success', 'Status berhasil diupdate');
    }
}
