<?php
namespace app\components\helpers;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use app\components\helpers\Data;

class Data
{
    /**
    *   findRecords()
    *   Finds one/all records of one/all attributes.
    *
    *   @param      model $model - initialized model for querying
    *   @param      array/string $attribute - column/s for fetching | if all columns, value should be null
    *   @param      array - conditions for query;
    *               ['column_name' => 'column_value', 'column_name' => ['column_value1', 'column_value2']]
    *   @param      string quantity - one (returns one row) | all (returns all rows)
    */
    public static function findRecords($model, $attribute, $params, $quantity = 'one')
    {
        $quantities = ['one', 'all'];
        $query = '';
        $count = 0;

        foreach ($params as $pKey => $pValue) {
            if($count > 0) {
                $query .= ' AND ';
            }
            if (count($pValue) > 1) {
                $strArrVal = "'" . implode("', '", $pValue) . "'";
                $query .= $pKey . ' IN (' . $strArrVal . ')';
                unset($params[$pKey]);
            } else {
                if (is_array($pValue)) {
                    $params[$pKey] = implode(', ', $pValue);
                }
                $query .= $pKey . '=:' . $pKey;
            }
            $count++;
        }
        if (in_array($quantity, $quantities)) {
            $record =
                $model::find()
                    ->where($query, $params)
                    ->$quantity();
            return Data::handleRecords($record, $attribute, $quantity);
        }
    }

     private function handleRecords($record, $attribute, $quantity)
    {
        if (!empty($record)) {
            if (!empty($attribute)) {
                if (count($record) === 1) {
                    if (count($attribute) > 1) {
                        if ($quantity === 'one') {
                            foreach ($attribute as $a) {
                                if (isset($record[$a])) {
                                    $getAttribute[$a] = $record[$a];
                                }
                            }
                        } else {
                            foreach ($record as $r) {
                                foreach ($attribute as $a) {
                                    if (isset($r[$a])) {
                                        $getAttribute[$r['id']][$a] = $r[$a];
                                    }
                                }
                            }
                        }
                    } else {
                        if ($quantity === 'one') {
                            $getAttribute = ArrayHelper::getValue($record, $attribute);
                        } else {
                            foreach ($record as $r) {
                                $getAttribute[$r['id']][$attribute] = $r[$attribute];
                            }
                        }
                    }
                } else {
                    if (count($attribute) > 1) {
                        foreach ($record as $r) {
                            foreach ($attribute as $a) {
                                if (isset($r[$a])) {
                                    $getAttribute[$r['id']][$a] = $r[$a];
                                }
                            }
                        }
                    } else {
                        $getAttribute = ArrayHelper::getColumn($record, $attribute);
                    }
                }
                if (!empty($getAttribute)) {
                    return $getAttribute;
                } else {
                    return array();
                }
            } else {
                return $record;
            }
        } else {
            return array();
        }
    }

}
