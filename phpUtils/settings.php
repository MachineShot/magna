<?php
define("DB_SERVER", "localhost");
define("DB_USER", "stud");
define("DB_PASS", "");
define("DB_NAME", "isp");
define("TBL_USERS", "naudotojas");
define("DEFAULT_LEVEL","");
define("ADMIN_LEVEL","vadovas");
define("CLIENT_LEVEL","uzsakovas");
define("WORKER_LEVEL","tiekejas");
define("UZBLOKUOTAS","255");
define("EMAIL_FROM_NAME", "Demo");
define("EMAIL_FROM_ADDR", "demo@ktu.lt");
define("EMAIL_WELCOME", false);

function inisession($arg) {   //valom sesijos kintamuosius
            if($arg =="full"){
	       		$_SESSION['ulevel']="";
				$_SESSION['umail']=0;
				$_SESSION['username_login']="";
				$_SESSION['pass_login']="";
                $_SESSION['mail_login']="";
                $_SESSION['prev']="";
            }
        $_SESSION['message']="";
        $_SESSION['username_error']="";
        $_SESSION['pass_error']="";
        $_SESSION['mail_error']="";
        $_SESSION['name_error']="";
        $_SESSION['surname_error']="";
        $_SESSION['number_error']="";
        $_SESSION['birthdate_error']="";
        $_SESSION['agencyname_error']="";
        $_SESSION['agencyadress_error']="";
        $_SESSION['agencydescription_error']="";
        $_SESSION['agencycode_error']="";
        $_SESSION['agencycity_error']="";
        $_SESSION['agencymailcode_error']="";
        }

echo
    "  <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            <title>Magna Adversitements</title>
            <script src='https://unpkg.com/vue/dist/vue.js'></script>
            <link rel='stylesheet' href='/isp/styles/global.css'>
            <link rel='stylesheet' href='/isp/styles/navigation.css'>
    ";
?>