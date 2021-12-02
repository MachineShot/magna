<?php
    # login (set session variables)
    include 'startSession.php';
    $usertype = isset($_SESSION["usertype"]) ? $_SESSION["usertype"] : "";
    $usertype = "uzsakovas";
    #$usertype = "tiekejas";
    #$usertype = "vadovas";

    if ($usertype == "vadovas") {
        // nustatyt $_SESSION["agenturos_id"] (kuria agentura valdo prisijunges vadovas)
    }

    ########### padaryt, kad negaletu i neleidziamas posistemes eit naudotojas,
    ########### o ne tik, kad navigacija isjungtu tam tikros posistemes,
    ########### nes per adresa pvz. uzsakovas gali patekt i agenturos puslapius.

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
    # there is no closing head tag here in case there's a
    # need for additional imports in different pages
?>
