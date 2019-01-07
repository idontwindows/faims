<?php

namespace common\models\inventory;

use Yii;
use common\models\Lab;
use common\models\Rstl;

/**
 * This is the model class for table "tbl_inventory_entries".
 *
 * @property integer $inventory_transactions_id
 * @property integer $transaction_type_id
 * @property integer $rstl_id
 * @property integer $product_id
 * @property string $manufacturing_date
 * @property string $expiration_date
 * @property integer $created_by
 * @property integer $suppliers_id
 * @property string $po_number
 * @property integer $quantity
 * @property string $amount
 * @property string $total_amount
 * @property string $Image1
 * @property string $Image2
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Equipmentservice[] $equipmentservices
 * @property EquipmentstatusEntry[] $equipmentstatusEntries
 * @property Suppliers $suppliers
 * @property Products $product
 * @property Transactiontype $transactionType
 * @property User $user
 * @property Lab $lab
 * @property Rstl $rstl
 */
class InventoryEntries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_inventory_entries';
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
            [['transaction_type_id', 'rstl_id', 'product_id', 'created_by', 'suppliers_id', 'quantity'], 'required'],
            [['transaction_type_id', 'rstl_id', 'product_id', 'created_by', 'suppliers_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['manufacturing_date', 'expiration_date'], 'safe'],
            [['amount', 'total_amount'], 'number'],
            [['po_number'], 'string', 'max' => 50],
            [['Image1', 'Image2'], 'string', 'max' => 100],
            [['suppliers_id'], 'exist', 'skipOnError' => true, 'targetClass' => Suppliers::className(), 'targetAttribute' => ['suppliers_id' => 'suppliers_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'product_id']],
            [['transaction_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transactiontype::className(), 'targetAttribute' => ['transaction_type_id' => 'transactiontype_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inventory_transactions_id' => 'Inventory Transactions ID',
            'transaction_type_id' => 'Transaction Type ID',
            'rstl_id' => 'Rstl ID',
            'product_id' => 'Product ID',
            'manufacturing_date' => 'Manufacturing Date',
            'expiration_date' => 'Expiration Date',
            'created_by' => 'Created By',
            'suppliers_id' => 'Suppliers ID',
            'po_number' => 'Po Number',
            'quantity' => 'Quantity',
            'amount' => 'Amount',
            'total_amount' => 'Total Amount',
            'Image1' => 'Image1',
            'Image2' => 'Image2',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentservices()
    {
        return $this->hasMany(Equipmentservice::className(), ['inventory_transactions_id' => 'inventory_transactions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentstatusEntries()
    {
        return $this->hasMany(EquipmentstatusEntry::className(), ['inventory_transactions_id' => 'inventory_transactions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuppliers()
    {
        return $this->hasOne(Suppliers::className(), ['suppliers_id' => 'suppliers_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionType()
    {
        return $this->hasOne(Transactiontype::className(), ['transactiontype_id' => 'transaction_type_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'created_by']);
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getRstl()
    {
        return $this->hasOne(Rstl::className(), ['rstl_id' => 'rstl_id']);
    }
    
}
