<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leaders".
 *
 * @property integer $id
 * @property integer $voter_id
 * @property string $assigned_precinct
 * @property string $status
 *
 * @property Voters $voter
 * @property Members[] $members
 */
class VotersdbLeaders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leaders';
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
            [['voter_id', 'assigned_precinct'], 'required'],
            [['voter_id'], 'integer'],
            [['status'], 'string'],
            [['assigned_precinct'], 'string', 'max' => 10],
            [['voter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voters::className(), 'targetAttribute' => ['voter_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voter_id' => 'Voter ID',
            'assigned_precinct' => 'Assigned Precinct',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoter()
    {
        return $this->hasOne(Voters::className(), ['id' => 'voter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Members::className(), ['leader_id' => 'id']);
    }
}
