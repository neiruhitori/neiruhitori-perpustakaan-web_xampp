<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $iduser = Auth::id();
        $profile = User::where('id',$iduser)->first();
        
        if ($request->has('search')) {
            $siswa = Siswa::where('name', 'LIKE', '%' .$request->search. '%')->paginate(5);
        } else {
            $siswa = Siswa::orderBy('created_at', 'DESC')->paginate(35);
        }
        return view('siswa.index', compact('siswa', 'profile'));
    }

    public function create()
    {
        $iduser = Auth::id();
        $profile = User::where('id',$iduser)->first();

        $siswa = Siswa::all();
        return view('siswa.create', compact('siswa', 'profile'));
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
            'name' => 'required|min:1|max:50',
            'kelas' => 'required|min:1|max:50',
        ]);
        
        Siswa::create([
                    'name' => $request->name,
                    'kelas' => $request->kelas,
                ]);
                return redirect('/siswa')->with('success', 'Data Berhasil di Tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $iduser = Auth::id();
        $profile = User::where('id',$iduser)->first();
        
        $siswa = Siswa::findOrFail($id);
        return view('siswa.show', compact('siswa', 'profile'));
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
        
        $siswa = Siswa::findOrFail($id);
        return view('siswa.edit', compact('siswa', 'profile'));
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
        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->all());

        return redirect()->route('siswa')->with('success', 'Siswa updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
  
        $siswa->delete();
  
        return redirect()->route('siswa')->with('success', 'Siswa deleted successfully');
    }

    public function removeAll(){
        Siswa::query()->forceDelete();
        return redirect()->route('siswa')->with('removeAll', 'Reset data Siswa successfully');
    }
}
