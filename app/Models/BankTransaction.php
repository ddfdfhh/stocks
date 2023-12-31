<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BankTransaction extends Model
{
    protected $table='bank_transactions';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
     public function setAttributeHandledById($value){
    $this->attributes['handled_by_id']=auth()->id();
   }
   public function handled_by():BelongsTo
{
  return $this->belongsTo(User::class,'handled_by_id','id')->withDefault();
}
public function store():BelongsTo
{
  return $this->belongsTo(Store::class,'store_id','id')->withDefault();
}
  
}