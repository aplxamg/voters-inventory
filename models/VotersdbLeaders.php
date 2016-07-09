<?php

namespace app\models;

use Yii;
use yii\db\Query;

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

    public function getLeadersList(){
        $query = new Query();
        $query->select('v.id, v.voters_no, v.first_name, v.middle_name, v.last_name, l.assigned_precinct')
              ->from('_votersdb.voters as v')
              ->innerJoin('_votersdb.leaders as l', 'v.id = l.voter_id and l.`status` = "active"')
              ->where('v.status = "active"');
        $command = $query->createCommand(Yii::$app->votersdb);
        $rows = $command->queryAll();
        return $rows;
    }

    public function getList($keyword)
    {
        $query = new Query();
        $query->select('v.id, first_name, middle_name, last_name')
              ->from('_votersdb.voters as v')
              ->leftJoin('_votersdb.leaders l', 'l.voter_id = v.id and l.status="active"')
              ->leftJoin('_votersdb.members m', 'v.id = m.member_id and m.status="active"')
              ->where(['v.status' => 'active', 'l.voter_id' => NULL, 'm.member_id' => NULL])
              ->andWhere(['like', 'CONCAT(first_name, " ", middle_name, " ", last_name)', $keyword])
              ->limit(5);
        $command = $query->createCommand(Yii::$app->votersdb);
        $rows = $command->queryAll();
        return $rows;
    }
}
