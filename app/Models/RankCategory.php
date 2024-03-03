<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankCategory extends Model
{
    use HasFactory;

    public function ranks(){
        return $this->hasMany(Rank::class);
    }
}
