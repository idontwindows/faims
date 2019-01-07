<?php
use common\models\system\User;

$Request_URI=$_SERVER['REQUEST_URI'];
//$_SERVER['SERVER_NAME']
if($Request_URI=='/'){//alias ex: http://admin.eulims.local
    $Backend_URI=Yii::$app->urlManagerBackend->createUrl('/');
    $Backend_URI=$Backend_URI."/uploads/user/photo/";
}else{//http://localhost/eulims/backend/web
    $Backend_URI='//localhost/fais/backend/web/uploads/user/photo/';
}
Yii::$app->params['uploadUrl']=$Backend_URI;
if(Yii::$app->user->isGuest){
    $CurrentUserName="Visitor";
    $CurrentUserAvatar=Yii::$app->params['uploadUrl'] . 'no-image.png';
    $CurrentUserDesignation='Guest';
    $UsernameDesignation=$CurrentUserName;
}else{
    $CurrentUser= User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
    $CurrentUserName=$CurrentUser->profile ? $CurrentUser->profile->fullname : $CurrentUser->username;
    $CurrentUserAvatar=$CurrentUser->profile ? Yii::$app->params['uploadUrl'].$CurrentUser->profile->getImageUrl() : Yii::$app->params['uploadUrl'] . 'no-image.png';
    $CurrentUserDesignation=$CurrentUser->profile ? $CurrentUser->profile->designation : '';
    if($CurrentUserDesignation==''){
       $UsernameDesignation=$CurrentUserName;
    }else{
       $UsernameDesignation=$CurrentUserName.'<br>'.$CurrentUserDesignation;
    }
}
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $CurrentUserAvatar ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $UsernameDesignation ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => 'Procurement Plan', 
                        'icon' => 'archive', 
                        //'url' => ['/settings'],
                        'visible'=> Yii::$app->user->can('access-procurement'),
                        'items' => [
                            ['label' => 'Line-Item Budget', 'icon' => 'money', 'url' => ['/procurement/lineitembudget/index']],
                            ['label' => 'PPMP', 'icon' => 'clipboard', 'url' => ['/procurement/ppmp/index']],
                            ['label' => 'APP', 'icon' => 'file-text', 'url' => ['/procurement/app/index']],
                        ]
                    ],
                    [
                        'label' => 'Purchasing', 
                        'icon' => 'tasks', 
                        //'url' => ['/settings'],
                        'visible'=> Yii::$app->user->can('access-procurement'),
                        'items' => [
                            ['label' => 'Purchase Request', 'icon' => 'cart-plus', 'url' => ['/procurement/purchaserequest/index']],
                            ['label' => 'Obligation Request', 'icon' => 'object-ungroup', 'url' => ['/procurement/obligationrequest/index']],
                            ['label' => 'Quotations, Bids and Awards', 'icon' => 'object-ungroup', 'url' => ['/procurement/bids/index']],
                            ['label' => 'Purchase Order', 'icon' => 'tags', 'url' => ['/procurement/purchaseorder/index']],
                            ['label' => 'Inspection and Acceptance', 'icon' => 'search', 'url' => ['/procurement/inspection']],
                            ['label' => 'Disbursement and Payment', 'icon' => 'ruble ', 'url' => ['/procurement/disbursement']],
                        ]
                    ],
                    [
                        'label' => 'Evaluation', 
                        'icon' => 'line-chart', 
                        'url' => ['/settings'],
                        'visible'=> Yii::$app->user->can('access-evaluation'),
                        'items' => [
                            ['label' => 'Performance Evaluation', 'icon' => 'commenting', 'url' => ['/procurement/performance']],
                            ['label' => 'PAR', 'icon' => 'briefcase', 'url' => ['/procurement/par']],
                            ['label' => 'ICS', 'icon' => 'book', 'url' => ['/procurement/ics']],
                        ]
                    ],
                    [
                        'label' => 'Suppliers', 
                        'icon' => 'truck', 
                        'visible'=> Yii::$app->user->can('access-suppliers'),
                        'items' => [
                            ['label' => 'Management', 'icon' => 'th', 'url' => ['/procurement/management']],
                            ['label' => 'Accreditation', 'icon' => 'th-list', 'url' => ['/procurement/accreditation']],
                        ]
                    ],
                    [
                        'label' => 'System tools',
                        'icon' => 'cogs',
                        'url' => '/#',
                        'visible'=> Yii::$app->user->can('access-system-tools'),
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],'visible'=> Yii::$app->user->can('access-gii')],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],'visible'=> Yii::$app->user->can('access-debug')],
                            ['label' => 'Package List', 'icon' => 'cog', 'url' => ['/package'],'visible'=> Yii::$app->user->can('access-package-list')],
                            ['label' => 'Package Manager', 'icon' => 'cog', 'url' => ['/package/manager'],'visible'=> Yii::$app->user->can('access-package')],
                            [
                                'label' => 'RBAC',
                                'icon' => 'fa fa-user-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Users', 'icon' => 'fa fa-user-o', 'url' => ['/admin/user'],'visible'=> Yii::$app->user->can('access-user')],
                                    ['label' => 'Assignment', 'icon' => 'dashboard', 'url' => ['/admin'],'visible'=> Yii::$app->user->can('access-assignment')],
                                    ['label' => 'Route', 'icon' => 'line-chart', 'url' => ['/admin/route'],'visible'=> Yii::$app->user->can('access-route')],
                                    ['label' => 'Roles', 'icon' => 'glide-g', 'url' => ['/admin/role'],'visible'=> Yii::$app->user->can('access-role')],
                                    ['label' => 'Permissions', 'icon' => 'resistance', 'url' => ['/admin/permission'],'visible'=> Yii::$app->user->can('access-permission')],
                                    ['label' => 'Menus', 'icon' => 'scribd', 'url' => ['/admin/menu'],'visible'=> Yii::$app->user->can('access-menu')],
                                    ['label' => 'Rules', 'icon' => 'reorder', 'url' => ['/admin/rule'],'visible'=> Yii::$app->user->can('access-rule')],
                                ],
                                'visible'=> Yii::$app->user->can('access-rbac')
                            ],
                        ],
                    ],

                    [
                        'label' => 'Account Setting',
                        'icon' => 'user-secret',
                        //'url' => ['/settings'],
                        //'visible'=> Yii::$app->user->can('access-settings'),
                        'items' => [
                            ['label' => 'Profile', 'icon' => 'user', 'url' => ['/profile'],'visible'=> Yii::$app->user->can('access-settings')],
                            ['label' => 'Login', 'icon' => 'user', 'url' => ['/site/login'],'visible'=>  Yii::$app->user->isGuest],
                            ['label' => 'Sign Out', 'icon' => 'user-times'  , 'url' => Yii::$app->urlManager->createUrl(['/site/logout']), 'visible' => !Yii::$app->user->isGuest, 'template' => '<a href="{url}" data-method="post">{icon}{label}</a>'],
                        ]
                    ],
                    /*
                    [
                        'label' => 'PAAI',
                        'icon' => 'user-secret',
                        //'url' => ['/settings'],
                        //'visible'=> Yii::$app->user->can('access-settings'),
                        'items' => [
                            ['label' => 'Registration', 'icon' => 'user', 'url' => ['/procurement/registration/summary'],'visible'=> Yii::$app->user->can('access-settings')],
                            ['label' => 'Check In', 'icon' => 'user', 'url' => ['/procurement/registration/checkin'],'visible'=> Yii::$app->user->can('access-settings')],
                            ['label' => 'Raffle', 'icon' => 'user', 'url' => ['/procurement/registration/raffle'],'visible'=> Yii::$app->user->can('access-settings')],
                            //['label' => 'Login', 'icon' => 'user', 'url' => ['/site/login'],'visible'=>  Yii::$app->user->isGuest],
                            //['label' => 'Sign Out', 'icon' => 'user-times'  , 'url' => Yii::$app->urlManager->createUrl(['/site/logout']), 'visible' => !Yii::$app->user->isGuest, 'template' => '<a href="{url}" data-method="post">{icon}{label}</a>'],
                        ]
                    ],
                    */
                ],
            ]
        ) ?>
    </section>

</aside>
