<?php

namespace App\Http\Controllers;

use App\Models\Bukucrud;
use App\Models\KodebukuTahunan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukucrudController extends Controller
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

        if ($request->has('search')) {
            $buku = Bukucrud::where('buku', 'LIKE', '%' . $request->search . '%')->paginate(5);
        } else {
            $buku = Bukucrud::orderBy('created_at', 'DESC')->paginate(10);
        }
        return view('buku.index', compact('buku', 'profile'));
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

        $buku = Bukucrud::all();
        return view('buku.create', compact('buku', 'profile'));
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

        $data = Bukucrud::create($request->all());

        if($request->hasFile('foto')){
            $request->file('foto')->move('gambarbukutahunan/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }

        // Bukucrud::create([
        //     'buku' => $request->buku,
        //     'penulis' => $request->penulis,
        //     'penerbit' => $request->penerbit,
        //     'description' => $request->buku,
        // ]);
        return redirect()->route('buku.createkodebukutahunan')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function createkodebukutahunan()
    {
        $iduser = Auth::id();
        $profile = User::where('id', $iduser)->first();

        $kodebuku = KodebukuTahunan::all();
        $buku = Bukucrud::all();
        return view('buku.createkodebukutahunan', compact('kodebuku', 'buku', 'profile'));
    }
    public function createkodebukutahunanstore(Request $request)
    {
        $this->validate($request, [
            'bukucrud_id' => 'required|array',
            'bukucrud_id.*' => 'required|exists:bukucruds,id',
            'kodebuku' => 'required|array',
            'kodebuku.*' => 'required|string|distinct',
        ]);

        // Loop melalui input array untuk menyimpan setiap kode buku
        foreach ($request->bukucrud_id as $index => $bukucrud_id) {
            // Pastikan kodebuku sesuai dengan urutan bukucrud_id
            $kodebuku = $request->kodebuku[$index];

            KodebukuTahunan::create([
                'bukucrud_id' => $bukucrud_id,
                'kodebuku' => $kodebuku,
            ]);
        }

        return redirect('/buku')->with('success', 'Data Berhasil Ditambahkan');
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
        $profile = User::where('id',$iduser)->first();
        
        $buku = Bukucrud::findOrFail($id);
        return view('buku.show', compact('buku', 'profile'));
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
        $profile = User::where('id',$iduser)->first();
        
        $buku = Bukucrud::findOrFail($id);
        return view('buku.edit', compact('buku', 'profile'));
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
            'foto' => 'required|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
        ]);

        // Temukan data Bukusharian berdasarkan ID
        $data = Bukucrud::findOrFail($id);

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
            if ($data->foto && file_exists(public_path('gambarbukutahunan/' . $data->foto))) {
                unlink(public_path('gambarbukutahunan/' . $data->foto));
            }

            // Simpan foto baru
            $filename = $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move('gambarbukutahunan/', $filename);
            $data->foto = $filename;
        }

        $data->save();

        // Update kodebuku di KodebukuHarian
        foreach ($request->kodebuku as $key => $kode) {
            $kodebuku = KodebukuTahunan::where('bukucrud_id', $id)->where('id', $key)->first();
            if ($kodebuku) {
                $kodebuku->kodebuku = $kode;
                $kodebuku->save();
            }
        }

        return redirect('/buku')->with('success', 'Data Berhasil di Update');
    }
    // {
    //     $buku = Bukucrud::findOrFail($id);
    //     $buku->update($request->all());

    //     return redirect()->route('buku')->with('success', 'buku updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $buku = Bukucrud::findOrFail($id);
  
        $buku->delete();
  
        return redirect()->route('buku')->with('success', 'buku deleted successfully');
    }

    public function removeAll(){
        Bukucrud::query()->forceDelete();
        return redirect()->route('buku')->with('removeAll', 'Reset data Buku Tahunan successfully');
    }
}
