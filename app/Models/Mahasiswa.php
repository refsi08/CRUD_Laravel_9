<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // nama table
    protected $table = 'mahasiswas';

    // kolom mana saja yang boleh diisi
    protected $fillable = ['nim', 'nama', 'jurusan'];

    // tidak menggunakan timestamp
    public $timestamps = false;
}
