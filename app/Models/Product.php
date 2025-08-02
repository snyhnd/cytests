<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function company()
{
    return $this->belongsTo(Company::class);
}

protected $fillable = [
    'product_name',
    'price',
    'stock',
    'comment',
    'company_id',
    'img_path'
];

public function sales()
{
    return $this->hasMany(Sale::class);
}

}
