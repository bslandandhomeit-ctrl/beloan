<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $hidden = ['created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo('App\Models\ProductCategory', 'category_id');
    }
    public function prod_prod_type()
    {
        return $this->belongsTo('App\Models\Products\Product_products_type', 'product_type_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\ProductBrand', 'brand_id');
    }

    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealer', 'dealer_id');
    }

    public function record()
    {
        return $this->hasMany('App\Models\ProductRecord', 'product_id');
    }

    public function loan()
    {
        return $this->hasOne('App\Models\Loan', 'product_id');
    }
    public function product_types()
    {
        return $this->belongsTo('App\Models\Products\Product_type', 'product_type_id','id');
    }
}
