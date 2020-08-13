<?php

namespace frontend\modules\system\controllers;

use Yii;
use common\models\system\Comment;
use common\models\system\CommentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Json;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
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
     * Lists all Comment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comment model.
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
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Comment();
        
        $searchModel = new CommentSearch();
        $searchModel->component_id = Comment::COMPONENT_ATTACHMENT;
        $searchModel->record_id = $_GET['record_id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        date_default_timezone_set('Asia/Manila');
        
        $model->component_id = $model::COMPONENT_ATTACHMENT;
        $model->record_id = $_GET['record_id'];
        $model->create_date=date("Y-m-d H:i:s");
        $model->created_by = Yii::$app->user->identity->user_id;
        
        if ($model->load(Yii::$app->request->post())) {
            //$model->save(false);
            if($model->save(false))
                Yii::$app->session->setFlash("success", "Comment posted.");
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->comment_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Comment model.
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
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPost() //call to add expenditure
    {
    
        $componentId = $_POST['componentid'];
        $recordId = $_POST['recordid'];
        $created_by = $_POST['createdby'];
        $message = $_POST['message'];

        $model = new Comment();
        $model->component_id  = $componentId;
        $model->record_id  = $recordId;
        $model->message  = $message;
        
        date_default_timezone_set('Asia/Manila');
        $model->create_date  = date("Y-m-d H:i:s");
        $model->created_by   = $created_by;
        $model->save(false);
        $out = 'Comment Succefully Posted';
    
        echo Json::encode(['message'=>$out]);
    }
}
