<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_program".
 *
 * @property integer $program_id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property integer $yearStarted
 * @property string $imageIcon
 */
class Program extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_program';
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
            [['code', 'name', 'yearStarted'], 'required'],
            [['description'], 'string'],
            [['yearStarted'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['name', 'imageIcon'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'program_id' => 'Program ID',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'yearStarted' => 'Year Started',
            'imageIcon' => 'Image Icon',
        ];
    }
}
