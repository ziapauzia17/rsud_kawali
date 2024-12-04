<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RencanaHasilKinerjaPegawai extends Model
{
    use HasFactory;

    protected $table = 'rencana_hasil_kerja_pegawai';

    protected $fillable = [
        'uuid',
        'rencana_atasan_id',
        'user_id',
        'skp_id',
        'rencana',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rencanaAtasan(): BelongsTo
    {
        return $this->belongsTo(RencanaHasilKinerja::class, 'rencana_atasan_id');
    }

    public function skp(): BelongsTo
    {
        return $this->belongsTo(Skp::class);
    }

    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class, 'rencana_kerja_pegawai_id', 'id');
    }

    public function getIndikatorWithDetails()
    {
        return $this->indikatorKinerja()->with('user');
    }
}