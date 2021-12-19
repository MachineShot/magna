<!DOCTYPE html>
<html>
    <?php
    include '../../phpScripts/naudotojoDaliesValdymas.php';
    include '../../phpUtils/startSession.php';
    if ($_SESSION['username_login']=="")
    {
       header("Location:../../index.php");
       exit();
    }
    $_SESSION['prev'] = "PaskyrosTrynimas";
    if(isset($_POST['Trinti'])){deleteAccount();}
?>
    <link rel="stylesheet" href="../../styles/InputForm.css" />
    <body>
        <div id="app" style="padding: 0;">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>
            <link rel="stylesheet" href="../../styles/forms.css" />
            <h2 style="text-align: center;">Ar tikrai norite ištrinti paskyrą?</h2>
        </div>
        <div class="card">
            <form action="" method="post">
                <button type="submit" value="Trinti" name="Trinti">Taip</button>
            </form>
            <button class="button" onclick="location.href = 'PaskyrosInformacijosPerziura.php';">Atgal</button>
        </div>
    </body>
    <script src="../../components/navigation.js"></script>
    <script>
        const app = new Vue({
            el: "#app",
        });
    </script>
</html>
