<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsOrderModel extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $table = 'products_order';
    protected $fillable = [
        'order_id',
        'product_id',
    ];
}
