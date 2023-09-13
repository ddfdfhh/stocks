<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateOrder extends Model
{
  use SoftDeletes;
    protected $table='create_order';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   public function setAttributeCreatedById($value){
    $this->attributes['created_by_id']=auth()->id();
   }
  

	public function customer():BelongsTo
{
  return $this->belongsTo(Customer::class,'customer_id','id')->withDefault()->withTrashed();
} 

	public function store():BelongsTo
{
  return $this->belongsTo(Store::class,'store_id','id')->withDefault()->withTrashed();
} 

	public function created_by():BelongsTo
{
  return $this->belongsTo(User::class,'created_by_id','id')->withDefault()->withTrashed();
} 

 
	public function driver():BelongsTo
{
  return $this->belongsTo(Driver::class,'driver_id','id')->withDefault()->withTrashed();;
} 
 }