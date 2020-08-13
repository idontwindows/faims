<?php

namespace common\models\finance;

use Yii;
use common\models\procurement\Expenditureclass;
/**
 * This is the model class for table "tbl_os".
 *
 * @property integer $os_id
 * @property integer $osdv_id
 * @property integer $request_id
 * @property string $os_number
 * @property string $os_date
 */
class Os extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_os';
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
            [['osdv_id', 'request_id', 'os_number', 'os_date'], 'required'],
            [['osdv_id', 'request_id'], 'integer'],
            [['os_date'], 'safe'],
            [['os_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'os_id' => 'Os ID',
            'osdv_id' => 'Osdv ID',
            'request_id' => 'Request ID',
            'os_number' => 'Os Number',
            'os_date' => 'Os Date',
        ];
    }
    
    static function generateOsNumber($expenditurClassId, $createDate)
    {
        //OS-200-20-02-1516
        $year = date("y", strtotime($createDate));
        $month = date("m", strtotime($createDate));
        
        $os_type = Expenditureclass::findOne($expenditurClassId)->account_code;

        $count = Os::find()->where(['year(os_date)' => date("Y", strtotime($createDate))])->count();
        $count += 1;
    
        return 'OS-'.$os_type.'-'.$year.'-'.$month.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
