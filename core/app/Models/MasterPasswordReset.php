<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPasswordReset extends Model
{
    protected $table = "master_password_resets";
    protected $guarded = ['id'];
    public $timestamps = false;
}
