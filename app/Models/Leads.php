<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Leads extends Model
{
    protected $table='leads';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     protected $casts = [
    
];
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function source():BelongsTo
{
  return $this->belongsTo(LeadSource::class,'source_id','id')->withDefault()->withTrashed();
} 
 
	public function assigned_to():BelongsTo
{
  return $this->belongsTo(User::class,'assigned_id','id')->withDefault()->withTrashed();
} 
 
	public function product():BelongsTo
{
  return $this->belongsTo(Product::class,'product_id','id')->withDefault()->withTrashed();
} 
 }