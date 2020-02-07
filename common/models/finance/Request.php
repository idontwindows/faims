<?php

namespace common\models\finance;

use Yii;
use common\models\cashier\Creditor;
use common\models\finance\Requesttype;
/**
 * This is the model class for table "tbl_request".
 *
 * @property integer $request_id
 * @property string $request_number
 * @property string $request_date
 * @property integer $request_type_id
 * @property integer $payee_id
 * @property string $particulars
 * @property double $amount
 * @property integer $status_id
 * @property integer $created_by
 */
class Request extends \yii\db\ActiveRecord
{
    const STATUS_CREATED = 10;   // end user
    const STATUS_SUBMITTED = 20; // end user
    const STATUS_VERIFIED = 30;  // finance verification team
    const STATUS_REVIEWED = 40;  // ard
    const STATUS_OBLIGATED = 50; // budget
    const STATUS_DISBURSED = 60; // accounting
    const STATUS_APPROVED = 70;  // rd
    const STATUS_COMPLETED = 80; // 
    const STATUS_RATED = 90;     // end user
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_request';
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
            [['request_number', 'request_date', 'request_type_id', 'payee_id', 'particulars', 'amount', 'created_by'], 'required'],
            [['request_date'], 'safe'],
            [['request_type_id', 'payee_id', 'status_id', 'created_by'], 'integer'],
            [['particulars'], 'string'],
            [['amount'], 'number'],
            [['request_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request ID',
            'request_number' => 'Request Number',
            'request_date' => 'Request Date',
            'request_type_id' => 'Request Type ID',
            'payee_id' => 'Payee ID',
            'particulars' => 'Particulars',
            'amount' => 'Amount',
            'status_id' => 'Status ID',
            'created_by' => 'Created By',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Requestattachment::className(), ['request_id' => 'request_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditor()
    {
        return $this->hasOne(Creditor::className(), ['creditor_id' => 'payee_id']);
    }
    
    public function getRequesttype()
    {
        return $this->hasOne(Requesttype::className(), ['request_type_id' => 'request_type_id']);
    }
    
    static function generateRequestNumber()
    {
        $year = date("Y", strtotime("now"));
        $month = date("m", strtotime("now"));
        
        $count = Request::find()->where(['YEAR(`request_date`)' => $year])->count();
        $count += 1;

        return $year.'-'.$month.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    
    function owner()
    {
        $owner = ($this->created_by == Yii::$app->user->identity->user_id) ? true : false;
        return $owner;
    }
    
    public function getVerifiedAttachments()
    {
        $verified = false;
        foreach($this->attachments as $attachment)
        {
            $verified = $attachment->status_id ? true : false;
        }
        return $verified;
    }
}
