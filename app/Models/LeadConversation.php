<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LeadConversation extends Model
{
    protected $table='lead_conversations';
    public $timestamps=0;
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
   
  

	public function lead():BelongsTo
{
  return $this->belongsTo(Lead::class,'lead_id','id')->withDefault();
} 
 
	public function by_user():BelongsTo
{
  return $this->belongsTo(User::class,'conversation_by_user_id','id')->withDefault();
} 
 }