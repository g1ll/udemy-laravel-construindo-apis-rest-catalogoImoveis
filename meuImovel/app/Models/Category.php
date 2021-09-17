<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','slug'];

    public function real_states(){
        return $this->belongsToMany(RealState::class,'real_state_categories');
    }
}
