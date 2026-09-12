<?php namespace App\Models\Cbc;

use App\Models\MyModels;

class ClientCbcContact extends MyModels
{

    protected $table = 'client_cbc_contact';
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;

    private $Add_rules = [
        'contact_number_type' => 'required',
    ];

    public function SaveClientContact($data, $id, $save_id = null)
    {

        if ($data['draft'] != true) {
            if (is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
        }
        $result = [];
        for ($i = 0; $i <= count($data['contact_number_type']) - 1; $i++) {

            $contactData = new self();
            if (!empty($save_id) || is_array($save_id)) {

                if (!empty($data['cnt_id'])) {

                    for ($a = 0; $a < count($data['cnt_id']); $a++) {
                        $del = self::find($data['cnt_id'][$a]);
                        if (!empty($del)) {
                            $del->delete();
                        }
                    }
                }
                $contact = self::find($save_id[$i]);
                if (!empty($contact)) {
                    $contactData = self::find($save_id[$i]);
                }
            }
            $contactData->client_id = (int)$id;
            $contactData->contact_number_type = $data['contact_number_type'][$i];
            $contactData->contact_number_country_code = $data['country_code'][$i];
            $contactData->contact_number_area = $data['area_code'][$i];
            $contactData->contact_number_number = str_replace('-','',$data['phone_number'])[$i];
            $contactData->contact_number_extension = $data['ext_code'][$i];
            $contactData->email_address = $data['email_address'];

            if ($contactData->save()) {
                $result[] = $contactData->attributes['id'];
            } else {
                return false;
            }
        }
        return $result;
    }
}

