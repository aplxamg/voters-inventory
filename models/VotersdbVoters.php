<?php

namespace app\models;

use Yii;
use yii\db\Query;
use app\components\helpers\Data;

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
 * @property string $voting_status
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
            [['address', 'status', 'voting_status'], 'string'],
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
            'voters_no' => 'Voters No',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'birthdate' => 'Birthdate',
            'address' => 'Address',
            'precinct_no' => 'Precinct No',
            'status' => 'Status',
            'voting_status' => 'Voting Status',
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
        return $this->hasMany(Members::className(), ['voter_id' => 'id']);
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
        $model->voting_status   = 'N';

        return $model->save();
    }

    public function updateVote($id, $action)
    {
        $record  = self::findOne($id);
        if($record != null) {
            if($action == 'set') {
                $record->voting_status = 'Y';
            } else {
                $record->voting_status = 'N';
            }
        } else {
            return false;
        }

        return $record->save();
    }

    public function countByVote($voted = 'Y')
    {
        $params = ['status' => 'active', 'voting_status' => $voted];
        $record = self::find()->where($params)->all();
        return count($record);
    }


    public function deleteData()
    {
        $connection = Yii::$app->votersdb;
        $transaction =  $connection->beginTransaction();
        try {
            // Delete all members
            $membersCount = count(VotersdbMembers::find()->all());
            if(VotersdbMembers::deleteAll() == $membersCount) {
                // Delete all users with type not admin
                $params = ['not', ['user_type' => 'admin']];
                $usersCount = count(Users::find()->where($params)->all());
                if(Users::deleteAll($params) == $usersCount) {
                    // Delete all leaders
                    $leadersCount = count(VotersdbLeaders::find()->all());
                    if(VotersdbLeaders::deleteAll() == $leadersCount) {
                        // Delete all voters
                        $votersCount = count(VotersdbVoters::find()->all());
                        if(VotersdbVoters::deleteAll() == $votersCount) {
                            $transaction->commit();
                            return true;
                        } else {
                            $transaction->rollBack();
                            return false;
                        }
                    } else {
                        $transaction->rollBack();
                        return false;
                    }
                } else {
                    $transaction->rollBack();
                    return false;
                }
            } else {
                $transaction->rollBack();
                return false;
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}
