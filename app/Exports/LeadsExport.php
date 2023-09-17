<?php

namespace App\Exports;

use App\Models\Leads;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadsExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings
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
            'lead_name',
            'email',
            'lead_phone_no',
            'whatsapp_no',
            'company_name',
            'designation',
            'address',
            'assigned_id',
            'status',
            'type',
            'source',

        ];

    }
    public function query()
    {

        $query = null;
        if (count($this->model_relations) > 0) {
            $query = Leads::with(array_column($this->model_relations, 'name'));
        } else {
            $query = Leads::query();
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

        return [
            $row->lead_name,
            $row->email,
            $row->lead_phone_no,
            $row->whatsapp_no,
            $row->company_name,
            $row->designation,
            $row->address,
            $row->assigned_to ? $row->assigned_to->name : '',

            $row->status,
            $row->type,
            $row->source ? $row->source->name : '',
        ];
    }

    public function headings(): array
    {
        $resp = [];
        foreach ($this->export_columns as $colname) {
            $lab = ucwords(str_replace('_', ' ', $colname));
            $lab = ucwords(str_replace(' Id', ' ', $lab));
            $resp[] = $lab;
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
