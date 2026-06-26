<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unidad extends Model
{
    protected $table = 'unidades';

    protected $fillable = [
        'nombre',
    ];

   
    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class, 'idUnidad', 'idUnidad');
    }


    public function materialUnidades(): HasMany
    {
        return $this->hasMany(MaterialUnidad::class, 'idUnidad', 'idUnidad');
    }
}