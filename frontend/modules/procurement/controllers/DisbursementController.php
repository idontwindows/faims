<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Disbursement;
use common\models\procurement\DisbursementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisbursementController implements the CRUD actions for Disbursement model.
 */
class DisbursementController extends Controller
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
     * Lists all Disbursement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DisbursementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Disbursement model.
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
     * Creates a new Disbursement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
       /* $model = new Disbursement();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dv_id]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
       */






        $dvtype_data = [
            "MDS" => "MDS 101",
            "TF" => "Trust Fund",
            "ST" => "S & T Scholarship Fund",
            "BI" => "B I R Taxes",
        ];


        $dbursement = new Disbursement();

        if ($dbursement->load(Yii::$app->request->post())) {
            if ($dbursement->validate()) {
                //$osnumber = $this->GenerateOSNumber($obrequest->os_type);
               // $dbursement-> = $osnumber; //'PR-13-01-0028';
                $dbursement->save();
                //return $osnumber;
                return $this->redirect('index');
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $dbursement->errors;
                return $errors;
            }

        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $dbursement,
                    'dvtype_data' => $dvtype_data,
                ]);
            }else{
                return $this->render('create', [
                    'model' => $dbursement,
                    'dvtype_data' => $dvtype_data,
                ]);
            }
        }







    }

    /**
     * Updates an existing Disbursement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dv_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Disbursement model.
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
     * Finds the Disbursement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Disbursement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Disbursement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
