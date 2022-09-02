<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public static $monthNames = [
        'Január',
        'Február',
        'Március',
        'Április',
        'Május',
        'Június',
        'Július',
        'Augusztus',
        'Szeptember',
        'Október',
        'November',
        'December',
    ];

    public function wallets()
    {
        return $this->belongsToMany(Wallet::class)
            ->withPivot('amount')
            ->withTimestamps();
    }
}
