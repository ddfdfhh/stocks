<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AddProductStock extends Model
{
    protected $table='add_product_stocks';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   public function store():BelongsTo
{
  return $this->belongsTo(Store::class,'store_id','id')->withDefault();
} 
   public function created_by():BelongsTo
{
  return $this->belongsTo(User::class,'created_by_id','id')->withDefault();
} 
  

	public function product():BelongsTo
{
  return $this->belongsTo(Product::class,'product_id','id')->withDefault();
} 
 }