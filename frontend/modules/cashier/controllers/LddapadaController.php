<?php

namespace frontend\modules\cashier\controllers;

use Yii;
use common\models\cashier\Creditor;
use common\models\cashier\Lddapada;
use common\models\cashier\Lddapadaitem;
use common\models\cashier\LddapadaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\helpers\Json;
/**
 * LddapadaController implements the CRUD actions for Lddapada model.
 */
class LddapadaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Lddapada models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LddapadaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lddapada model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $lddapadaItemsDataProvider = new ActiveDataProvider([
            'query' => $model->getLddapadaItems(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);
        
        return $this->render('view', [
            'model' => $model,
            'lddapadaItemsDataProvider' => $lddapadaItemsDataProvider,
        ]);
    }

    /**
     * Creates a new Lddapada model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() 
    {
        $model = new Lddapada();
        
        /*$listYear = ['2019' => '2019', '2018' => '2018'];
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');*/
        
        /*if ($model->load(Yii::$app->request->post())) {
            $model->batch_number = Lddapada::generateBatchNumber();
            
            date_default_timezone_set('Asia/Manila');
            $model->batch_date = date("Y-m-d H:i:s", strtotime("now"));
            if($model->save())
                return $this->redirect(['view', 'id' => (string) $model->lddapada_id]);*/
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['view', 'id' => $model->lddapada_id]);    
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lddapada model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->lddapada_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lddapada model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Lddapada model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lddapada the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lddapada::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAdditems()
    {
        /** Post Data
            itemId : 4
            checked : true
            ppmpId : 2
            year : 2019
            
            creditorId:creditor_id,
            lddapadaId:lddapada_id,
            checked:checked},
        **/
        $lddapadaId = $_POST['lddapadaId'];
        $creditorId = $_POST['creditorId'];
        $checked = $_POST['checked'];
        
        $creditor = Creditor::findOne($creditorId);
        $lddapada_item = Lddapadaitem::find()->where([
                                    'lddapada_id' => $lddapadaId, 
                                    'creditor_id' => $creditorId])->one();
        
        if($lddapada_item)
        {
            //echo Json::encode(['message'=>$ppmp_item]);
            if($checked == 'true'){
                $lddapada_item->active = 1;
                $lddapada_item->save(false);
            }
            else{
                $lddapada_item->active = 0;
                $lddapada_item->save(false);
            }
        }else{
            $model = new Lddapadaitem();
        
            //lddapada_item_id 	lddapada_id 	creditor_id 	creditor_type_id 	name 	bank_name 	account_number 	gross_amount 	alobs_id 	expenditure_object_id 	check_number 	active
            $model->lddapada_id = $lddapadaId;
            $model->creditor_id = $creditor->creditor_id;
            $model->creditor_type_id = $creditor->creditor_type_id;
            $model->name = $creditor->name;
            $model->bank_name = $creditor->bank_name;
            $model->account_number = $creditor->account_number;
            $model->active = 1;
            $model->save(false);
        }
        
        echo Json::encode(['message'=>$lddapadaId]);
    }
    
    public function actionUpdatechecknumber() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Lddapadaitem'][$index][$attr];
           $model = Lddapadaitem::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
               //echo Yii::$app->session->setFlash('success', "Quantity updated.");
           else
               return false;
               //return Yii::$app->session->setFlash('failure', "Quantity not updated. Please refresh this page.");
       }
    }
    
    public function actionUpdatealobs() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Lddapadaitem'][$index][$attr];
           $model = Lddapadaitem::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
               //echo Yii::$app->session->setFlash('success', "Quantity updated.");
           else
               return false;
               //return Yii::$app->session->setFlash('failure', "Quantity not updated. Please refresh this page.");
       }
    }
}
