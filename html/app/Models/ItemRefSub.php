<?php
/**
 * Created by PhpStorm.
 * User: N.K
 * Date: 15/05/2021
 * Time: 05:35 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ItemRefSub extends Model {

    protected $table = 'ast_item_ref_sub';

    public static function getFirstByCode($code, ...$columns) {
        if (isset($code) && !empty($code)) {
            $query = self::getActiveQuery()->where('code', $code);
            if (!isset($columns) || $columns == null) {
                $query->select('code','description','description_en', 'order_num');
            } else {
                $query->select($columns);
            }
            return $query->first();
        }
        return null;
    }

    public static function getAllByRefCode($code, ...$columns) {
        if (isset($code) && !empty($code)) {
            $query = self::getActiveQuery()->where('item_ref', $code);
            if (!isset($columns) || $columns == null) {
                $query->select('code','description','description_en', 'order_num');
            } else {
                $query->select($columns);
            }
            return $query->get();
        }
        return null;
    }

    public static function getActiveQuery($status = ItemConstant::STATUS_ACTIVE) {
        return self::where('status', $status)->orderBy('order_num');
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'order_num' => $this->order_num
        ];
    }
} 
