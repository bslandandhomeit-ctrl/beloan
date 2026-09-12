<?php namespace App\Models\Cbc;

use App\Models\MyModels;
use App\Models\save\SaveDraft;

class Client_cbc_info extends MyModels
{

    protected $table = 'clients';
    protected $fillable = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    //protected $visible = ['id','status','salutation'];
    //protected $attributes = ['id','status','salutation'];
    //protected $timestapes = false;

    private $Add_rules = [
        'salutation'    => 'required',
        'resident'      => 'required',
        'education'     => 'required',
    ];

    public function saveClientInfo($data, $user, $id)
    {
            if($data['draft'] != true) {
                if(is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
            }
            $clientData = new self();
            if(!empty($id)) {

                $clientData = self::find($id);

                if($data['submit'] == true) {

                    $saveDraft = SaveDraft::where('client_id', $id)->first();
                    if(!empty($saveDraft)) {

                        $saveDraft->where('id', $saveDraft->id)->delete();
                    }
                }
            }

            $clientData->client_name    = $data['family_name_en'].''.$data['first_name_en'];
            $clientData->phone1         = str_replace('-','',$data['phone_number'])[0];
            $clientData->phone2         = str_replace('-','',$data['phone_number'])[1];
            $clientData->account_cbc_type = $data['account_cbc_type'];
            $clientData->salutation     = $data['salutation'];
            $clientData->resident       = ($data['resident'])?$data['resident']:'N';
            $clientData->education      = $data['education'];
            $clientData->officer_id     = $data['officer_id'];
            $clientData->user_id        = (int)$user->id;
            $clientData->industries_id  = $data['industries'];
            $clientData->latitude       = $data['latitude'];
            $clientData->longitude      = $data['longitude'];
            $clientData->house_own      = $data['house_own'];
            $clientData->family_member_num      = $data['family_member_num'];
            $clientData->active_member_num      = $data['active_member_num'];

            if(!$clientData->save()) {
                return false;
            } else {
                return (int)$clientData->attributes['id'];
            }
    }

    public function general () {
        return $this->hasMany('App\Models\Cbc\ClientCbcGeneral', 'client_id');//->where('id',1); //id is belong to general ID
    }

    public function clientLoan() {

        return $this->hasMany('App\Models\ClientLoanAccounts', 'client_id');
    }

    public function loan() {

        return $this->hasMany('App\Models\Loan', 'client_id');
    }

    public function Address () {
        return $this->hasMany('App\Models\Cbc\ClientCbcAddress', 'client_id');
    }

    public function Contact () {
        return $this->hasMany('App\Models\Cbc\ClientCbcContact', 'client_id');
    }

    public function Employer () {
        return $this->hasMany('App\Models\Cbc\ClientCbcEmployer', 'client_id');
    }

    public function Identification () {
        return $this->hasMany('App\Models\Cbc\ClientCbcIdentification', 'client_id');
    }

    public function Spouse () {
        return $this->hasMany('App\Models\Cbc\ClientCbcSpouse', 'client_id');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id', 'officer_id');
    }

    public function saveDraft(){

        return self::hasMany('App\Models\save\SaveDraft','client_id');

    }

    public function Industries(){

        return $this->hasOne('App\Models\Industries\Industries', 'client_id');
    }

}

