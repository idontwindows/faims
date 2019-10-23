<?php

namespace frontend\modules\procurementplan\controllers;

use Yii;
use common\models\procurement\Division;
use common\models\procurement\Section;
use common\models\procurement\Unit;

use common\models\procurementplan\Item;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\PpmpSearch;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * PpmpController implements the CRUD actions for Ppmp model.
 */
class PpmpController extends Controller
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
     * Lists all Ppmp models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(isset($_GET['year']))
            $selected_year = $_GET['year'];
        else 
            $selected_year = '2019';
        //$selected_year = isset($_POST['year']) ? $_POST['year'] : '';
        
        $searchModel = new PpmpSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $sections = Section::find()->orderBy('division_id, section_id');
        $dataProvider = Ppmp::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $dataProvider,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    
        
        $ppmpDataProvider = new ActiveDataProvider([
            'query' => $sections,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $queryPpmpItems = Ppmpitem::find();
        $ppmpItemsDataProvider = new ActiveDataProvider([
            'query' => $queryPpmpItems,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    //'created_at' => SORT_DESC,
                    //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ppmpDataProvider' => $ppmpDataProvider,
            'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            'listDivisions' => $listDivisions,
            
            'listUnits' => $listUnits,
            'selected_year' => $selected_year,
        ]);
    }

    /**
     * Displays a single Ppmp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //$queryPpmpItems = Ppmpitem::find();
        $model = $this->findModel($id);
        $isMember = $model->isMember();
        $disableSubmitPpmp = ($model->status_id != 1) ? true : false;
        $disableAddItem = ($model->status_id != 1) ? true : false;

        $queryPpmpItems = Ppmpitem::find()->where([
                                    'ppmp_id' => $id, 
                                    'active' => 1]);
        
        $ppmpItemsDataProvider = new ActiveDataProvider([
            'query' => $queryPpmpItems,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        return $this->render('view', [
            'model' => $model,
            'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            'disableSubmitPpmp' => $disableSubmitPpmp,
            'disableAddItem' => $disableAddItem,
            'isMember' => $isMember
        ]);
    }
    
    public function actionCreate() 
    {
        $model = new Ppmp();
        
        $listYear = ['2019' => '2019', '2018' => '2018'];
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string) $model->ppmp_id]);
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'listDivisions' => $listDivisions,
                        'listUnits' => $listUnits,
                        'listYear' => $listYear,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'listDivisions' => $listDivisions,
                        'listUnits' => $listUnits,
                        'listYear' => $listYear,
            ]);
        }
    }

    /**
     * Updates an existing Ppmp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ppmp_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ppmp model.
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
     * Finds the Ppmp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ppmp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ppmp::findOne($id)) !== null) {
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
        **/
        $ppmpId = $_POST['ppmpId'];
        $itemId = $_POST['itemId'];
        $checked = $_POST['checked'];
        $year = $_POST['year'];

        
        $item = Item::findOne($itemId);
        $ppmp_item = Ppmpitem::find()->where([
                                    'ppmp_id' => $ppmpId, 
                                    'item_id' => $itemId])->one();
        
        if($ppmp_item)
        {
            //echo Json::encode(['message'=>$ppmp_item]);
            if($checked == 'true'){
                $ppmp_item->active = 1;
                $ppmp_item->save(false);
            }
            else{
                $ppmp_item->active = 0;
                $ppmp_item->save(false);
            }
        }else{
            $model = new Ppmpitem();
        
            $model->ppmp_id = $ppmpId;
            $model->item_id = $item->item_id;
            $model->item_category_id = $item->item_category_id;
            $model->code = $item->item_code;
            $model->description = $item->item_name;
            $model->unit = $item->unit_of_measure_id;
            $model->cost = $item->price_catalogue;
            $model->active = 1;
            $model->save(false);
        }
        
        echo Json::encode(['message'=>$ppmpId]);
    }

    public function actionUpdateqty() {
       if (Yii::$app->request->post('hasEditable')) {
           $ids = Yii::$app->request->post('editableKey');
           
           $index = Yii::$app->request->post('editableIndex');
           $attr = Yii::$app->request->post('editableAttribute');
           $qty = $_POST['Ppmpitem'][$index][$attr];
           $model = Ppmpitem::findOne($ids);
           $model->$attr = $qty; //$fmt->asDecimal($amt,2);
           if($model->save(false))
               return true;
               //echo Yii::$app->session->setFlash('success', "Quantity updated.");
           else
               return false;
               //return Yii::$app->session->setFlash('failure', "Quantity not updated. Please refresh this page.");
       }
    }
    
    public function actionSubmit()
    {
        
        $model = $this->findModel($_GET['id']);
        
        if (Yii::$app->request->isAjax) {
            if(!$model->isMember())
            {
                return $this->renderAjax('_info', ['message'=>'You are not Authorized to do this action.']);   
            }elseif($model->status_id == Ppmp::STATUS_SUBMITTED){
                return $this->renderAjax('_info', ['message'=>'This PPMP has been submitted for Approval.']);   
            }
        }
        if (Yii::$app->request->post()) {
            
            $model->status_id = 2;
            if($model->save(false)){
                Yii::$app->session->setFlash('success', "PPMP successfully submitted.");
                return $this->redirect(['view', 'id' => (string) $model->ppmp_id]);
            }
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_submit', [
                        'model' => $model,
            ]);
        } 
    }
}
