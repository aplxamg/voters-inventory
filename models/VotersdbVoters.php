<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "voters".
 *
 * @property integer $id
 * @property string $voters_no
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $birthdate
 * @property string $address
 * @property string $precinct_no
 * @property string $status
 *
 * @property Leaders[] $leaders
 * @property Members[] $members
 */
class VotersdbVoters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voters';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('votersdb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voters_no', 'first_name', 'last_name', 'birthdate', 'precinct_no'], 'required'],
            [['address', 'status'], 'string'],
            [['voters_no'], 'string', 'max' => 30],
            [['first_name', 'middle_name', 'last_name'], 'string', 'max' => 50],
            [['birthdate', 'precinct_no'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voters_no' => 'Voters Number',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'birthdate' => 'Birthdate',
            'address' => 'Address',
            'precinct_no' => 'Precinct Number',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaders()
    {
        return $this->hasMany(Leaders::className(), ['voter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Members::className(), ['member_id' => 'id']);
    }


    public function getVoter($id) {
        return VotersdbVoters::find()-> where(['id'=>$id])->one();
    }

    /*
    *   @author Anecita M. Gabisan
    *   @date   2016-06-28
    *   saveVoter
    *   saves / updates voter's information
    *   @params:
    *       [model]     [model]     new model
    *       [int]       [id]        id of voter (use for update voter)
    *       [array]     [values]    values from form
    */
    public function saveVoter($model, $id, $values)
    {
        if($id != null)
            $model = $this->getVoter($id);

        $model->voters_no       = strtoupper(trim($values['voters_no']));
        $model->first_name      = strtoupper(trim($values['first_name']));
        $model->middle_name     = strtoupper(trim($values['middle_name']));
        $model->last_name       = strtoupper(trim($values['last_name']));
        $model->birthdate       = strtoupper(trim($values['birthdate']));
        $model->address         = strtoupper(trim($values['address']));
        $model->precinct_no     = strtoupper(trim($values['precinct_no']));
        $model->status          = 'active';

        return $model->save();
    }

}
