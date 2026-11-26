<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    public const FORMS = ['Tablet', 'Capsule', 'Syrup', 'Ointment', 'Injection'];

    protected $fillable = [
        'drug_code', 'drug_name', 'dosage', 'category', 'preparation_form',
        'manufacturer', 'bpom_number', 'stock', 'unit', 'price', 'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'price'       => 'decimal:2',
        'stock'       => 'integer',
    ];
}
