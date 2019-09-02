<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $incrementing = false;
    public function images()
    {
        return $this->hasMany('App\SKUImage', 'product_id', 'id' );
    }

    public function skus()
    {
        return $this->hasMany('App\StockKeepingUnit', 'product_id', 'id' )->orderBy('description');
    }

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'cid' );
    }
}
