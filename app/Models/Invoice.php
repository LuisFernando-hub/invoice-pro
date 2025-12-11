<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_number',
        'issuer_name',
        'issuer_address',
        'issuer_tax_id',
        'client_name',
        'client_address',
        'issue_date',
        'due_date',
        'items',
        'currency',
        'total_amount',
        'status',
        'language',
    ];

    protected $casts = [
        'items' => 'array',
        'issue_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
