<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SetCurrency
 *
 * @author theary
 */
class AssetRecord extends Model{
    //put your code here
    protected $table = 'fa_status_record';
    protected $hidden = ['created_at', 'updated_at'];
}
