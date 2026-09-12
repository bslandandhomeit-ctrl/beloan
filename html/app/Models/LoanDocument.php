<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/18/2015
 * Time: 9:19 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LoanDocument extends Model{
    protected $table = 'loan_documents';
    protected $hidden = ['created_at', 'updated_at'];
} 