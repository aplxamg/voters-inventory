<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "module_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $status
 *
 * @property Modules[] $modules
 */
class ModuleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'module_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['status'], 'string'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Modules::className(), ['cat_id' => 'id']);
    }

    /**
    *       @author     Anecita M. Gabisan
    *       @created    2016 - 06 - 25
    *       getCategories
    *       get data from module_category
    **/
    public static function getCategories($condition)
    {
        $records = self::findAll($condition);
        return $records;
    }


}
