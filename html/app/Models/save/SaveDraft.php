<?php namespace App\Models\save;

use App\Models\MyModels;
use Illuminate\Support\Facades\Auth;

class SaveDraft extends MyModels
{
    protected $table = 'save_draft';
    protected $hidden = ['created_at', 'updated_at'];
    //public $timestamps = false;

    public function SaveData($Client_id) {

        $draft = new self;
        $ifExit = self::where('client_id', $Client_id)->first();
        if(!empty($ifExit)) {
            $draft = self::find($ifExit->id);
        }
        $draft->client_id = $Client_id;
        $draft->users_id = Auth::user()->id;
        $draft->save();
        return $draft->attributes['client_id'];
    }

    public function Client(){

        return self::belongsTo('App\Models\Client', 'client_id');

    }
}