<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Supplier extends Model
{
    protected $table='supplier';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function state():BelongsTo
{
  return $this->belongsTo(State::class,'state_id','id')->withDefault();
} 
 
	public function city():BelongsTo
{
  return $this->belongsTo(City::class,'city_id','id')->withDefault();
} 
 }