<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@data', dirname(dirname(__DIR__)) . '\uploads');


$whitelist = array(
    '127.0.0.1',
    '::1',
    //'::8082',
    'localhost',
);
if (isset($_SERVER['SERVER_NAME'])) {
    if (!in_array($_SERVER['SERVER_NAME'], $whitelist)) {
        $Backend_URI = 'https://' . $_SERVER['SERVER_NAME'] . '/';
        $BaseURI = $Backend_URI.'/';
        $Backend_URI = $Backend_URI . "/uploads/user/photo/";
        #$FrontendBaseURI = 'http://' . $_SERVER['SERVER_NAME'] . ':8082/';
        $FrontendBaseURI = 'https://' . $_SERVER['SERVER_NAME'] . '/';
    } else {
        $Backend_URI = 'https://localhost/faims/backend/web/uploads/user/photo/';
        $BaseURI = "https://localhost/faims/backend/web/";
        $BaseURI2 = "https://localhost/faims/frontend/web/";
        $FrontendBaseURI = "https://localhost/faims/frontend/web/";
    }
    $GLOBALS['upload_url'] = $Backend_URI;
    $GLOBALS['base_uri'] = $BaseURI;
    $GLOBALS['frontend_base_uri'] = $FrontendBaseURI;
}

