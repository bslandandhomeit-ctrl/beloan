<?php namespace App\Models;
/**
 * Created by PhpStorm.
 * User: ChamroeunDeab
 * Date: 6/30/2015
 * Time: 4:24 PM
 */

use Illuminate\Database\Eloquent\Model;

class TransactionsPosting extends Model {
    protected $table = 'transactions_posting';
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;
}
