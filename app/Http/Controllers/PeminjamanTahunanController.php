<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Bukucrud;
use Illuminate\Http\Request;
use App\Models\PeminjamanTahunan;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanTahunanController extends Controller
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
            $peminjamantahunan = PeminjamanTahunan::whereHas('siswas', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })->orWhereHas('siswas', function ($query) use ($keyword) {
                $query->where('kelas', 'like', '%' . $keyword . '%');
            })->get();
        } else {
            $peminjamantahunan = PeminjamanTahunan::latest()->paginate(35);
        }
        return view('peminjamantahunan.index', compact('peminjamantahunan', 'profile'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $iduser = Auth::id();
        $profile = User::where('id', $iduser)->first();

        $peminjamantahunan = PeminjamanTahunan::all();
        $siswa = Siswa::all();
        return view('peminjamantahunan.create', compact('peminjamantahunan', 'siswa', 'profile'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'kls' => 'required:true',
            'absen' => 'required:true',
            'tgl' => 'required:true',
            'siswas_id' => 'required:true',
            'jam_pinjam' => 'required:true',
            'jam_kembali' => 'required:true',
        ]);

        $kode_pinjam = $request->kls . '' . $request->absen . '-' . $request->tgl;
        $siswas_id = $request->siswas_id;
        $jam_pinjam = $request->jam_pinjam;
        $jam_kembali = $request->jam_kembali;
        $description = $request->description;

        // Simpan ke database
        $yourModel = new PeminjamanTahunan();
        $yourModel->kode_pinjam = $kode_pinjam;
        $yourModel->siswas_id = $siswas_id;
        $yourModel->jam_pinjam = $jam_pinjam;
        $yourModel->jam_kembali = $jam_kembali;
        $yourModel->description = $description;
        $yourModel->save();

        return redirect()->route('peminjamantahunanbuku.create')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function createbuku()
    {
        $iduser = Auth::id();
        $profile = User::where('id', $iduser)->first();

        $peminjamantahunanbuku = PeminjamanTahunan::all();
        $siswa = Siswa::all();
        $bukucrud = Bukucrud::all();
        return view('peminjamantahunan.createbuku', compact('peminjamantahunanbuku', 'bukucrud', 'siswa', 'profile'));
    }

    public function storebuku(Request $request)
    {
        $this->validate($request, [
            'peminjamantahunan_id' => 'required|array|min:1|max:50',
            'bukucruds_id' => 'required|array|min:1|max:50',
            'jml_buku' => 'required|array',
            'kodebuku' => 'required|array',
        ]);

        $peminjamantahunan_id = $request->peminjamantahunan_id;
        $bukucruds_id = $request->bukucruds_id;
        $kodebuku = $request->kodebuku;
        $jml_buku = $request->jml_buku;

        DB::beginTransaction();

        try {
            foreach ($kodebuku as $i => $kode) {
                $buku = Bukucrud::find($bukucruds_id[$i]);

                if (!$buku) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Buku tidak ditemukan.');
                }

                if ($buku->stok < $jml_buku[$i]) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Stok buku tidak mencukupi.');
                }

                // Kurangi stok
                $buku->stok -= $jml_buku[$i];
                $buku->save();

                Buku::create([
                    'peminjamantahunan_id' => $peminjamantahunan_id[$i],
                    'bukucruds_id' => $bukucruds_id[$i],
                    'kodebuku' => $kode,
                    'jml_buku' => $jml_buku[$i],
                ]);
            }

            DB::commit();
            return redirect('/peminjamantahunan')->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan data.');
        }
    }

    public function show(string $id)
    {
        $iduser = Auth::id();
        $profile = User::where('id', $iduser)->first();

        $peminjamantahunan = PeminjamanTahunan::findOrFail($id);
        return view('peminjamantahunan.show', compact('peminjamantahunan', 'profile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $iduser = Auth::id();
        $profile = User::where('id', $iduser)->first();

        // Ambil data peminjamantahunan beserta buku dan relasi ke bukucrud
        $peminjamantahunan = PeminjamanTahunan::with('bukus.bukucruds')->findOrFail($id);
        $siswas = Siswa::all();
        $bukucrud = Bukucrud::all();
        $bukus = Buku::with('bukucruds')->get();
        return view('peminjamantahunan.edit', compact('peminjamantahunan', 'bukucrud', 'siswas', 'bukus', 'profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'siswas_id' => 'required|exists:siswas,id',
        'jam_kembali' => 'required|date',
    ]);

    // Update data peminjaman tahunan
    $peminjamantahunan = PeminjamanTahunan::findOrFail($id);
    $peminjamantahunan->siswas_id = $request->siswas_id;
    $peminjamantahunan->kode_pinjam = $request->kode_pinjam;
    $peminjamantahunan->jam_kembali = $request->jam_kembali;
    $peminjamantahunan->save();

    // Loop untuk setiap buku yang diupdate
    foreach ($request->jml_buku as $buku_id => $jumlah_baru) {
        $buku = Buku::findOrFail($buku_id);
        
        // Hitung selisih jumlah buku yang diupdate
        $jumlah_sebelumnya = $buku->jml_buku;
        $selisih = $jumlah_baru - $jumlah_sebelumnya;

        // Update jumlah buku
        $buku->jml_buku = $jumlah_baru;
        $buku->save();

        // Update stok di Bukucrud terkait
        $bukucrud = $buku->bukucruds;
        $bukucrud->stok -= $selisih; // Kurangi atau tambah stok sesuai selisih
        $bukucrud->save();
    }

    return redirect()->route('peminjamantahunan')->with('success', 'Peminjaman updated successfully');
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $peminjamantahunan = PeminjamanTahunan::findOrFail($id);

        $peminjamantahunan->delete();

        return redirect()->route('peminjamantahunan')->with('success', 'Peminjaman deleted successfully');
    }

    public function removeAll()
    {
        PeminjamanTahunan::query()->forceDelete();
        return redirect()->route('peminjamantahunan')->with('removeAll', 'Reset data Peminjaman Tahunan successfully');
    }
}
