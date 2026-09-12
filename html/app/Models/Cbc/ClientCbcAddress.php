<?php namespace App\Models\Cbc;

use App\Models\MyModels;

class ClientCbcAddress extends MyModels
{

    protected $table = 'client_cbc_address';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at', 'updated_at'];
    //public $timestamps = false;

    private $Add_rules = [
        'addr_types' => 'required',// CBC required

    ];

    public function SaveClientAddress($data, $id, $addres_id)
    {
        if($data['draft'] != true) {
            if(is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
        }

        for ($i = 0; $i <= count($data['addr_types'])-1; $i++) {

            $city_code = '';
            $postal_code = $data['postal_code'][$i];
            $address = new self();
            if(!empty($addres_id)) {

                 if(!empty($data['addr_id'])) {

                     for($a=0; $a <count($data['addr_id']);$a++) {
                         $del  = self::find($data['addr_id'][$a]);
                         if(!empty($del)) {
                             $del->delete();
                         }
                     }
                 }
                $addressData = self::find($addres_id[$i]);
                if(!empty($addressData)){
                    $address = self::find($addres_id[$i]);
                }
            }
            if (trim($data['provinces']) === 'PNH' && trim($data['country']) === 'KHM') {
                $city_code = 'PNH';
                $postal_code = ($data['provinces'][$i])?$data['provinces'][$i]:'';
            }

            $address->client_id = (int)$id;
            $address->country_id = (int)$data['country'][$i];
            $address->province_Id = (string)$data['provinces'][$i];
            $address->district_id = (string)$data['district'][$i];
            $address->commune_id = (string)$data['commune'][$i];
            $address->village_id = (string)$data['villages'][$i];
            $address->address_type = $data['addr_types'][$i];
            $address->address_en1 = $data['address_en1'][$i];
            $address->address_en2 = $data['address_en2'][$i];
            $address->address_kh1 = $data['address_kh1'][$i];
            $address->address_kh2 = $data['address_kh2'][$i];
            $address->city_code = $city_code;
            $address->postal_code = $postal_code;

            if($address->save()) {
                $array_ids[] = (int)$address->attributes['id'];
            }
        }

        return $array_ids;
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country\Countries', 'id', 'country_id');
    }

    public function province()
    {
        return $this->hasOne('App\Models\Country\Provinces', 'prov_gis', 'province_Id');
    }

    public function District()
    {
        return $this->hasOne('App\Models\Country\Districts', 'distr_gis', 'district_id');
    }

    public function Commune()
    {
        return $this->hasOne('App\Models\Country\Communes', 'comm_gis', 'commune_id');
    }

    public function Village()
    {
        return $this->hasOne('App\Models\Country\Villages', 'vill_gis', 'village_id');
    }

}

