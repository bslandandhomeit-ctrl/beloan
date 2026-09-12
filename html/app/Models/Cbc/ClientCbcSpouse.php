<?php namespace App\Models\Cbc;

use App\Models\MyModels;

class ClientCbcSpouse extends MyModels
{

    protected $table = 'client_cbc_spouse';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at', 'updated_at'];
    //protected $timestapes = false;

    private $Add_rules = [
        'client_id' => '',
        'id_type_1' => '',
        'id_number_1' => '',
    ];

    public function SaveClientSpouse($data, $id, $save_id)
    {
        if ($data['draft'] != true) {
            if (is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
        }

        $spouseData = new self();
        if (!empty($save_id)) {
            $spouseData = self::find($save_id);
        }
        $spouseData->client_id = (int)$id;
        $spouseData->family_name_eng = $data['sp_family_name_en'];
        $spouseData->first_name_eng = $data['sp_first_name_eng'];
        $spouseData->last_name_eng = $data['sp_last_name_eng'];

        $spouseData->family_name_kh = $data['sp_family_name_kh'];
        $spouseData->first_name_kh = $data['sp_first_name_kh'];
        $spouseData->last_name_kh = $data['sp_last_name_kh'];
        $spouseData->birthday =  $data['sp_birth_date'];//date("Y-m-d", strtotime($data['sp_birth_date']));
        $spouseData->nationality = $data['sp_nationality'];
        $spouseData->occupation = $data['sp_occupation'];
        $spouseData->id_type = $data['id_type'];
        $spouseData->id_number = $data['sp_id_number'];
        $spouseData->id_issued_date =  $data['id_issued_date'];//date("Ymd", strtotime($data['id_issued_date']));
        $spouseData->exp_date = $data['exp_date'];//date("Y-m-d", strtotime());

        if (!$spouseData->save()) {
            return false;
        } else {
            return (int)$spouseData->attributes['id'];
        }
    }
}

