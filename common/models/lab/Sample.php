<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_sample".
 *
 * @property integer $sample_id
 * @property integer $rstl_id
 * @property integer $pstcsample_id
 * @property integer $package_id
 * @property integer $sample_type_id
 * @property string $sample_code
 * @property string $samplename
 * @property string $description
 * @property string $sampling_date
 * @property string $remarks
 * @property integer $request_id
 * @property integer $sample_month
 * @property integer $sample_year
 * @property integer $active
 *
 * @property Analysis[] $analyses
 * @property Packagelist[] $packagelists
 * @property Sampletype $sampleType
 * @property Request $request
 * @property Packagelist $package
 * @property TestreportSample[] $testreportSamples
 */
class Sample extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_sample';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('labdb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rstl_id', 'pstcsample_id', 'sample_type_id', 'sample_code', 'samplename', 'description', 'sampling_date', 'remarks', 'request_id', 'sample_month', 'sample_year'], 'required'],
            [['rstl_id', 'pstcsample_id', 'package_id', 'sample_type_id', 'request_id', 'sample_month', 'sample_year', 'active'], 'integer'],
            [['description'], 'string'],
            [['sampling_date'], 'safe'],
            [['sample_code'], 'string', 'max' => 20],
            [['samplename'], 'string', 'max' => 50],
            [['remarks'], 'string', 'max' => 150],
            [['sample_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sampletype::className(), 'targetAttribute' => ['sample_type_id' => 'sample_type_id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => Request::className(), 'targetAttribute' => ['request_id' => 'request_id']],
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => Packagelist::className(), 'targetAttribute' => ['package_id' => 'package_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sample_id' => 'Sample ID',
            'rstl_id' => 'Rstl ID',
            'pstcsample_id' => 'Pstcsample ID',
            'package_id' => 'Package ID',
            'sample_type_id' => 'Sample Type ID',
            'sample_code' => 'Sample Code',
            'samplename' => 'Samplename',
            'description' => 'Description',
            'sampling_date' => 'Sampling Date',
            'remarks' => 'Remarks',
            'request_id' => 'Request ID',
            'sample_month' => 'Sample Month',
            'sample_year' => 'Sample Year',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalyses()
    {
        return $this->hasMany(Analysis::className(), ['sample_id' => 'sample_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagelists()
    {
        return $this->hasMany(Packagelist::className(), ['sampletype_id' => 'sample_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleType()
    {
        return $this->hasOne(Sampletype::className(), ['sample_type_id' => 'sample_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(Packagelist::className(), ['package_id' => 'package_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestreportSamples()
    {
        return $this->hasMany(TestreportSample::className(), ['sample_id' => 'sample_id']);
    }
}
