<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class League extends Model {
    use GlobalStatus;

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function betgames() {
        return $this->hasMany(Betgame::class);
    }

    public function runningGame() {
        return $this->hasMany(Betgame::class)->where('bet_end_time', '>', now())->where('bet_start_time', '<', now());
    }
    public function upcomingGame() {
        return $this->hasMany(Betgame::class)->where('bet_start_time', '>', now());
    }

}
