<?php
# rezenlite/index.php
session_start();
date_default_timezone_set('Europe/Paris');
//Récuperation de la configuration
$set = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/Configs/setting.json'));

//Autoloader
require $_SERVER['DOCUMENT_ROOT'].'/Vendor/autoload.php';

//Ont détermine la langue selon l'utilisateur ou du navigateur en verifiant l'existance
if(Core\Auth::Logged()) {
    if(isset($_SESSION['lang']) AND !empty($_SESSION['lang'])) {
        $lang = $_SESSION['lang'];
    }
    else {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
    }
}
else {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2); 
}

if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/Langs/'.$lang.'.json')) {
    $lang = $set->lang_default;
}

//On charge la langue
$translate = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/Langs/'.$lang.'.json'));

//Verifie si le site est online
if($set->online) {
    $get = htmlspecialchars($_GET['page']);
    if(empty($get)) {
        $get = $set->default_page;
    }
    $patch = $_SERVER['DOCUMENT_ROOT'].'/Controllers/controller.'.$get.'.php';
    if(file_exists($patch)) {
        require $patch;
    }
    else {
        require $_SERVER['DOCUMENT_ROOT'].'/Controllers/controller.404.php';
    }
}
else {
    echo 'raison: '.$set->maintenance->reason;
}
?>