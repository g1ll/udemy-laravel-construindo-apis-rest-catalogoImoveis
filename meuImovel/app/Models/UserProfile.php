<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $fillable = ['phone', 'mobile_phone', 'about', 'social_networks'];
    protected $table = 'user_profile';
    public function user(){
        return $this->belongsTo(User::class);
    }
}
