<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsOrderModel extends Model
{
    protected $table = 'products_order';
    protected $fillable = [
        'order_id',
        'product_id',
    ];
    use HasFactory;
}
