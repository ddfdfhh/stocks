<?php

namespace App\Exports;

use App\Models\InputMaterial;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InputMaterialExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($model_relations, $filter = [], $filter_date = [], $date_field = null)
    {
        $this->filter = $filter;
        $this->filter_date = $filter_date;
        $this->model_relations = $model_relations;
        $this->date_field = $date_field;
        $this->export_columns = [
            'name',
            'unit', 'Current Quantity', 'Total Quantity', 'Total Used',
        ];

    }
    public function query()
    {

        $query = null;
        if (count($this->model_relations) > 0) {
            $query = InputMaterial::with(['material_stock', 'unit']);
        } else {
            $query = InputMaterial::query();
        }
        if (count($this->filter) > 0) {
            $query = $query->where($this->filter);
        }
        if (count($this->filter_date) > 0 && $this->date_field) {
            $query = $query->whereDate($this->date_field, '>=', $this->filter_date['min'])
                ->whereDate($this->date_field, '<=', $this->filter_date['max']);
        }
        return $query;
    }
    public function map($row): array
    {
        /**use here to map to relatioship or format column  */
        //  dd($row->toArray());
        return [
            $row->name,
            $row->unit->name,
            $row->material_stock ? $row->material_stock->current_stock : 0,
            $row->material_stock ? $row->material_stock->total_incoming : 0,
            $row->material_stock ? $row->material_stock->total_outgoing : 0,
        ];
    }

    public function headings(): array
    {
        $resp = [];
        foreach ($this->export_columns as $colname) {

            $resp[] = ucwords(str_replace('_', ' ', $colname));
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
