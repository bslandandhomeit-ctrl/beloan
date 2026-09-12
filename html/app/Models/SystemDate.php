<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SystemDate extends Model{
    protected $table = 'system_date';
    protected $primaryKey = 'id';
    public $timestamps = false;
} 