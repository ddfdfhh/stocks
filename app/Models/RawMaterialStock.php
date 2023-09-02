<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class RawMaterialStock extends Model
{
    protected $table='raw_material_stock';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function raw_material():BelongsTo
{
  return $this->belongsTo(InputMaterial::class,'material_id','id')->withDefault();
} 
 
	public function unit():BelongsTo
{
  return $this->belongsTo(Unit::class,'unit_id','id')->withDefault();
} 
 }