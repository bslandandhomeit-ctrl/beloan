<?php namespace App\Models;
/**
 * Created by PhpStorm.
 * User: ChamroeunDeab
 * Date: 5/29/2015
 * Time: 4:24 PM
 */

use Illuminate\Database\Eloquent\Model;

class LoanPaymentsDraft extends Model {
    protected $table = 'repayment_draft';
    protected $hidden = ['created_at','updated_at'];

    public function audit()
    {
        return $this->hasMany('App\Models\Audit','tbl_id')->where('tbl', 'repayment_draft');
    }    
}
