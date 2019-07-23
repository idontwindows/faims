<?php

namespace frontend\modules\procurementplan\controllers;

use Yii;
use common\models\procurement\Division;
use common\models\procurement\Unit;

use common\models\procurementplan\Ppmp;
use common\models\procurementplan\PpmpSearch;
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
        $searchModel = new PpmpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');
        //$listUnits = ArrayHelper::map($types, 'section_id', 'name');
        //$listUnits = ArrayHelper::map($types, 'unit_id', 'name');
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listDivisions' => $listDivisions,
            'listUnits' => $listUnits,
        ]);
    }

    /**
     * Displays a single Ppmp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Ppmp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new Ppmp();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->ppmp_id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }
    
    public function actionCreate() 
    {
        $model = new Ppmp();
        
        $year = ['2019' => '2019', '2018' => '2018'];
        
        $divisions = Division::find()->orderBy('name')->asArray()->all();
        $listDivisions = ArrayHelper::map($divisions, 'division_id', 'name');
        
        $units = Unit::find()->orderBy('name')->asArray()->all();
        $listUnits = ArrayHelper::map($units, 'unit_id', 'name');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string) $model->_id]);
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'listDivisions' => $listDivisions,
                        'listUnits' => $listUnits,
                        'listYear' => $year,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'listDivisions' => $listDivisions,
                        'listUnits' => $listUnits,
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
}
