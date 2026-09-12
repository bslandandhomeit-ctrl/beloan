<?php namespace App\Models\Cbc;

use App\Models\MyModels;

class ClientCbcGeneral extends MyModels
{

    protected $table = 'client_cbc_general';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at', 'updated_at'];
    //protected $timestapes = false;
    protected $guarded = [];
    private $Add_rules = [
        'date_of_birth' => 'required',// CBC required
//        'family_name_en' => 'required',
//        'first_name_en' => 'required',
        'gender' => 'required',// CBC required
        'marital_status' => 'required',// CBC required
        'national_code' => 'required',// CBC required
        'applicant_type' => 'required',// CBC required
    ];

    public function SaveClientGeneral($data, $id, $photo, $signature, $save_id)
    {
        if ($data['draft'] != true) {
            if (is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
        }
        $generalData = new self();
        if(!empty($save_id)) {
            $generalData = self::find($save_id);
        }
        $generalData->client_id = (int)$id;
        $generalData->date_of_birth =  $data['date_of_birth'];
        $generalData->country_of_birth = (int)$data['country_of_birth'];
        $generalData->province_of_birth = (string)$data['province_of_birth'];
        $generalData->district_of_birth = (string)$data['district_of_birth'];
        $generalData->commune_of_birth = (string)$data['commune_of_birth'];
        $generalData->village_of_birth = (string)$data['village_of_birth'];
        $generalData->place_of_birth_adds = ($data['place_of_birth_adds'])?$data['place_of_birth_adds']:'';

        $generalData->family_name = $data['family_name_en'];
        $generalData->first_name = $data['first_name_en'];
        $generalData->second_name = $data[''];// not fill in form
        $generalData->third_name = $data[''];// not fill in form
        $generalData->unformatted_name = $data[''];// not fill in form
        $generalData->mother_name_unfomatted = $data[''];// not fill in form
        $generalData->family_name_kh = $data['family_name_kh'];
        $generalData->first_name_kh = $data['first_name_kh'];
        $generalData->second_name_kh = $data[''];// not fill in form
        $generalData->third_name_kh = $data[''];// not fill in form
        $generalData->unformatted_name_kh = $data[''];// not fill in form
        $generalData->mother_name_unfomatted_kh = $data[''];// not fill in form
        $generalData->gender = $data['gender'];
        $generalData->marital_status = $data['marital_status'];
        $generalData->national_code = $data['national_code'];
        $generalData->taxpayer_reg_no = $data['taxpayer_reg_no'];
        $generalData->applicant_type = $data['applicant_type'];
        if(!empty($photo)){
            $generalData->photo = ($photo) ? $photo : '';
        }if(!empty($signature)){
            $generalData->signature = ($signature) ? $signature : '';
        }
        if (!$generalData->save()) {
            return false;
        } else {
            return (int)$generalData->attributes['id'];
        }
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country\Countries', 'id', 'country_of_birth');
    }

    public function nationality()
    {
        return $this->hasOne('App\Models\Country\Countries', 'iso_code_3', 'national_code');
    }

    public function province()
    {
        return $this->hasOne('App\Models\Country\Provinces', 'prov_gis', 'province_of_birth');
    }

    public function District()
    {
        return $this->hasOne('App\Models\Country\Districts', 'distr_gis', 'district_of_birth');
    }

    public function Commune()
    {
        return $this->hasOne('App\Models\Country\Communes', 'comm_gis', 'commune_of_birth');
    }

    public function Village()
    {
        return $this->hasOne('App\Models\Country\Villages', 'vill_gis', 'village_of_birth');
    }

    public function clients(){

        return $this->belongsTo('App\Models\Client', 'client_id');
    }
}

