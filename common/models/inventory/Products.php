<?php

namespace common\models\inventory;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "tbl_products".
 *
 * @property integer $product_id
 * @property string $product_code
 * @property string $product_name
 * @property integer $producttype_id
 * @property integer $categorytype_id
 * @property string $description
 * @property string $price
 * @property string $srp
 * @property integer $qty_reorder
 * @property integer $qty_onhand
 * @property integer $qty_min_reorder
 * @property string $qty_per_unit
 * @property integer $discontinued
 * @property string $suppliers_ids
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property InventoryEntries[] $inventoryEntries
 * @property InventoryWithdrawaldetails[] $inventoryWithdrawaldetails
 * @property Categorytype $categorytype
 * @property Producttype $producttype
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_products';
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
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_name', 'categorytype_id', 'qty_reorder', 'qty_onhand', 'qty_min_reorder', 'qty_per_unit', 'created_by'], 'required'],
            [['producttype_id', 'categorytype_id', 'qty_reorder', 'qty_onhand', 'qty_min_reorder', 'discontinued', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['description', 'suppliers_ids'], 'string'],
            [['price', 'srp'], 'number'],
            [['product_code'], 'string', 'max' => 100],
            [['product_name', 'qty_per_unit'], 'string', 'max' => 50],
            [['categorytype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['categorytype_id' => 'categorytype_id']],
            [['producttype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producttype::className(), 'targetAttribute' => ['producttype_id' => 'producttype_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'product_name' => 'Product Name',
            'producttype_id' => 'Producttype ID',
            'categorytype_id' => 'Categorytype ID',
            'description' => 'Description',
            'price' => 'Price',
            'srp' => 'Srp',
            'qty_reorder' => 'Qty Reorder',
            'qty_onhand' => 'Qty Onhand',
            'qty_min_reorder' => 'Qty Min Reorder',
            'qty_per_unit' => 'Qty Per Unit',
            'discontinued' => 'Discontinued',
            'suppliers_ids' => 'Suppliers Ids',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventoryEntries()
    {
        return $this->hasMany(InventoryEntries::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventoryWithdrawaldetails()
    {
        return $this->hasMany(InventoryWithdrawaldetails::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryType()
    {
        return $this->hasOne(Categorytype::className(), ['categorytype_id' => 'categorytype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducttype()
    {
        return $this->hasOne(Producttype::className(), ['producttype_id' => 'producttype_id']);
    }
}
