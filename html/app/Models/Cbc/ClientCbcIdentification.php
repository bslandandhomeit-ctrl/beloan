<?php namespace App\Models\Cbc;

use App\Models\MyModels;

class ClientCbcIdentification extends MyModels
{

    protected $table = 'client_cbc_identification';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;

    private $Add_rules = [
        'id_type_id'        => 'required', // CBC required
        'id_number'       => 'required', // CBC required
    ];

    public function SaveClientIden($data, $id, $save_id = null)
    {
        if($data['draft'] != true) {
            if(is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
        }
            $result = [];
            for($i = 0; $i <= count($data['id_type_id'])-1; $i++) {

                $identification = new self();

                if(!empty($save_id) || is_array($save_id)) {

                    if(!empty($data['iden_id'])) {

                        for($a = 0; $a < count($data['iden_id']); $a++) {
                            $del  = self::find($data['iden_id'][$a]);
                            if(!empty($del)) {
                                $del->delete();
                            }
                        }
                    }
                    $ident = self::find($save_id[$i]);
                    if(!empty($ident)) {
                        $identification = self::find($save_id[$i]);
                    }
                }
                $identification->client_id        =  (int)$id;
                $identification->id_type_id       =  $data['id_type_id'][$i];
                $identification->id_number        =  $data['id_number'][$i];
                $identification->issued_date      =  $data['issued_date'][$i];
                $identification->issued_by        =  $data['issued_by'][$i];
                $identification->id_expiry_date   =  $data['id_expiry_date'][$i];

                if(!$identification->save()) {
                    return false;
                }else {
                    $result[] = $identification->attributes['id'];
                }
            }
        return $result;
    }

    public function types(){

        return $this->hasOne('App\Models\Cbc\ClientCbcIdentificationTypes', 'id', 'id_type_id');

    }
}

