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
            [['request_id', 'account_id', 'transaction_type', 'tax_registered', 'debitcreditflag'], 'integer'],
            [['amount','rate1','rate2'], 'number'],
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
            'debitcreditflag' => 'Entry Type',
        ];
    }
    
    public function getAccount()  
    {  
      return $this->hasOne(Account::className(), ['account_id' => 'account_id']);  
    } 
    
    public function getTaxcategory()  
    {  
      return $this->hasOne(Taxcategory::className(), ['tax_category_id' => 'tax_category_id']);  
    } 
    
    public function getOsdv()  
    {  
      return $this->hasOne(Osdv::className(), ['osdv_id' => 'request_id']);  
    } 
}
