<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_purchase_request".
 *
 * @property integer $purchase_request_id
 * @property string $purchase_request_number
 * @property string $purchase_request_sai_number
 * @property integer $division_id
 * @property integer $section_id
 * @property string $purchase_request_date
 * @property string $purchase_request_saidate
 * @property string $purchase_request_purpose
 * @property string $purchase_request_referrence_no
 * @property string $purchase_request_project_name
 * @property string $purchase_request_location_project
 * @property integer $purchase_request_requestedby_id
 * @property integer $purchase_request_approvedby_id
 * @property string $lineitembudgetlist
 */
class Purchaserequest extends \yii\db\ActiveRecord
{

    public $lineitembudgetlist;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_purchase_request';
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
            [['division_id', 'section_id', 'purchase_request_requestedby_id', 'purchase_request_approvedby_id'], 'integer'],
            [['division_id','section_id','purchase_request_purpose','purchase_request_referrence_no','purchase_request_project_name','purchase_request_location_project','purchase_request_requestedby_id','purchase_request_approvedby_id','purchase_request_date'], 'required'],
            [['purchase_request_date', 'purchase_request_saidate'], 'safe'],
            [['purchase_request_number', 'purchase_request_referrence_no', 'purchase_request_project_name', 'purchase_request_location_project'], 'string', 'max' => 100],
            [['purchase_request_sai_number', 'purchase_request_purpose','lineitembudgetlist'], 'string', 'max' => 5000],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_request_id' => 'Request ID',
            'purchase_request_number' => 'Request Number',
            'purchase_request_sai_number' => 'Sai Number',
            'division_id' => 'Division',
            'section_id' => 'Section',
            'purchase_request_date' => 'PR Date',
            'purchase_request_saidate' => 'PR Saidate',
            'purchase_request_purpose' => 'Request Purpose',
            'purchase_request_referrence_no' => 'Referrence No',
            'purchase_request_project_name' => 'Project Name',
            'purchase_request_location_project' => 'Location Project',
            'purchase_request_requestedby_id' => 'Requested By',
            'purchase_request_approvedby_id' => 'Approved By',
            'lineitembudgetlist' => 'Lineitem Budget',
        ];
    }

    public function getBidpurchaseid() {
        return $this->hasOne(BidsDetails::className(), ['purchase_request_id'=>'purchase_request_id']);
    }

}
