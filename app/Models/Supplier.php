<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
 use SoftDeletes,HasFactory;


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
  return $this->belongsTo(State::class,'state_id','id')->withDefault()->withTrashed();
} 
 
	public function city():BelongsTo
{
  return $this->belongsTo(City::class,'city_id','id')->withDefault()->withTrashed();
} 
 }