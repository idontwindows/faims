<?php

namespace frontend\modules\tagging\controllers;

use yii\web\Controller;
use Yii;
use frontend\modules\tagging\models\TagProfile;

/**
 * Default controller for the `Lab` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //$config = require(Yii::$app->basePath.'/config/_modules.php');
        //$config= require(Yii::$app->basePath.'/config/_modules.php');
        //echo "<pre>";
        //var_dump($config);
        //echo "</pre>";
        //$TagProfile=TagProfile();
        //$TagProfile->GetProfileID();
        return $this->render('index');
    }
}
class myCLass extends TagProfile{
   public function SetName($name) {
      
   }
   public function SetAddress($add) {
      
   }
}
