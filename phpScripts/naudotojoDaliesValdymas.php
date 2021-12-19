<?php
include '../../phpUtils/connectToDB.php';
include '../../phpUtils/settings.php';

function procregister()
{
    if (!isset($_SESSION['prev']) || $_SESSION['prev'] != "registracija") {
        header("Location: logout.php");
        exit();
    }
    $_SESSION['prev'] = "procregister";
    $_SESSION['message'] = "Registracija nesėkminga";

    $username = strtolower($_POST['username']);
    $pass = $_POST['pass'];
    $_SESSION['pass_login'] = $pass;
    $mail = $_POST['email'];
    $_SESSION['mail_login'] = $mail;
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $number = $_POST['number'];
    $birthdate = $_POST['birthdate'];

    list($dbuname) = checkdb($username); //patikrinam DB
    if ($dbuname) {
        $_SESSION['username_error'] = "<font size=\"2\" color=\"#ff0000\">* Tokiu vardu jau yra registruotas vartotojas</font>";
    } else {

        if (checkpass($pass, substr(hash('sha256', $pass), 5, 32)) & checkusername($username)
        &checkmail($mail)&checkname($name)&checksurname($surname)&checknumber($number)) {
            // antra tikrinimo dalis checkpass bus true
            $pass = substr(hash('sha256', $pass), 5, 32); // DB password skirti 32 baitai, paimam is maisos vidurio
            //if (!isset($_SESSION['ulevel']) && $_SESSION['ulevel'] == $user_roles[ADMIN_LEVEL]) $ulevel = $user_roles[WORKER_LEVEL]; // jei registravo adminas, imam jo nurodyta role
            //else $ulevel = $user_roles[DEFAULT_LEVEL];
            $ulevel = CLIENT_LEVEL;
            $sql =
                "INSERT INTO " .
                TBL_USERS .
                " (slapyvardis, email, vardas, pavarde, tel_nr, slaptazodis, gimimo_data, tipas)
    				VALUES(
    					'$username', '$mail', '$name', '$surname', '$number', '$pass', '$birthdate','$ulevel')
    				";

            if (db_send_query($sql)) {
                $_SESSION['message'] = "Registracija sėkminga";
                      header("Location:../../index.php");
                      exit();
            } else {
                $_SESSION['message'] = "Registracija nesėkminga";
            }
        }
    }
    header("Location: Registracija.php");
    exit();
}

function proclogin()
{
    if (!isset($_SESSION['prev']) || $_SESSION['prev'] != "prisijungimas") {
        header("Location: logout.php");
        exit();
    }
    $_SESSION['prev'] = "proclogin";
    $_SESSION['name_error'] = "";
    $_SESSION['pass_error'] = "";
    $user = strtolower($_POST['username']);

    if (isset($_POST['problem'])) {
        $_SESSION['message'] = "Turi būti įvestas galiojantis vartotojo vardas";
    } else {
        $_SESSION['message'] = "Pabandykite dar kartą";
    }
    if (checkusername($user)) {
        list($dbuname, $dbupass, $dbulevel, $dbumai) = checkdb($user); //patikrinam ir jei randam, nuskaitom DB
        if ($dbuname) {
            $pass = $_POST['pass'];
            if (checkpass($pass, $dbupass)) {
                $_SESSION['username_login'] = $user;
                $_SESSION['umail'] = $dbumai;
                $_SESSION['pass_login'] = $pass;
                $_SESSION['ulevel'] = $dbulevel;
                $_SESSION['message'] = "";
            }
        }
    }
    header("Location: Prisijungimas.php");
    exit();
}


function db_get_user_information()
{
    $username = $_SESSION['username_login'];
    $sql = "SELECT  *
                FROM `naudotojas`
                WHERE `slapyvardis` = '$username'";
    return db_send_query($sql);
}

function updateInformation()
{
    $username = $_SESSION['username_login'];
    $mail = $_POST['email'];
    $number = $_POST['number'];
    $_SESSION['mail_login'] = $mail;
     if (checkmail($mail)&checknumber($number))
    {
      $sql = "UPDATE `naudotojas`
                SET `email` = '$mail', `tel_nr` = '$number'
                WHERE `slapyvardis` = '$username'";
                if (db_send_query($sql))
                {


                                      header("Location: PaskyrosInformacijosPerziura.php");
                                      exit();
                            }
   }
   header("Location: PaskyrosInformacijosKeitimas.php");
   exit();
}

function deleteAccount()
{
    $username = $_SESSION['username_login'];
      $sql = "DELETE FROM `naudotojas`
                WHERE `slapyvardis` = '$username'";
                if (db_send_query($sql))
                {


                                      header("Location: Atsijungti.php");
                                      exit();
                }
   header("Location: PaskyrosTrynimas.php");
   exit();
}

function updatePassword()
{
    $username = $_SESSION['username_login'];
    $currentpasw = $_POST['currentpasw'];
    $newpasw = $_POST['newpasw'];
    list($dbuname, $dbupass, $dbulevel, $dbumai) = checkdb($username);
    if ($dbuname)
    {
                if (checkpass($currentpasw, $dbupass))
                {
                    $pass = substr(hash('sha256', $newpasw), 5, 32);
                    $_SESSION['pass_login'] = $pass;
                     $sql = "UPDATE `naudotojas`
                                    SET `slaptazodis` = '$pass'
                                    WHERE `slapyvardis` = '$username'";
                       if (db_send_query($sql))
                                       {


                                            header("Location: PaskyrosInformacijosPerziura.php");

                                            exit();
                                       }
                }
    }
      header("Location: PaskyrosSlaptazodzioKeitimas.php");
      exit();
}


