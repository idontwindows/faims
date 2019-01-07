<?php

namespace common\models\inventory;

use Yii;

/**
 * This is the model class for table "tbl_inventory_withdrawal".
 *
 * @property integer $inventory_withdrawal_id
 * @property integer $created_by
 * @property string $withdrawal_datetime
 * @property integer $lab_id
 * @property integer $total_qty
 * @property string $total_cost
 * @property string $remarks
 * @property integer $inventory_status_id
 *
 * @property InventoryStatus $inventoryStatus
 * @property InventoryWithdrawaldetails[] $inventoryWithdrawaldetails
 */
class InventoryWithdrawal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_inventory_withdrawal';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('inventorydb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'withdrawal_datetime', 'total_qty', 'inventory_status_id'], 'required'],
            [['created_by', 'lab_id', 'total_qty', 'inventory_status_id'], 'integer'],
            [['withdrawal_datetime'], 'safe'],
            [['total_cost'], 'number'],
            [['remarks'], 'string'],
            [['inventory_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => InventoryStatus::className(), 'targetAttribute' => ['inventory_status_id' => 'inventory_status_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inventory_withdrawal_id' => 'Inventory Withdrawal ID',
            'created_by' => 'Created By',
            'withdrawal_datetime' => 'Withdrawal Datetime',
            'lab_id' => 'Lab ID',
            'total_qty' => 'Total Qty',
            'total_cost' => 'Total Cost',
            'remarks' => 'Remarks',
            'inventory_status_id' => 'Inventory Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventoryStatus()
    {
        return $this->hasOne(InventoryStatus::className(), ['inventory_status_id' => 'inventory_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventoryWithdrawaldetails()
    {
        return $this->hasMany(InventoryWithdrawaldetails::className(), ['inventory_withdrawal_id' => 'inventory_withdrawal_id']);
    }
}
