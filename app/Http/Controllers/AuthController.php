<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
// use illuminate\Support\Facades\Validator;
// use illuminate\Support\Facades\Hash;
use illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerSave( Request $request)
    {

        $this->validate($request, [
            'perpustakaan_id' => 'required|min:10|max:10',
            'name' => 'required|min:1|max:50',
            // 'email' => 'required|min:1|max:50',
            'password' => 'required|min:5|max:50',
        ]);
        
        User::create([
            'perpustakaan_id' => $request->perpustakaan_id,
            'name' => $request->name,
            // 'email' => $request->email,
            // 'password' => $request->password,
            'password' => bcrypt($request->password),
        ]);
  
        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        if (Auth::attempt($request->only('perpustakaan_id', 'password'))) {
            return redirect('/dashboard');
        }
        return redirect('/login')->with('error', 'Username atau password yang anda masukkan salah');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
  
        $request->session()->invalidate();
  
        return redirect('/login');
    }
 
    public function profile()
    {
        $iduser = Auth::id();
        $profile = User::where('id',$iduser)->first();
        return view('profile', compact('profile'));
    }

    public function updateProfile(Request $request) {

        $user = Auth::user();

        if ($request->has('nip', 'name', 'email', 'password')) {           
            $user->nip = ($request->input('nip'));
            $user->name = ($request->input('name'));
            $user->email = ($request->input('email'));
            $user->password = bcrypt($request->input('password'));

            // $user->photoProfile = ($request->input('photoProfile'));
   
            // $namaGambar = time().'.'.$request->photoProfile->extension();
   
            // $request->photoProfile->move(public_path('AdminLTE-3.2.0/dist/img/photoProfile'),$namaGambar);
   
            // $user->photoProfile =$namaGambar;

        } else {

        }
        // $user->save();
    
        return redirect('/dashboard')->with('success', 'Password berhasil diubah.');
    }
}
