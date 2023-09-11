<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class GeneratedProductStock extends Model
{
    protected $table='generated_product_stocks';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
     public function getRawMeterialsAttribute($value) {

        $infoArray = json_decode($value, true);
        dd('ok');
        $infoArray=array_map(function($v){
          unset($v['material_id']);
          return $v;
        },$infoArray);
       
        $infoJson = json_encode($infoArray);
        
        return $infoJson;
    }
   
  

	public function product():BelongsTo
{
  return $this->belongsTo(Product::class,'product_id','id')->withDefault()->withTrashed();
} 
 }