function checkusername($username)
{
    // Vartotojo vardo sintakse
    if (!$username || strlen($username = trim($username)) == 0) {
        $_SESSION['username_error'] = "<font size=\"2\" color=\"#ff0000\">* Neįvestas vartotojo vardas</font>";
        "";
        return false;
    } elseif (!preg_match("/^([0-9a-zA-Z])*$/", $username)) {
        /* Check if username is not alphanumeric */
        $_SESSION['username_error'] = "<font size=\"2\" color=\"#ff0000\">* Vartotojo vardas gali būti sudarytas<br>
    				&nbsp;&nbsp;tik iš raidžių ir skaičių</font>";
        return false;
    } else {
        return true;
    }
}

function checknumber($username)
{
    // Vartotojo vardo sintakse
    if (!$username || strlen($username = trim($username)) == 0) {
        $_SESSION['number_error'] = "<font size=\"2\" color=\"#ff0000\">* Neįvestas telefono numeirs</font>";
        "";
        return false;
    } elseif (!preg_match("/^^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\.\/0-9]*$/", $username)) {
        /* Check if username is not alphanumeric */
        $_SESSION['number_error'] = "<font size=\"2\" color=\"#ff0000\">* Neteisingas formatas</font>";
        return false;
    } else {
        return true;
    }
}

function checkname($input)
{
    if (!$input || strlen($input = trim($input)) == 0) {
        $_SESSION['name_error'] = "<font size=\"2\" color=\"#ff0000\">* Neįvestas vartotojo vardas</font>";
        "";
        return false;
    } elseif (!preg_match("/^([a-zA-Z])*$/", $input)) {
        /* Check if username is not alphanumeric */
        $_SESSION['name_error'] = "<font size=\"2\" color=\"#ff0000\">* Vartotojo vardas gali būti sudarytas<br>
    				&nbsp;&nbsp;tik iš raidžių</font>";
        return false;
    } else {
        return true;
    }
}

function checksurname($input)
{
    if (!$input || strlen($input = trim($input)) == 0) {
        $_SESSION['surname_error'] = "<font size=\"2\" color=\"#ff0000\">* Neįvesta vartotojo pavardė</font>";
        "";
        return false;
    } elseif (!preg_match("/^([a-zA-Z])*$/", $input)) {
        /* Check if username is not alphanumeric */
        $_SESSION['surname_error'] = "<font size=\"2\" color=\"#ff0000\">* Vartotojo pavardė gali būti sudaryta<br>
    				&nbsp;&nbsp;tik iš raidžių</font>";
        return false;
    } else {
        return true;
    }
}

function checkpass($pwd, $dbpwd)
{
    //  slaptazodzio tikrinimas (tik demo: min 4 raides ir/ar skaiciai) ir ar sutampa su DB esanciu
    if (!$pwd || strlen($pwd = trim($pwd)) == 0) {
        $_SESSION['pass_error'] = "<font size=\"2\" color=\"#ff0000\">* Neįvestas slaptažodis</font>";
        return false;
    } elseif (!preg_match("/^([0-9a-zA-Z])*$/", $pwd)) {
        /* Check if $pass is not alphanumeric */
        $_SESSION['pass_error'] = "* Čia slaptažodis gali būti sudarytas<br>&nbsp;&nbsp;tik iš raidžių ir skaičių";
        return false;
    } elseif (strlen($pwd) < 4) {
        // per trumpas
        $_SESSION['pass_error'] = "<font size=\"2\" color=\"#ff0000\">* Slaptažodžio ilgis <4 simbolius</font>";
        return false;
    } elseif ($dbpwd != substr(hash('sha256', $pwd), 5, 32)) {
        $_SESSION['pass_error'] = "<font size=\"2\" color=\"#ff0000\">* Neteisingas slaptažodis</font>";
        return false;
    } else {
        return true;
    }
}

function checkdb($username)
{
    // iesko DB pagal varda, grazina {vardas,slaptazodis,lygis,id} ir nustato name_error
    $sql = "SELECT * FROM " . TBL_USERS . " WHERE slapyvardis = '$username'";
    $result = db_send_query($sql);
    $uname = $upass = $ulevel = $uid = $umail = $id = null;
    if (!$result || mysqli_num_rows($result) != 1) {
        // jei >1 tai DB vardas kartojasi, netikrinu, imu pirma
        // neradom vartotojo DB
        $_SESSION['name_error'] = "<font size=\"2\" color=\"#ff0000\">* Tokio vartotojo nėra</font>";
    } else {
        //vardas yra DB
        $row = mysqli_fetch_assoc($result);
        $uname = $row["slapyvardis"];
        $upass = $row["slaptazodis"];
        $ulevel = $row["tipas"];
        $umail = $row["email"];
    }
    return [$uname, $upass, $ulevel, $umail];
}

function checkmail($mail)
{
    // e-mail sintax error checking
    if (!$mail || strlen($mail = trim($mail)) == 0) {
        $_SESSION['mail_error'] = "<font size=\"2\" color=\"#ff0000\">* Neįvestas e-pašto adresas</font>";
        return false;
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mail_error'] = "<font size=\"2\" color=\"#ff0000\">* Neteisingas e-pašto adreso formatas</font>";
        return false;
    } else {
        return true;
    }
}



?>