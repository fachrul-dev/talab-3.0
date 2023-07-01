<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index()
{
    $pegawai = User::all();

    return view('manajemen', ['pegawai' => $pegawai]);
}

public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role')
        ]);

        return redirect()->route('manajemen-pegawai')->with('success', 'User created successfully.');
    }

public function edit($id)
    {
        $pegawai = User::findOrFail($id);
        // Implementasikan logika lain yang diperlukan untuk halaman pengeditan pegawai
        return view('manajemen', compact('pegawai'));
    }

    public function destroy($id)
    {
        $pegawai = User::findOrFail($id);
        // Implementasikan logika penghapusan pegawai
        $pegawai->delete();

        // Redirect atau kembalikan respon yang sesuai
        return redirect()->route('manajemen-pegawai')->with('success', 'Pegawai berhasil dihapus.');
    }


    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        // Validasi data yang diterima dari form
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'nullable' 
        ]);
        
        // Memperbarui data pegawai
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->role = $request->input('role');
        
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }
        
        $employee->save();
        

        // Redirect atau kembalikan respon yang sesuai
        return redirect()->route('manajemen-pegawai')->with('success', 'Data pegawai berhasil diperbarui.');
    }

}
