<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_packagelist".
 *
 * @property integer $package_id
 * @property integer $rstl_id
 * @property integer $testcategory_id
 * @property integer $sampletype_id
 * @property string $name
 * @property string $rate
 * @property string $tests
 *
 * @property Testcategory $testcategory
 * @property Sample $sampletype
 * @property Sample[] $samples
 */
class Packagelist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_packagelist';
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
            [['rstl_id', 'testcategory_id', 'sampletype_id', 'name', 'tests'], 'required'],
            [['rstl_id', 'testcategory_id', 'sampletype_id'], 'integer'],
            [['rate'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['tests'], 'string', 'max' => 100],
            [['testcategory_id'], 'exist', 'skipOnError' => true, 'targetClass' => Testcategory::className(), 'targetAttribute' => ['testcategory_id' => 'testcategory_id']],
            [['sampletype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sample::className(), 'targetAttribute' => ['sampletype_id' => 'sample_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'package_id' => 'Package ID',
            'rstl_id' => 'Rstl ID',
            'testcategory_id' => 'Testcategory ID',
            'sampletype_id' => 'Sampletype ID',
            'name' => 'Name',
            'rate' => 'Rate',
            'tests' => 'Tests',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestcategory()
    {
        return $this->hasOne(Testcategory::className(), ['testcategory_id' => 'testcategory_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampletype()
    {
        return $this->hasOne(Sample::className(), ['sample_id' => 'sampletype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSamples()
    {
        return $this->hasMany(Sample::className(), ['package_id' => 'package_id']);
    }
}
