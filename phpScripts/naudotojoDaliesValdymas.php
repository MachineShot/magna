<?php
include '../../phpUtils/connectToDB.php';
include '../../phpUtils/settings.php';

function procregister(){
    if (!isset($_SESSION['prev']) || $_SESSION['prev'] != "registracija") {
        header("Location: logout.php");
        exit();
    }
    $_SESSION['prev'] = "procregister";
    $_SESSION['message'] = "Registracija nesėkminga";
    $username = strtolower($_POST['username']);
    $pass = $_POST['pass'];
    $type = $_POST['type'];
    $_SESSION['pass_login'] = $pass;
    $mail = $_POST['email'];
    $_SESSION['mail_login'] = $mail;
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $number = $_POST['number'];
    $birthdate = $_POST['birthdate'];

    list($dbuname) = checkdb($username);
    if ($dbuname) {
        $_SESSION['username_error'] = "<font size=\"2\" color=\"#ff0000\">* Tokiu vardu jau yra registruotas vartotojas</font>";
    } else {
        if (checkpass($pass, substr(hash('sha256', $pass), 5, 32)) & checkusername($username)
            &checkmail($mail)&checkname($name)&checksurname($surname)&checknumber($number)) {
            $pass = substr(hash('sha256', $pass), 5, 32);
            $ulevel = $type;
            $sql ="INSERT INTO " . TBL_USERS . " (slapyvardis, email, vardas, pavarde, tel_nr, slaptazodis, gimimo_data, tipas)
                            	VALUES('$username', '$mail', '$name', '$surname', '$number', '$pass', '$birthdate','$ulevel')";
            if($ulevel == CLIENT_LEVEL || $ulevel == WORKER_LEVEL)
            {
               $inserted = db_send_query($sql);
            }
            if($ulevel == ADMIN_LEVEL)
            {
              $agencyname = $_POST['agencyname'];
              $agencyadress = $_POST['agencyadress'];
              $agencydescription = $_POST['agencydescription'];
              $agencycode = $_POST['agencycode'];
              $agencycity = $_POST['agencycity'];
              $agencymailcode = $_POST['agencymailcode'];
              $date = date('Y-m-d');
              if (checkagencyinput($agencyname, "agencyname_error")&checkagencyinput($agencyadress, "agencyadress_error")
              &checkagencyinput($agencydescription, "agencydescription_error")&checkagencyinput($agencycode, "agencycode_error")
              &checkagencyinput($agencycity, "agencycity_error")&checkagencyinput($agencymailcode, "agencymailcode_error"))
                  {
                    $inserted = db_send_query($sql);
                    if($inserted)
                    {
                      $sql2 ="INSERT INTO  `agentura` (id, pavadinimas, sukurimo_data, adresas, aprasymas, imones_kodas, miestas, pasto_kodas, fk_vadovo_slapyvardis)
                                                              VALUES(default, '$agencyname', '$date', '$agencyadress', '$agencydescription', '$agencycode', '$agencycity', '$agencymailcode','$username')";
                      $inserted2 = db_send_query($sql2);
                    }


                  }

            }
            if ($inserted||$inserted2) {
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

function proclogin(){
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
        list($dbuname, $dbupass, $dbulevel, $dbumai) = checkdb($user);
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


function db_get_user_information(){
    $username = $_SESSION['username_login'];
    $sql = "SELECT  *
            FROM `naudotojas`
            WHERE `slapyvardis` = '$username'";
    return db_send_query($sql);
}

function updateInformation(){
    $username = $_SESSION['username_login'];
    $mail = $_POST['email'];
    $number = $_POST['number'];
    $_SESSION['mail_login'] = $mail;
     if (checkmail($mail)&checknumber($number)){
          $sql = "UPDATE `naudotojas`
                  SET `email` = '$mail', `tel_nr` = '$number'
                  WHERE `slapyvardis` = '$username'";
          if (db_send_query($sql)){
              header("Location: PaskyrosInformacijosPerziura.php");
               exit();
          }
     }
   header("Location: PaskyrosInformacijosKeitimas.php");
   exit();
}

function deleteAccount(){
    $username = $_SESSION['username_login'];
    $sql = "DELETE FROM `naudotojas`
            WHERE `slapyvardis` = '$username'";
            if (db_send_query($sql)){
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
    if ($dbuname){
       if (checkpass($currentpasw, $dbupass)){
          $pass = substr(hash('sha256', $newpasw), 5, 32);
          $_SESSION['pass_login'] = $pass;
          $sql = "UPDATE `naudotojas`
                  SET `slaptazodis` = '$pass'
                  WHERE `slapyvardis` = '$username'";
          if (db_send_query($sql)){
              header("Location: PaskyrosInformacijosPerziura.php");
              exit();
          }
       }
    }
    header("Location: PaskyrosSlaptazodzioKeitimas.php");
    exit();
}


function checkusername($username){
    if (!$username || strlen($username = trim($username)) == 0) {
        $_SESSION['username_error'] = "<font size=\"2\" color=\"#ff0000\">* Užpildykite šį įvedimo lauką!</font>";
        return false;
    } elseif (!preg_match("/^([0-9a-zA-Z])*$/", $username)) {
        $_SESSION['username_error'] = "<font size=\"2\" color=\"#ff0000\">* Vartotojo vardas gali būti sudarytas<br>
    				&nbsp;&nbsp;tik iš raidžių ir skaičių</font>";
        return false;
    } else {
        return true;
    }
}

function checknumber($username){
    if (!$username || strlen($username = trim($username)) == 0) {
        $_SESSION['number_error'] = "<font size=\"2\" color=\"#ff0000\">* Užpildykite šį įvedimo lauką!</font>";
        return false;
    } elseif (!preg_match("/^^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\.\/0-9]*$/", $username)) {
        $_SESSION['number_error'] = "<font size=\"2\" color=\"#ff0000\">* Neteisingas formatas</font>";
        return false;
    } else {
        return true;
    }
}

function checkname($input){
    if (!$input || strlen($input = trim($input)) == 0) {
        $_SESSION['name_error'] = "<font size=\"2\" color=\"#ff0000\">* Užpildykite šį įvedimo lauką!</font>";
        return false;
    } elseif (!preg_match("/^([a-zA-Z])*$/", $input)) {
        $_SESSION['name_error'] = "<font size=\"2\" color=\"#ff0000\">* Vartotojo vardas gali būti sudarytas<br>
    				&nbsp;&nbsp;tik iš raidžių</font>";
        return false;
    } else {
        return true;
    }
}

function checkagencyinput($input, $sessionerror){
    if (!$input || strlen($input = trim($input)) == 0) {
        $_SESSION[$sessionerror] = "<font size=\"2\" color=\"#ff0000\">* Užpildykite šį įvedimo lauką!</font>";
        return false;
    } elseif ($sessionerror=="agencycity_error" && !preg_match("/^([a-zA-Z])*$/", $input)){
          $_SESSION[$sessionerror] = "<font size=\"2\" color=\"#ff0000\">* Miestas gali būti sudarytas<br>
              				&nbsp;&nbsp;tik iš raidžių</font>";
    }
    else {
        return true;
    }
}


function checksurname($input){
    if (!$input || strlen($input = trim($input)) == 0) {
        $_SESSION['surname_error'] = "<font size=\"2\" color=\"#ff0000\">* Užpildykite šį įvedimo lauką!</font>";
        return false;
    } elseif (!preg_match("/^([a-zA-Z])*$/", $input)) {
        $_SESSION['surname_error'] = "<font size=\"2\" color=\"#ff0000\">* Vartotojo pavardė gali būti sudaryta<br>
    				&nbsp;&nbsp;tik iš raidžių</font>";
        return false;
    } else {
        return true;
    }
}

function checkpass($pwd, $dbpwd){
    if (!$pwd || strlen($pwd = trim($pwd)) == 0) {
        $_SESSION['pass_error'] = "<font size=\"2\" color=\"#ff0000\">* Užpildykite šį įvedimo lauką!</font>";
        return false;
    } elseif (!preg_match("/^([0-9a-zA-Z])*$/", $pwd)) {
        /* Check if $pass is not alphanumeric */
        $_SESSION['pass_error'] = "* Čia slaptažodis gali būti sudarytas<br>&nbsp;&nbsp;tik iš raidžių ir skaičių";
        return false;
    } elseif (strlen($pwd) < 4) {
        $_SESSION['pass_error'] = "<font size=\"2\" color=\"#ff0000\">* Slaptažodžio ilgis <4 simbolius</font>";
        return false;
    } elseif ($dbpwd != substr(hash('sha256', $pwd), 5, 32)) {
        $_SESSION['pass_error'] = "<font size=\"2\" color=\"#ff0000\">* Neteisingas slaptažodis</font>";
        return false;
    } else {
        return true;
    }
}

function checkdb($username){
    $sql = "SELECT * FROM " . TBL_USERS . " WHERE slapyvardis = '$username'";
    $result = db_send_query($sql);
    $uname = $upass = $ulevel = $uid = $umail = $id = null;
    if (!$result || mysqli_num_rows($result) != 1) {
    } else {
        $row = mysqli_fetch_assoc($result);
        $uname = $row["slapyvardis"];
        $upass = $row["slaptazodis"];
        $ulevel = $row["tipas"];
        $umail = $row["email"];
    }
    return [$uname, $upass, $ulevel, $umail];
}

function checkmail($mail){
    if (!$mail || strlen($mail = trim($mail)) == 0) {
        $_SESSION['mail_error'] = "<font size=\"2\" color=\"#ff0000\">* Užpildykite šį įvedimo lauką!</font>";
        return false;
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mail_error'] = "<font size=\"2\" color=\"#ff0000\">* Neteisingas e-pašto adreso formatas</font>";
        return false;
    } else {
        return true;
    }
}
?>