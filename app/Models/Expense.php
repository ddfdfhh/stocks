<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Expense extends Model
{
    protected $table='expenses';
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
    public function setAttributeDueAmount($value)
{
      $this->due_amount=0.0;
  return ;
} 
   
  

	public function item():BelongsTo
{
  return $this->belongsTo(ExpsnseItem::class,'item_id','id')->withDefault();
} 
 
	public function paid_user():BelongsTo
{
  return $this->belongsTo(User::class,'paid_user_id','id')->withDefault();
} 
 }