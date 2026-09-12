<?php namespace App\Models\Cbc;

use App\Models\MyModels;

class EconomicCbcSector extends MyModels
{

    protected $table = 'economic_cbc_sector';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at', 'updated_at','client_id'];
    //protected $timestapes = false;

    private $Add_rules = [
        'status'        => 'required|max:11',
    ];

    public function SaveClientEconomic($data, $id, $save_id)
    {
        if ($data['draft'] != true) {
            if (is_object($this->Check_validator($data, $this->Add_rules))) return $this->Check_validator($data, $this->Add_rules);
        }
        $economicData = new self();
        if (!empty($save_id)) {
            $economicData = self::find($save_id);
        }
        if (!$economicData->save()) {
            return false;
        } else {
            return (int)$economicData->attributes['id'];
        }
    }
}