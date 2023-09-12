<?php

namespace App\Exports;

use App\Models\TransferProductStock;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping; 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TransferProductStockExport  implements FromQuery,WithColumnFormatting, WithMapping,WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
   public function __construct($model_relations,$filter=[],$filter_date=[],$date_field=null)
    {
        $this->filter = $filter;
        $this->filter_date = $filter_date;
         $this->model_relations = $model_relations;
        $this->date_field = $date_field;
        $this->export_columns=[
    'title',
    'store_id',
    'product_id',
    'quantity',
    'created_at'
];
        
    }
   public function query()
    {
        
         $query = null;
        if (count($this->model_relations) > 0) {
            $query = TransferProductStock::with(array_column($this->model_relations, 'name'));
        } else {
            $query = TransferProductStock::query();
        }
        if(count($this->filter)>0){
            $query=$query->where($this->filter);
         }
         if(count($this->filter_date)>0 && $this->date_field){
              $query=$query->whereDate($this->date_field,'>=',$this->filter_date['min'])
              ->whereDate($this->date_field,'<=',$this->filter_date['max']);
          }
          return $query;
    }
    public function map($row): array
    {
        /**use here to map to relatioship or format column  */
        $resp=[];
         foreach ($this->export_columns as $colname) {
            $val = $row->{$colname};
            if (count($this->model_relations)>0 && isFieldPresentInRelation($this->model_relations, $colname) >= 0) {
                $resp[] = getForeignKeyFieldValue($this->model_relations, $row, $colname);
            } else {
                $y = json_decode($val, true);
                if (!is_numeric($val) && $y != null) {
                    $resp[] = showArrayInColumn($y);
                } else {
                    $resp[] = $row->{$colname};

                }

            }

        }
        return $resp;
    }
   
     public function headings(): array
    {
        $resp=[];
        foreach($this->export_columns as $colname){
                
            $resp[]=ucwords(str_replace('_',' ',$colname));
        }
       
        return $resp;
    }
    public function columnFormats(): array
    {
        return [
        //    'C' => NumberFormat::FORMAT_DATE_YYYYMMDD,
           
        ];
    }
}