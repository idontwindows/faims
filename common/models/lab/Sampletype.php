<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_sampletype".
 *
 * @property integer $sample_type_id
 * @property string $sample_type
 * @property integer $test_category_id
 *
 * @property Sample[] $samples
 * @property Testcategory $testCategory
 * @property Test[] $tests
 */
class Sampletype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_sampletype';
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
            [['sample_type', 'test_category_id'], 'required'],
            [['test_category_id'], 'integer'],
            [['sample_type'], 'string', 'max' => 75],
            [['test_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Testcategory::className(), 'targetAttribute' => ['test_category_id' => 'testcategory_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sample_type_id' => 'Sample Type ID',
            'sample_type' => 'Sample Type',
            'test_category_id' => 'Test Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSamples()
    {
        return $this->hasMany(Sample::className(), ['sample_type_id' => 'sample_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestCategory()
    {
        return $this->hasOne(Testcategory::className(), ['testcategory_id' => 'test_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTests()
    {
        return $this->hasMany(Test::className(), ['sample_type_id' => 'sample_type_id']);
    }
}
