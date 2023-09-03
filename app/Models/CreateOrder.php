<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CreateOrder extends Model
{
    protected $table='create_order';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function customer():BelongsTo
{
  return $this->belongsTo(Customer::class,'customer_id','id')->withDefault();
} 

 
	public function driver():BelongsTo
{
  return $this->belongsTo(Driver::class,'driver_id','id')->withDefault();
} 
 }