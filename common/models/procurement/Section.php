<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_section".
 *
 * @property integer $section_id
 * @property integer $division_id
 * @property string $code
 * @property string $name
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_section';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['division_id', 'code', 'name'], 'required'],
            [['division_id'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'section_id' => 'Section ID',
            'division_id' => 'Division ID',
            'code' => 'Code',
            'name' => 'Name',
        ];
    }

    public function getDivision() {
        return $this->hasOne(Division::className(), ['division_id'=>'division_id']);
    }
}
