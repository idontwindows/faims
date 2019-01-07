<?php

/*
 * Project Name: eulims * 
 * Copyright(C)2018 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 01 10, 18 , 8:35:40 AM * 
 * Module: TagProfile * 
 */

namespace frontend\modules\tagging\models;
use common\models\system\Profile;
use frontend\modules\tagging\components\ICLass;

/**
 * Description of TagProfile
 *
 * @author OneLab
 */
abstract class TagProfile extends Profile implements ICLass{
    //put your code here
    public function GetProfileID(){
        
    }
}
