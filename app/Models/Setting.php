<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**Protection : Mass Assignment :
     * Utilisations de la $fillable pour que Laravel autorise uniquement les champs présents dans cette variable
     */
    protected $fillable = [
        'module',
        'key',
        'value',
        'type',
    ];
}
