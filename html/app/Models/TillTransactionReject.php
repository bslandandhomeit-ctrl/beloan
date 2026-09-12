<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;

class TillTransactionReject extends Model
{
    /**
     * The database table used by the model.
     * @var string
     */

    protected $table = 'till_transaction_reject';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    
}