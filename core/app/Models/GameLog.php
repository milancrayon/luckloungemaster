<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class GameLog extends Model
{
    use Searchable;

    // Define searchable columns (the columns that will be indexed for search)
    public function toSearchableArray()
    {
        return [
            'username' => $this->user->username, // Assuming a relationship to 'user' model
            'email' => $this->user->email,
            'lastname' => $this->user->lastname,
            'firstname' => $this->user->firstname,
            'game_name' => $this->game->name, // Assuming a relationship to 'game' model
        ];
    }

    protected $guarded = ['id'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWin()
    {
        return $this->where('win_status', Status::WIN);
    }
    public function scopeLoss()
    {
        return $this->where('win_status', Status::LOSS);
    }
}
