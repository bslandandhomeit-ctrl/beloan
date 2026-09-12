<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CommissionRate extends Model {
    protected $table = 'commission_rate';

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
    protected $guarded = [];
} 