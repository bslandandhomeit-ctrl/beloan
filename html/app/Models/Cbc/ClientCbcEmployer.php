<?php namespace App\Models\Cbc;

use App\Models\MyModels;

class ClientCbcEmployer extends MyModels
{

    protected $table = 'client_cbc_employer';
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;

    private $Add_rules = [
            'employer_type' => 'required',// CBC Option C=Current p = Previous
    ];

    public function SaveClientEmployer($data, $id, $save_id)
    {
        if ($data['draft'] != true) {
            if (is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
        }
        $result  = [];
        for ($i = 0; $i <=count($data['employer_type'])- 1; $i++) {

            $employer = new self();
            if (!empty($save_id)) {

                $employerData = self::find($save_id[$i]);
                if (!empty($employerData)) {

                    $employer = self::find($save_id[$i]);
                }
            }
            $employer->client_id = $id;
            $employer->employer_country_id = (int)$data['em_country'][$i];
            $employer->employer_province_id = (string)$data['em_province'][$i];
            $employer->employer_district_id = (string)$data['em_district'][$i];
            $employer->employer_commune_id = (string)$data['em_commune'][$i];
            $employer->employer_village_id = (string)$data['em_village'][$i];

            $employer->employer_type = $data['employer_type'][$i];
            $employer->self_employed = ($data['self_employed'][$i]) ? $data['self_employed'][$i] : 'N';
            $employer->employer_name = $data['employer_name_en'][$i];
            $employer->employer_name_kh = $data['employer_name_kh'][$i];
            $employer->economic_id = $data['economic_sector'][$i];
            $employer->business_type = $data['business_type'][$i];
            $employer->employer_address = ($data['employer_address'][$i])?$data['employer_address'][$i]:'WORK';
            $employer->employer_address_kh = $data['employer_address_kh'][$i];
            $employer->employer_address_city = ''; //City Code Must be blank for default address type WORK OR if country is not KHM. Must be code as per 7 City Tables
            $employer->employer_address_city_kh = ''; //City Code
            $employer->postal_code = $data['em_postal_code'][$i];
            $employer->occupation = $data['occupation'][$i];
            $employer->occupation_kh = $data['occupation_kh'][$i];
            $employer->date_of_employment = str_replace(["-"], "", $data['date_of_employment'][$i]);
            $employer->length_of_service = $data['length_of_service'][$i];
            $employer->contract_exp_date = str_replace(["-"], "", $data['contract_exp_date'][$i]);
            $employer->currency_id = $data['currency'][$i];
            $employer->monthly_basic_salary = $data['monthly_basic_salary'][$i];
            $employer->total_monthly_salary = $data['total_monthly_salary'][$i];

            if (!$employer->save()) {
                return false;
            }

            $result[] = $employer->attributes['id'];
        }
        if (!empty($data['em_id'])) {

            for ($a = 0; $a <= count($data['em_id']); $a++) {

                $del = self::find($data['em_id'][$a]);
                if (!empty($del)) {
                    $del->delete();
                }
            }
        }
        return $result;
    }

    public function currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
    }

    public function EconomicSector()
    {
        return $this->hasOne('App\Models\Cbc\EconomicCbcSector','id','economic_id');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country\Countries', 'id', 'employer_country_id');
    }

    public function province()
    {
        return $this->hasOne('App\Models\Country\Provinces', 'prov_gis', 'employer_province_id');
    }

    public function District()
    {
        return $this->hasOne('App\Models\Country\Districts', 'distr_gis', 'employer_district_id');
    }

    public function Commune()
    {
        return $this->hasOne('App\Models\Country\Communes', 'comm_gis', 'employer_commune_id');
    }

    public function Village()
    {
        return $this->hasOne('App\Models\Country\Villages', 'vill_gis', 'employer_village_id');
    }

}

