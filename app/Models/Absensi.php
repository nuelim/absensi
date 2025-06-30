<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'mata_kuliah_id',
        'tanggal_absensi',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id'); // Eksplisitkan foreign key jika nama model dan tabel berbeda
    }
}