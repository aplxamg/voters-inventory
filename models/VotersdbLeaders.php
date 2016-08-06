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

    public function getSummaryBLeaders()
    {
        $query = new Query();
        $query->select('l.id, v.first_name, v.middle_name, v.last_name, l.assigned_precinct')
              ->from('leaders l')
              ->innerJoin('voters v', 'v.id = l.voter_id and v.status = "active"')
              ->where('l.status = "active"');
        $command = $query->createCommand(Yii::$app->votersdb);
        $rows = $command->queryAll();

        if(count($rows) != 0) {
            $model = new VotersdbMembers;
            $records = [];
            $leaders = [];
            foreach($rows as $row) {
                $temp = [];
                if(!empty($row['middle_name'])) {
                    $temp['name'] = ucfirst(strtolower($row['last_name'])).", ".ucwords(strtolower($row['first_name']))." ".ucfirst(strtolower($row['middle_name']));
                } else {
                    $temp['name'] = ucfirst(strtolower($row['last_name'])).", ".ucwords(strtolower($row['first_name']));
                }
                $temp['voted'] = $this->getVoterCountByMembers($row['id'], 'Y');
                $temp['not_voted'] = $this->getVoterCountByMembers($row['id'], 'N');
                array_push($records, $temp);
                $leaders[$row['id']] = $temp['name'];
            }
            return ['records' => $records, 'leaders' => $leaders];
        }

        return $rows;

    }

    public function getVoterCountByMembers($leader_id, $voting_status)
    {
        $connection = Yii::$app->votersdb;
        $query  = 'select m.* from members m where leader_id=:leader and m.status="active" and voter_id in';
        $query .= '(select id from voters v where status="active" and voting_status=:voting_status)';
        $command=$connection->createCommand($query);
        $command->bindParam(":leader",$leader_id);
        $command->bindParam(":voting_status",$voting_status);
        $rows = $command->execute();

        return $rows;
    }
}
