<!DOCTYPE html>
<html>
<?php
    include '../../phpScripts/naudotojoDaliesValdymas.php';
    include '../../phpUtils/startSession.php';
    if (isset($_POST['Registracija']))
    {
            procregister();
    }
    if ($_SESSION['username_login']!="" || ($_SESSION['prev'] != "index" && $_SESSION['prev'] != "prisijungimas" && $_SESSION['prev'] != "procregister"))
    {
       header("Location:../../index.php");
       exit();
    }
    if($_SESSION['prev'] != "procregister")
    {
      inisession("part");
    }

    $_SESSION['prev'] = "registracija"
?>
    <link rel="stylesheet" href="../../styles/InputForm.css" />
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8" />
    </head>
    <body>
        <div id="app" style="padding: 0;">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"></navigation>
            <h2>Registracija</h2>
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
                    <label>El. paštas</label><br>
                    <input class="s1" name="email" type="text" /><br>
                    <?php echo $_SESSION['mail_error'];?><br>
                    <label>Vardas</label><br>
                    <input class="s1" name="name" type="text" /><br>
                    <?php echo $_SESSION['name_error'];?><br>
                    <label>Pavardė</label><br>
                    <input class="s1" name="surname" type="text" /><br>
                    <?php echo $_SESSION['surname_error'];?><br>
                    <label>Telefono numeris</label><br>
                    <input class="s1" name="number" type="text" /><br>
                    <?php echo $_SESSION['number_error'];?><br>
                    <label>Gimimo Data</label><br>
                    <input class="s1" name="birthdate" type="date" required /><br>
                    <?php echo $_SESSION['pass_error'];?><br>
                    <button type="submit" value="Registruoti" name="Registracija">Registruotis</button>
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
