<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "members".
 *
 * @property integer $id
 * @property integer $leader_id
 * @property integer $voter_id
 * @property string $status
 * @property string $undecided
 *
 * @property Leaders $leader
 * @property Voters $voter
 */
class VotersdbMembers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'members';
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
            [['leader_id', 'voter_id'], 'required'],
            [['leader_id', 'voter_id'], 'integer'],
            [['status', 'undecided'], 'string'],
            [['leader_id'], 'exist', 'skipOnError' => true, 'targetClass' => VotersdbLeaders::className(), 'targetAttribute' => ['leader_id' => 'id']],
            [['voter_id'], 'exist', 'skipOnError' => true, 'targetClass' => VotersdbVoters::className(), 'targetAttribute' => ['voter_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'leader_id' => 'Leader ID',
            'voter_id' => 'Voter ID',
            'status' => 'Status',
            'undecided' => 'Undecided',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(VotersdbLeaders::className(), ['id' => 'leader_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoter()
    {
        return $this->hasOne(VotersdbVoters::className(), ['id' => 'voter_id']);
    }
}
