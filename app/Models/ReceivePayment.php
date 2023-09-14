<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivePayment extends Model
{
    protected $table = 'receive_payments';
    public $timestamps = 0;

    protected $dates = [
        'paid_date',
        'due_date',
        'created_at',

    ];
    public function getFillable()
    {
        return $this->getTableColumns();
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(CreateOrder::class, 'order_id', 'id')->withDefault()->withTrashed();
    }
    public function payment_collected_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_collected_by_id', 'id')->withDefault()->withTrashed();
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'id')->withDefault()->withTrashed();
    }
}
