<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerModel extends Model
{
    use SoftDeletes;
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'email',
        'telephone',
        'birth',
        'address',
        'complement',
        'neighborhood',
        'zipcode'
    ];
    
    use HasFactory;
}
