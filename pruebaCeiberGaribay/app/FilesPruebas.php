<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilesPruebas extends Model
{
    
    protected $table = 'tabla_filespruebas';
    protected $fillable = [
        'nombreArchivo'
    ];

}
