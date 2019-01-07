<?php

namespace common\models\inventory;

use Yii;
use common\models\User;
/**
 * This is the model class for table "tbl_equipmentservice".
 *
 * @property integer $equipmentservice_id
 * @property integer $inventory_transactions_id
 * @property integer $servicetype_id
 * @property integer $requested_by
 * @property integer $startdate
 * @property integer $enddate
 * @property integer $request_status
 * @property string $attachment
 *
 * @property Servicetype $servicetype
 * @property InventoryEntries $inventoryTransactions
 * @property User $requesteduser
 */
class Equipmentservice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_equipmentservice';
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
            [['inventory_transactions_id', 'servicetype_id', 'requested_by'], 'required'],
            [['inventory_transactions_id', 'servicetype_id', 'requested_by', 'startdate', 'enddate', 'request_status'], 'integer'],
            [['attachment'], 'string', 'max' => 100],
            [['servicetype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Servicetype::className(), 'targetAttribute' => ['servicetype_id' => 'servicetype_id']],
            [['inventory_transactions_id'], 'exist', 'skipOnError' => true, 'targetClass' => InventoryEntries::className(), 'targetAttribute' => ['inventory_transactions_id' => 'inventory_transactions_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'equipmentservice_id' => 'Equipmentservice ID',
            'inventory_transactions_id' => 'Inventory Transactions ID',
            'servicetype_id' => 'Servicetype ID',
            'requested_by' => 'Requested By',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'request_status' => 'Request Status',
            'attachment' => 'Attachment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicetype()
    {
        return $this->hasOne(Servicetype::className(), ['servicetype_id' => 'servicetype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventoryTransactions()
    {
        return $this->hasOne(InventoryEntries::className(), ['inventory_transactions_id' => 'inventory_transactions_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequesteduser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'requested_by']);
    }
}
