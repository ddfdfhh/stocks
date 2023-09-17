<?php

namespace App\Exports;

use App\Models\CompanyLedger;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LedgerExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings
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
            'name', 'amount', 'mode',
            'created_at',

        ];

    }
    public function query()
    {

        $query = CompanyLedger::query();

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

        return [$row->name, $row->amount, $row->mode, $row->created_at];
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
