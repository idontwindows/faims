<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_project".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_project';
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
            [['code', 'name', 'description'], 'required'],
            [['description'], 'string'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }
}
