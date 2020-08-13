<?php

namespace common\models\finance;

use Yii;
use common\models\procurement\Expenditureclass;

/**
 * This is the model class for table "tbl_dv".
 *
 * @property integer $dv_id
 * @property integer $osdv_id
 * @property integer $request_id
 * @property integer $dv_number
 * @property string $dv_date
 */
class Dv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dv';
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
            [['osdv_id', 'request_id', 'dv_number', 'dv_date'], 'required'],
            [['osdv_id', 'request_id', 'dv_number'], 'integer'],
            [['dv_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dv_id' => 'Dv ID',
            'osdv_id' => 'Osdv ID',
            'request_id' => 'Request ID',
            'dv_number' => 'Dv Number',
            'dv_date' => 'Dv Date',
        ];
    }
    
    static function generateDvNumber($expenditurClassId, $createDate)
    {
        //OS-200-20-02-1516
        $year = date("y", strtotime($createDate));
        $month = date("m", strtotime($createDate));
        
        $dv_type = Expenditureclass::findOne($expenditurClassId)->account_code;

        $count = Dv::find()->where(['year(dv_date)' => date("Y", strtotime($createDate))])->count();
        $count += 1;
    
        return 'DV-'.$dv_type.'-'.$year.'-'.$month.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
