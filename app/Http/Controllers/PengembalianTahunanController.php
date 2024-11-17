<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Bukucrud;
use Illuminate\Http\Request;
use App\Models\PeminjamanTahunan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PengembalianTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $iduser = Auth::id();
        $profile = User::where('id', $iduser)->first();

        $keyword = $request->input('search');
        if ($request->has('search')) {
            $pengembaliantahunan = PeminjamanTahunan::whereHas('siswas', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })->orWhereHas('siswas', function ($query) use ($keyword) {
                $query->where('kelas', 'like', '%' . $keyword . '%');
            })->get();
        } else {
            $pengembaliantahunan = PeminjamanTahunan::latest()->paginate(35);
        }
        return view('pengembaliantahunan.index', compact('pengembaliantahunan', 'profile'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 
    }

    public function status($id)
    {
        // Temukan peminjaman berdasarkan id
        $pengembaliantahunan = PeminjamanTahunan::with('bukus.bukucruds')->find($id);

        if (!$pengembaliantahunan) {
            return response()->json(['message' => 'Peminjaman tidak ditemukan'], 404);
        }

        // Update status peminjaman menjadi 0 (dikembalikan)
        $pengembaliantahunan->status = 0;
        $pengembaliantahunan->save();

        // Tambahkan stok buku sesuai dengan jumlah yang dipinjam
        foreach ($pengembaliantahunan->bukus as $buku) {
            if ($buku->bukucruds) {
                $buku->bukucruds->stok += $buku->jml_buku;  // Asumsi 'jumlah' ada di model Buku
                $buku->bukucruds->save();
            }
        }
        return redirect()->route('pengembaliantahunan', compact('pengembaliantahunan'))->with('success', 'Peminjaman selesai successfully');
    }


    public function view_pdf()
    {
        $pengembaliantahunan = PeminjamanTahunan::orderBy('name', 'ASC')->get();
        $pdf = Pdf::loadView('pengembaliantahunan.pdf', ['pengembaliantahunan' => $pengembaliantahunan]);
        return $pdf->stream('data-perpustakaan.pdf');
    }
}
