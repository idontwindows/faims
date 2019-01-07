<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_testreportconfig".
 *
 * @property integer $testreportconfig_id
 * @property integer $lab_id
 * @property integer $number
 * @property string $config_year
 *
 * @property Lab $lab
 */
class Testreportconfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_testreportconfig';
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
            [['lab_id', 'number', 'config_year'], 'required'],
            [['lab_id', 'number'], 'integer'],
            [['config_year'], 'safe'],
            [['lab_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lab::className(), 'targetAttribute' => ['lab_id' => 'lab_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'testreportconfig_id' => 'Testreportconfig ID',
            'lab_id' => 'Lab ID',
            'number' => 'Number',
            'config_year' => 'Config Year',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLab()
    {
        return $this->hasOne(Lab::className(), ['lab_id' => 'lab_id']);
    }
}
