<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";

    public function parent(){
        return $this->hasOne('App\Category' ,'id', 'parent');
    }

    public function children(){
        return $this->hasMany('App\Category' ,'parent', 'id');
    }

    public function products(){
        return $this->hasMany('App\Product' ,'cid', 'id');
    }
}
