<?php namespace App\Models\Cbc;

use App\Models\MyModels;

class ClientCbcIdentificationTypes extends MyModels
{

    protected $table = 'cbc_identification_types';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at', 'updated_at'];
    //protected $timestapes = false;

    private $Add_rules = [
        'code'     => 'required'
    ];

    public function SaveClientGeneral($data,$id)
    {
        try {

            DB::beginTransaction();
            if($data['draft'] != true) {
                if(is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
            }
            $identification = new self();
            $identification->code          =  $data[''];
            $identification->description   =  $data[''];
            $identification->status        =  $data[''];
            if(!$identification->save()) {

                return false;

            }else {

                DB::commit();
                return $identification->attributes['id'];
            }
        }catch(Exception $e){
            DB::rollback();
        }

    }
}

