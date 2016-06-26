<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "members".
 *
 * @property integer $id
 * @property integer $leader_id
 * @property integer $member_id
 * @property string $status
 *
 * @property Leaders $leader
 * @property Voters $member
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
            [['leader_id', 'member_id'], 'required'],
            [['leader_id', 'member_id'], 'integer'],
            [['status'], 'string'],
            [['leader_id'], 'exist', 'skipOnError' => true, 'targetClass' => Leaders::className(), 'targetAttribute' => ['leader_id' => 'id']],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voters::className(), 'targetAttribute' => ['member_id' => 'id']],
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
            'member_id' => 'Member ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(Leaders::className(), ['id' => 'leader_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Voters::className(), ['id' => 'member_id']);
    }
}
