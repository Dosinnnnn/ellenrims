<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['title', 'slug', 'status'];

    // public static function getProductByBrand($id){
    //     return Product::where('brand_id',$id)->paginate(10);
    // }
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'brand_id', 'id')->where('status', 'aktif');
    }
    // public static function getProductByBrand($slug){
    //     // dd($slug);
    //     return Brand::with('products')->where('slug',$slug)->first();
    //     // return Product::where('cat_id',$id)->where('child_cat_id',null)->paginate(10);
    // }
    public static function getProductByBrand($slug)
    {
        return Brand::with('products')->where('slug', $slug)->first();
    }


    public function getAllBrands()
    {
        return $this->orderBy('title', 'ASC')->where('status', 'aktif')->get();
    }
}
