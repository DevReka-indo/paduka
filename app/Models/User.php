<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Ncr;
use App\Models\UnitKerja;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'jabatan',
        'unit_kerja',
        'departemen',
        'divisi',
        'no_telp',
        'foto',
        'level',
        'keterangan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ncrs()
    {
        return $this->hasMany(Ncr::class);
    }

    public function managerQcNcrs()
    {
        return $this->hasMany(Ncr::class, 'manager_qc_id');
    }

    public function managerTgpNcrs()
    {
        return $this->hasMany(Ncr::class, 'manager_tgp_id');
    }

    public function penanggungJawabNcrs()
    {
        return $this->hasMany(Ncr::class, 'penanggung_jawab');
    }

    public function unitKerja()
    {
        return $this->belongsToMany(UnitKerja::class, 'unit_kerja_user', 'user_id', 'unit_kerja_id')
            ->withTimestamps();
    }
}
