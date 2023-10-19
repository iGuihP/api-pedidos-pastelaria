<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
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
