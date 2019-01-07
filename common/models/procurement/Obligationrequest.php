<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_obligationrequest".
 *
 * @property integer $obligation_request_id
 * @property string $os_no
 * @property string $os_date
 * @property string $particulars
 * @property string $ppa
 * @property string $account_code
 * @property string $amount
 * @property string $payee
 * @property string $office
 * @property string $address
 * @property string $requested_by
 * @property string $requested_bypos
 * @property string $funds_available
 * @property string $funds_available_pos
 * @property string $purchase_no
 * @property string $os_type
 * @property string $dv_no
 * @property string $username
 */
class Obligationrequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_obligationrequest';
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
            [['os_date'], 'safe'],
            [['particulars'], 'string'],
            [['amount'], 'number'],
            [['os_no', 'ppa', 'account_code', 'payee', 'office', 'requested_by', 'requested_bypos', 'funds_available', 'funds_available_pos', 'purchase_no', 'os_type', 'dv_no', 'username'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obligation_request_id' => 'Obligation Request ID',
            'os_no' => 'Os No',
            'os_date' => 'Os Date',
            'particulars' => 'Particulars',
            'ppa' => 'Ppa',
            'account_code' => 'Account Code',
            'amount' => 'Amount',
            'payee' => 'Payee',
            'office' => 'Office',
            'address' => 'Address',
            'requested_by' => 'Requested By',
            'requested_bypos' => 'Requested Bypos',
            'funds_available' => 'Funds Available',
            'funds_available_pos' => 'Funds Available Pos',
            'purchase_no' => 'Purchase No',
            'os_type' => 'Os Type',
            'dv_no' => 'Dv No',
            'username' => 'Username',
        ];
    }
}
