<?php

namespace common\models\inventory;

use Yii;

/**
 * This is the model class for table "tbl_inventory_withdrawaldetails".
 *
 * @property integer $inventory_withdrawaldetails_id
 * @property integer $inventory_withdrawal_id
 * @property integer $product_id
 * @property integer $quantity
 * @property string $price
 * @property integer $withdarawal_status_id
 *
 * @property InventoryWithdrawal $inventoryWithdrawal
 * @property Products $product
 */
class InventoryWithdrawaldetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_inventory_withdrawaldetails';
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
            [['inventory_withdrawal_id', 'product_id', 'quantity', 'withdarawal_status_id'], 'required'],
            [['inventory_withdrawal_id', 'product_id', 'quantity', 'withdarawal_status_id'], 'integer'],
            [['price'], 'number'],
            [['inventory_withdrawal_id'], 'exist', 'skipOnError' => true, 'targetClass' => InventoryWithdrawal::className(), 'targetAttribute' => ['inventory_withdrawal_id' => 'inventory_withdrawal_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inventory_withdrawaldetails_id' => 'Inventory Withdrawaldetails ID',
            'inventory_withdrawal_id' => 'Inventory Withdrawal ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'withdarawal_status_id' => 'Withdarawal Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventoryWithdrawal()
    {
        return $this->hasOne(InventoryWithdrawal::className(), ['inventory_withdrawal_id' => 'inventory_withdrawal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['product_id' => 'product_id']);
    }
}
