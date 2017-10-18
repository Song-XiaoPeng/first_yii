<?php

namespace apps\alicard\ar;

use Yii;

/**
 * This is the model class for table "alicard_record".
 *
 * @property integer $id
 * @property integer $cardid
 * @property integer $uid
 * @property string $template_id
 * @property string $biz_card_no
 * @property integer $type
 * @property string $password
 * @property integer $valid_date
 * @property integer $created
 */
class AlicardType extends \lib\db\ActiveRecord
{

    public static function primaryKey()
    {
        return [
            'id',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alicard_type';
    }

    /**
     * @inheritdoc
     */
    /*public function rules()
    {
        return [
            [['cardid', 'uid', 'type', 'valid_date', 'created'], 'integer'],
            [['template_id', 'biz_card_no', 'password'], 'string', 'max' => 32],
            [['uid'], 'unique'],
        ];
    }*/

}
