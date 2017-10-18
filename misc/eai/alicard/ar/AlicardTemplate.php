<?php

namespace apps\alicard\ar;

use Yii;

/**
 * This is the model class for table "alicard_template".
 *
 * @property integer $id
 * @property string $write_off_type
 * @property string $template_style_info
 * @property string $template_benefit_info
 * @property string $column_info_list
 * @property string $field_rule_list
 * @property string $card_action_list
 * @property string $open_card_conf
 * @property string $service_label_list
 * @property string $shop_ids
 * @property string $pub_channels
 * @property string $card_level_conf
 * @property string $mdcode_notify_conf
 * @property string $ext
 * @property string $template_id
 * @property integer $created
 * @property integer $modified
 * @property integer $creator
 */
class AlicardTemplate extends \lib\db\ActiveRecord
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
        return 'alicard_template';
    }

    /**
     * @inheritdoc
     */
    /*public function rules()
    {
        return [
            [['template_style_info', 'template_benefit_info', 'column_info_list', 'field_rule_list', 'ext'], 'required'],
            [['template_style_info', 'template_benefit_info', 'column_info_list', 'field_rule_list', 'card_action_list', 'open_card_conf', 'service_label_list', 'shop_ids', 'pub_channels', 'card_level_conf', 'mdcode_notify_conf', 'ext'], 'string'],
            [['created', 'modified', 'creator'], 'integer'],
            [['write_off_type', 'template_id'], 'string', 'max' => 32],
            [['template_id'], 'unique'],
        ];
    }*/

}
