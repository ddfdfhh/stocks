<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Product extends Model
{
    protected $table='product';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function category():HasOne
{
  return $this->hasOne(Category::class,'id','category_id');
}
 
	public function images():HasMany
{
return $this->hasMany(ProductImage::class,'product_id','id');
}
  
 }