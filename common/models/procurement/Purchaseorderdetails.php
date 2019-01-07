<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_purchase_order_details".
 *
 * @property integer $purchase_order_details_id
 * @property integer $purchase_order_id
 * @property integer $bids_details_id
 * @property integer $purchase_request_details_status
 */
class Purchaseorderdetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_purchase_order_details';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('procurementdb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_id', 'bids_details_id', 'purchase_request_details_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_order_details_id' => 'Purchase Order Details ID',
            'purchase_order_id' => 'Purchase Order ID',
            'bids_details_id' => 'Bids Details ID',
            'purchase_request_details_status' => 'Purchase Request Details Status',
        ];
    }
}
