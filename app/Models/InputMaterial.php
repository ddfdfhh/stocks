<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InputMaterial extends Model
{
  use SoftDeletes;
    protected $table='input_material';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function unit():BelongsTo
{
  return $this->belongsTo(Unit::class,'unit_id','id')->withDefault();
} 
	public function material_stock():BelongsTo
{
  return $this->belongsTo(MaterialStock::class,'id','material_id')->withDefault();
} 
 }