<?php

namespace app\models;

use Yii;
use yii\db\Query;
use app\components\helpers\Data;

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
            [['voter_id'], 'required'],
            [['voter_id'], 'integer'],
            [['status'], 'string'],
            [['assigned_precinct'], 'string', 'max' => 10],
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
        $query->select('v.id, l.id as leader_id,v.voters_no, v.first_name, v.middle_name, v.last_name, l.assigned_precinct')
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
              ->leftJoin('_votersdb.members m', 'v.id = m.voter_id and m.status="active"')
              ->where(['v.status' => 'active', 'l.voter_id' => NULL, 'm.voter_id' => NULL])
              ->andWhere(['like', 'CONCAT(first_name, " ", middle_name, " ", last_name)', $keyword])
              ->limit(5);
        $command = $query->createCommand(Yii::$app->votersdb);
        $rows = $command->queryAll();
        return $rows;
    }

    public function saveMembers($id, $precinct_no, $members, $existingMembers)
    {
        $connection = Yii::$app->votersdb;
        $transaction =  $connection->beginTransaction();

        try {
            $record = self::findOne($id);
            $record->assigned_precinct = $precinct_no;
            if($record->save()) {
                $addMembers = array_diff($members, $existingMembers);
                $delMembers = array_diff($existingMembers, $members);

                if(count($addMembers) != 0) {
                    foreach($addMembers as $value) {
                        $memberModel = new VotersdbMembers;
                        $memberModel->leader_id = $id;
                        $memberModel->voter_id = $value;
                        $memberModel->status = 'active';
                        if(!$memberModel->save()) {
                            var_dump($memberModel->errors);
                            $transaction->rollBack();
                            return false;
                        }
                    }
                }

                if(count($delMembers) != 0) {
                    $memberModel = new VotersdbMembers;
                    foreach($delMembers as $value) {
                        $params = ['status' => 'active', 'voter_id' => $value];
                        $member = Data::findRecords($memberModel, null, $params);
                        if(count($member) != 0) {
                            $member->status = 'deleted';
                            if(!$member->save()) {
                                var_dump($member->errors);
                                $transaction->rollBack();
                                return false;
                            }
                        }
                    }
                }

                $transaction->commit();
                return true;

            } else {
                var_dump($record->errors);
                $transaction->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public function saveLeader($model, $id, $action)
    {
        $params = ['voter_id' => $id];
        $record  = self::find()->where($params)->one();
        if($record == null && $action == 'appoint') { // Add Leader
            $model->voter_id = $id;
            $model->assigned_precinct = '';
            $model->status = 'active';
        } else if ($record != null && $action == 'appoint') { // Update Leader
            $model = $record;
            $model->status = 'active';
        } else if ($record != null && $action == 'remove') { // Remove Leader
            $model = $record;
            $model->status = 'deleted';
        }

        return $model->save();
    }
}
