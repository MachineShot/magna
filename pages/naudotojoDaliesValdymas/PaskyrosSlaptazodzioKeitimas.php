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
    if(isset($_POST['Keisti'])){updatePassword();}
    $_SESSION['prev'] = "PaskyrosSlaptazodzioKeitimas";
?>
    <link rel="stylesheet" href="../../styles/InputForm.css" />
    <body>
        <div id="app" style="padding: 0;">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>
            <link rel="stylesheet" href="../../styles/forms.css" />
            <h2 style="text-align: center;">Keisti paskyros slaptažodį</h2>
        </div>
        <div class="card">
            <?php
                $result = db_get_user_information();
                if ($result->num_rows == 0) {
                die();
                }
                $row = mysqli_fetch_assoc($result);
            ?>
            <form action="" method="post">
                 <div>
                    <label>Dabartinis slaptažodis</label><br>
                    <input class="s1" name="currentpasw" type="password"><br>
                    <label>Naujas slaptažodis</label><br>
                    <input class="s1" name="newpasw" type="password"><br>
                    <?php echo $_SESSION['pass_error'];?><br>
                    <button type="submit" value="Keist" name="Keisti">Keisti slaptažodį</button>
                 </div>
            </form>
            <button class="button" onclick="location.href = 'PaskyrosInformacijosPerziura.php';">Atgal</button>
        </div>
    </body>
    <script src="../../components/navigation.js"> </script>
    <script>
       const app = new Vue({
         el: '#app'}
                    );
       </script>
</html>
