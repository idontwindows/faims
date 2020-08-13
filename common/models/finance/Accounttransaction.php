<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_account_transaction".
 *
 * @property integer $account_transaction_id
 * @property integer $request_id
 * @property integer $account_id
 * @property integer $transaction_type
 * @property double $amount
 */
class Accounttransaction extends \yii\db\ActiveRecord
{
    const DEBIT = 10;
    const CREDIT = 20;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_account_transaction';
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
            [['request_id', 'account_id', 'transaction_type', 'amount'], 'required'],
            [['request_id', 'account_id', 'transaction_type'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_transaction_id' => 'Account Transaction ID',
            'request_id' => 'Request ID',
            'account_id' => 'Account ID',
            'transaction_type' => 'Transaction Type',
            'amount' => 'Amount',
        ];
    }
    
    public function getAccount()  
    {  
      return $this->hasOne(Account::className(), ['account_id' => 'account_id']);  
    } 
}
