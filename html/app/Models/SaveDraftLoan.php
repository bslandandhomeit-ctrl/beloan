<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveDraftLoan extends Model {
	protected $table = "save_draft";
    protected $hidden = ['created_at', 'updated_at'];
}
