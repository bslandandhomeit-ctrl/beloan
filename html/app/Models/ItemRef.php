<?php
/**
 * Created by PhpStorm.
 * User: N.K
 * Date: 13/05/2021
 * Time: 10:09 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ItemRef extends Model {

    protected $table = 'ast_item_ref';

    public static function getFirstByCode($code, ...$columns) {
        if (isset($code) && !empty($code)) {
            $query = self::getActiveQuery()->where('code', $code);
            if (!isset($columns) || $columns == null) {
                $query->select('code','description','description_en');
            } else {
                $query->select($columns);
            }
            return $query->first();
        }
        return null;
    }

    public static function getActiveQuery($status = ItemConstant::STATUS_ACTIVE) {
        return self::where('status', $status)->orderBy('order_num');
    }
} 
