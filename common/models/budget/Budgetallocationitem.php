<?php

namespace common\models\budget;

use Yii;

/**
 * This is the model class for table "tbl_budget_allocation_item".
 *
 * @property integer $budget_allocation_item_id
 * @property integer $budget_allocation_id
 * @property string $name
 * @property string $code
 * @property integer $category_id
 * @property double $amount
 */
class Budgetallocationitem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_budget_allocation_item';
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
            [['budget_allocation_id', 'name', 'code', 'category_id', 'amount'], 'required'],
            [['budget_allocation_id', 'category_id'], 'integer'],
            [['amount'], 'number'],
            [['name'], 'string', 'max' => 30],
            [['code'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'budget_allocation_item_id' => 'Budget Allocation Item ID',
            'budget_allocation_id' => 'Budget Allocation ID',
            'name' => 'Name',
            'code' => 'Code',
            'category_id' => 'Category ID',
            'amount' => 'Amount',
        ];
    }
}
