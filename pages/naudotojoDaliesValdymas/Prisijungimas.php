<!DOCTYPE html>
<html>
<?php
    include '../../phpScripts/naudotojoDaliesValdymas.php';
    include '../../phpUtils/startSession.php';
    if(isset($_POST['Prisijungimas']))
    {
       proclogin();
    }
    if ($_SESSION['username_login']!="" || ($_SESSION['prev'] != "index" && $_SESSION['prev'] != "registracija"
     && $_SESSION['prev'] != "proclogin"))
    {
      header("Location:../../index.php");
      exit();
    }
    if($_SESSION['prev'] != "proclogin")
    {
      inisession("part");
    }
    $_SESSION['prev'] = "prisijungimas";
?>
    <link rel="stylesheet" href="../../styles/InputForm.css" />
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8" />
    </head>
    <body>
        <div id="app" style="padding: 0;">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"></navigation>
            <h2>Prisijungimas</h2>
        </div>
        <div class="card">
            <form action="" method="post">
                <div>
                    <label>Slapyvardis</label><br />
                    <input class="s1" name="username" type="text" /><br>
                    <?php echo $_SESSION['username_error'];?><br>
                    <label>Slaptažodis</label><br>
                    <input class="s1" name="pass" type="password" /><br>
                    <?php echo $_SESSION['pass_error'];?><br>
                    <button type="submit" name="Prisijungimas" value="Prisijungti">Prisijungti</button>
                </div>
            </form>
            <button class="button" onclick="location.href = '../../index.php';">Atgal</button>
        </div>
    </body>
    <script src="../../components/navigation.js"></script>
    <script>
        const app = new Vue({
            el: "#app",
        });
    </script>
</html>