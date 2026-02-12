<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AduanFoto extends Model
{
    protected $fillable = ['aduan_id', 'path'];

    public function aduan()
    {
        return $this->belongsTo(Aduan::class);
    }
}
