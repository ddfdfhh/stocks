<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    
     public function getFillable(){
        return  $this->getTableColumns();
     }
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}