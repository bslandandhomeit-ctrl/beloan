<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenaltyRecord extends Model {
    protected $table = "penalty_record";
    protected $hidden = ['created_at','updated_at'];
}
