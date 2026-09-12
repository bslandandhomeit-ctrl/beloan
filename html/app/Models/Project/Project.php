<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    protected $table = 'project';
    protected $hidden = ['created_at', 'updated_at'];
    
} 