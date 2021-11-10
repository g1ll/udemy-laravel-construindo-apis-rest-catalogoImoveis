<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'content',
        'price',
        'bathrooms',
        'bedrooms',
        'property_area',
        'total_property_area',
        'slug'
    ];

    protected $table = 'real_state';
    protected $appends = ['_links','_thumb'];

    //Accessors
    public function getLinksAttribute(){
        return [
            'href'=>route('real_states.real-states.show',['real_state'=>$this->id]),
            'rel'=>'real-states'
            ];
    }

    public function getThumbAttribute(){
        return $this->photos()->where('is_thumb',1)->first()->photo;
    }

    public function user(){
        return $this->belongsTo(User::class); //user_id
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'real_state_categories');
    }

    public function photos(){
        return $this->hasMany(RealStatePhoto::class);
    }

    public function address(){
        return $this->belongsTo(Address::class);
    }
}

