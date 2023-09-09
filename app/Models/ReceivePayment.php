<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ReceivePayment extends Model
{
    protected $table='receive_payments';
    public $timestamps=0;
   
protected $dates = [
        'paid_date',
        'due_date',
        'created_at'
       
    ];
     public function getFillable(){
        return  $this->getTableColumns();
     }
     
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function order():BelongsTo
{
  return $this->belongsTo(CreateOrder::class,'order_id','id')->withDefault();
} 
 }