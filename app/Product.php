<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $incrementing = false;
    public function images()
    {
        return $this->hasMany('App\SKUImage', 'product_id', 'id' )->orderBy('id', 'asc');
    }

    public function skus()
    {
        return $this->hasMany('App\StockKeepingUnit', 'product_id', 'id' )->where([
            ['stock_left', '>', 0]
        ])->orderBy('description');
    }

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'cid' );
    }
}
