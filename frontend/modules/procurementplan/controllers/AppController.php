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
class AppController extends Controller
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
    public function actionView()
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
        
        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ppmpDataProvider' => $ppmpDataProvider,
            'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            'listDivisions' => $listDivisions,
            
            'listUnits' => $listUnits,
            'selected_year' => $selected_year,
        ]);
    }
    
    public function actionIndex()
    {
        //$queryPpmpItems = Ppmpitem::find();
        //$id = 1;
        //$model = $this->findModel($id);
        //$isMember = $model->isMember();
        //$disableSubmitPpmp = ($model->status_id != 1) ? true : false;
        //$disableAddItem = ($model->status_id != 1) ? true : false;
        /*
        $modelRequest = Requestextend::find()
					->where('rstl_id =:rstlId AND status_id > :statusId AND lab_id = :labId AND DATE_FORMAT(`request_datetime`, "%Y-%m-%d") BETWEEN :fromRequestDate AND :toRequestDate', [':rstlId'=>$rstlId,':statusId'=>0,':labId'=>$labId,':fromRequestDate'=>$fromDate,':toRequestDate'=>$toDate])
					->groupBy(['DATE_FORMAT(request_datetime, "%Y-%m")'])
					->orderBy('request_datetime DESC');*/
        
        $queryPpmpItems = Ppmpitem::find()
                            ->where(['active' => 1])
                            ->groupBy('item_id');
        
                
        $ppmpItemsDataProvider = new ActiveDataProvider([
            'query' => $queryPpmpItems,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        return $this->render('index', [
            //'model' => $model,
            'ppmpItemsDataProvider' => $ppmpItemsDataProvider,
            //'disableSubmitPpmp' => $disableSubmitPpmp,
            //'disableAddItem' => $disableAddItem,
            'isMember' => false
        ]);
    }
}
