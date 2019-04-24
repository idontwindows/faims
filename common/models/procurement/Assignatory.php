<?php

namespace common\models\procurement;

use Yii;

/**
 * This is the model class for table "tbl_assignatory".
 *
 * @property integer $assignatory_id
 * @property string $report_title
 * @property integer $assignatory_1
 * @property integer $assignatory_2
 * @property integer $assignatory_3
 * @property integer $assignatory_4
 * @property integer $assignatory_5
 * @property integer $assignatory_6
 */
class Assignatory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_assignatory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assignatory_1', 'assignatory_2', 'assignatory_3', 'assignatory_4', 'assignatory_5', 'assignatory_6'], 'integer'],
            [['report_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assignatory_id' => 'Assignatory ID',
            'report_title' => 'Report Title',
            'assignatory_1' => 'Assignatory 1',
            'assignatory_2' => 'Assignatory 2',
            'assignatory_3' => 'Assignatory 3',
            'assignatory_4' => 'Assignatory 4',
            'assignatory_5' => 'Assignatory 5',
            'assignatory_6' => 'Assignatory 6',
        ];
    }
}
