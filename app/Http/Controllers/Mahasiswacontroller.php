<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;

class Mahasiswacontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // pencarian
        $kataKunci = $request->katakunci;
        $jumlahBaris = 5;
        if (strlen($kataKunci)) {
            $data = Mahasiswa::where('nim', 'like', "%$kataKunci%")
                ->orWhere('nama', 'like', "%$kataKunci%")
                ->orWhere('jurusan', 'like', "%$kataKunci%")
                ->paginate($jumlahBaris);
        } else {
            $data = Mahasiswa::orderBy('nim', 'desc')->paginate($jumlahBaris);
        }
        return view('mahasiswa.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Data yang sudah di input tidak hilang
        FacadesSession::flash('nim', $request->nim);
        FacadesSession::flash('nama', $request->nama);
        FacadesSession::flash('jurusan', $request->jurusan);

        // validasi
        $request->validate(
            [
                'nim' => 'required|numeric|unique:mahasiswas,nim',
                'nama' => 'required',
                'jurusan' => 'required',
            ],
            [
                'nim.required' => 'NIM Wajib diisi',
                'nim.numeric' => 'NIM Wajib angka',
                'nim.unique' => 'NIM Sudah ada ',
                'nama.required' => 'Nama Wajib diisi',
                'jurusan.required' => 'Jurusan Wajib diisi',
            ]
        );

        // request data
        $data = [
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];

        Mahasiswa::create($data);
        return redirect()->route('mahasiswa.index')->with('success', 'Berhasil Menyimpan Data');
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
        $data = Mahasiswa::where('nim', $id)->first();
        return view('mahasiswa.edit', compact('data'));
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
        // validasi
        $request->validate(
            [
                'nama' => 'required',
                'jurusan' => 'required',
            ],
            [
                'nama.required' => 'Nama Wajib diisi',
                'jurusan.required' => 'Jurusan Wajib diisi',
            ]
        );

        // request data
        $data = [
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];

        Mahasiswa::where('nim', $id)->update($data);
        return redirect()->route('mahasiswa.index')->with('success', 'Berhasil Mengubah Data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Mahasiswa::where('nim', $id)->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Berhasil Menghapus Data');
    }
}
