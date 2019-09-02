<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockKeepingUnit extends Model
{
    public $incrementing = false;

    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany('App\SKUImage', 'sku_id', 'id');
    }
}
