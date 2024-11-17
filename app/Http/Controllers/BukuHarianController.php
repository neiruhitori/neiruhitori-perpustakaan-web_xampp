<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bukusharian;
use App\Models\KodebukuHarian;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BukuHarianController extends Controller
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

        // if ($request->has('search')) {
        //     $bukuharian = Bukusharian::where('buku', 'LIKE', '%' . $request->search . '%')->paginate(5);
        // } else {
        //     $bukuharian = Bukusharian::orderBy('created_at', 'DESC')->paginate(10);
        // }

        if ($request->has('search')) {
            $bukuharian = Bukusharian::with('kodebukuharians')
                ->where('buku', 'LIKE', '%' . $request->search . '%')
                ->paginate(5);
        } else {
            $bukuharian = Bukusharian::with('kodebukuharians')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        }
        return view('bukuharian.index', compact('bukuharian', 'profile'));
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

        $bukuharian = Bukusharian::all();
        return view('bukuharian.create', compact('bukuharian', 'profile'));
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
            'buku' => 'required|min:1|max:50',
            'penulis' => 'required|min:1|max:50',
            'penerbit' => 'required|min:1|max:50',
            'stok' => 'required:true',
            'foto' => 'required|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
        ]);

        $data = Bukusharian::create($request->all());

        if ($request->hasFile('foto')) {
            $request->file('foto')->move('gambarbukuharian/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }
        return redirect()->route('bukuharian.createkodebukuharian')->with('success', 'Data Berhasil di Tambahkan');
        // return redirect('/bukuharian')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function createkodebukuharian()
    {
        $iduser = Auth::id();
        $profile = User::where('id', $iduser)->first();

        $kodebukuharian = KodebukuHarian::all();
        $bukuharian = Bukusharian::all();
        return view('bukuharian.createkodebukuharian', compact('kodebukuharian', 'bukuharian', 'profile'));
    }
    public function createkodebukuharianstore(Request $request)
    {
        $this->validate($request, [
            'bukuharian_id' => 'required|array',
            'bukuharian_id.*' => 'required|exists:bukusharians,id',
            'kodebuku' => 'required|array',
            'kodebuku.*' => 'required|string|distinct',
        ]);

        // Loop melalui input array untuk menyimpan setiap kode buku
        foreach ($request->bukuharian_id as $index => $bukuharian_id) {
            // Pastikan kodebuku sesuai dengan urutan bukuharian_id
            $kodebuku = $request->kodebuku[$index];

            KodebukuHarian::create([
                'bukuharian_id' => $bukuharian_id,
                'kodebuku' => $kodebuku,
            ]);
        }

        return redirect('/bukuharian')->with('success', 'Data Berhasil Ditambahkan');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $iduser = Auth::id();
        $profile = User::where('id', $iduser)->first();

        $bukuharian = Bukusharian::findOrFail($id);
        return view('bukuharian.show', compact('bukuharian', 'profile'));
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

        $bukuharian = Bukusharian::findOrFail($id);
        return view('bukuharian.edit', compact('bukuharian', 'profile'));
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
        // Validasi input
        $this->validate($request, [
            'buku' => 'required|min:1|max:50',
            'penulis' => 'required|min:1|max:50',
            'penerbit' => 'required|min:1|max:50',
            'stok' => 'required|integer',
            'kodebuku' => 'required|array',  // Memastikan kodebuku adalah array
            'kodebuku.*' => 'string|distinct',  // Memastikan tiap kodebuku adalah string dan unik
        ]);

        // Temukan data Bukusharian berdasarkan ID
        $data = Bukusharian::findOrFail($id);

        // Hanya update kolom-kolom yang ada di tabel bukusharians
        $data->update([
            'buku' => $request->buku,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'stok' => $request->stok,
            'description' => $request->description,
            // kolom lain yang relevan dari tabel bukusharians
        ]);

        // Handle file upload untuk foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($data->foto && file_exists(public_path('gambarbukuharian/' . $data->foto))) {
                unlink(public_path('gambarbukuharian/' . $data->foto));
            }

            // Simpan foto baru
            $filename = $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move('gambarbukuharian/', $filename);
            $data->foto = $filename;
        }

        $data->save();

        // Update kodebuku di KodebukuHarian
        foreach ($request->kodebuku as $key => $kode) {
            $kodebuku = KodebukuHarian::where('bukuharian_id', $id)->where('id', $key)->first();
            if ($kodebuku) {
                $kodebuku->kodebuku = $kode;
                $kodebuku->save();
            }
        }

        return redirect()->route('bukuharian')->with('success', 'Buku updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bukuharian = Bukusharian::findOrFail($id);

        $bukuharian->delete();

        return redirect()->route('bukuharian')->with('success', 'buku deleted successfully');
    }

    public function removeAll()
    {
        Bukusharian::query()->forceDelete();
        return redirect()->route('bukuharian')->with('removeAll', 'Reset data Buku Harian successfully');
    }
}
