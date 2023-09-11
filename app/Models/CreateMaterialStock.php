<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CreateMaterialStock extends Model
{
    protected $table='create_material_stock';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function material():BelongsTo
{
  return $this->belongsTo(InputMaterial::class,'material_id','id')->withDefault()->withTrashed();
} 
	public function driver():BelongsTo
{
  return $this->belongsTo(Driver::class,'driver_id','id')->withDefault()->withTrashed();
} 
	public function supplier():BelongsTo
{
  return $this->belongsTo(Supplier::class,'supplier_id','id')->withDefault()->withTrashed();
} 
 }