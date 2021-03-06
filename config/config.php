<?php
//SITE INFORMATION
$config = array();
//GET SITE INFORMATION

//BASE URL
define('BASE_URL', 'sego.dev');
//VERSION OF APP
define('VERSION', '0.0.1');
//DESCRIPTION
define('DESCRIPTION' , 'Friendly neighborhood process wizard');

//SET DATABASE INFORAMTION
$config['database']=array(
    'HOST_NAME' =>  '127.0.0.1',
    'USER_NAME' =>  'root',
    'PASSWORD'  =>  '',
    'DATABASE'  =>  'se'
);

//SET DEFAULT ROUTE
$config['route'] = array(
    //DEFAULT CONTROLLER
    'controller' => 'board',
    //DEFAULT METHOD
    'method' =>'index'
);

//SET USER LOGIN SESSION INFORMATION

$config['session']=array(
    //WILL THIS APPLICATION BE GATED
    'enabled' => true,
    //THE SESSION NAME YOU WOULD LIKE TO SAVE SESSIONS UNDER
    'session_name'=> 'f_sess',
    //THE DATABASE TABLE WHERE THE SESSIONS WILL BE SAVED
    //THIS GETS CREATED AUTOMAGICALLY
    'database_session_table_name'=>'sess',
    //THE FALLBACK CONTROLLER A USER GOES TO WHEN THERE IS NOT A SESSION
    'login_controller' => 'gate',
    //EXEMPT CONTROLLERS FROM THE GATING
    'non_gated_controllers' => array(
        'gate',
        'analytics'
    ),
    'max_session_hours' => 5
);

//MONGODB
$config['mongo']=array(
    'enabled' => true,
    'database' => 'se',
    'address' => '127.0.0.1'
);

//SECURITY SETTINGS
$config['security']= array(
    'hash_type' => 'sha256'
);

$config['facebook']= array(
    'callback' =>'http://sego.dev/index.php/sego/facebook_loader'
);

//SALESFORCE
$config['salesforce'] = array(
    'enabled' => true,
    'path_to_wsdl' =>'http://localhost/sfdc/wsdl.xml'
);


//SET DEFAULT TIMEZONE
date_default_timezone_set ("America/New_York");
