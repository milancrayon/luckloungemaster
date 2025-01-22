<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MastersTransaction extends Model
{
    public function master()
    {
        return $this->belongsTo(Master::class);
    }
}
