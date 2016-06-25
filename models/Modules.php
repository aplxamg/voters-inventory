<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modules".
 *
 * @property integer $id
 * @property integer $cat_id
 * @property integer $main_id
 * @property integer $sort_order
 * @property string $name
 * @property string $url
 * @property string $status
 *
 * @property ModuleCategory $cat
 */
class Modules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'sort_order', 'name', 'url'], 'required'],
            [['cat_id', 'main_id', 'sort_order'], 'integer'],
            [['url', 'status'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModuleCategory::className(), 'targetAttribute' => ['cat_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_id' => 'Cat ID',
            'main_id' => 'Main ID',
            'sort_order' => 'Sort Order',
            'name' => 'Name',
            'url' => 'Url',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(ModuleCategory::className(), ['id' => 'cat_id']);
    }

    /**
    *       @author     Anecita M. Gabisan
    *       @created    2016 - 06 - 25
    *       getModules
    *       get all modules
    **/
    public static function getModules($condition)
    {
        $records = self::findAll($condition);
        return $records;
    }
}
