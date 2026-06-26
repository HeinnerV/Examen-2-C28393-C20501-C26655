<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisicion extends Model
{
    protected $table = 'requisiciones';

    protected $fillable = [
        'fecha',
        'estado',
        'idUsuario', 
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUsuario', 'idUsuario');
    }

    // ocupa Model ItemRequisicion
    // public function items(): HasMany
    // {
    //     return $this->hasMany(ItemRequisicion::class, 'idRequisicion', 'idRequisicion');
    // }
}