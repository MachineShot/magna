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
    $_SESSION['prev'] = "PaskyrosInformacijosKeitimas";
    if(isset($_POST['Keisti']))
    {
       updateInformation();
    }

    $error = "";
    $success = "";
?>
    <link rel="stylesheet" href="../../styles/InputForm.css" />
    <body>
        <div id="app" style="padding: 0;">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>
            <link rel="stylesheet" href="../../styles/forms.css" />
            <h2 style="text-align: center;">Keisti paskyros informaciją</h2>
        </div>
        <div class="card">
            <?php
                if ($error != "") {
                echo "<p class='status-msg-error'>".$error."</p>";
                }
                else if ($success != "") {
                echo "<p class='status-msg-success'>".$success."</p>";
                }
                $result = db_get_user_information();
                if ($result->num_rows == 0) {
                echo isset($_SESSION['username_login']);
                echo "<h4>Informacija nerasta.</h4>";
                die();
                }
                $row = mysqli_fetch_assoc($result);
            ?>
            <form action="" method="post">
                 <div>
                    <label>El paštas</label><br>
                    <input class="s1" name="email" type="text" value="<?php echo $row['email']; ?>"><br>
                    <?php echo $_SESSION['mail_error'];?><br>
                    <label>Telefono numeris</label><br>
                    <input class="s1" name="number" type="text" value="<?php echo $row['tel_nr']; ?>"><br>
                    <?php echo $_SESSION['number_error'];?><br>
                    <button type="submit" value="Registruoti" name="Keisti">Keisti informaciją</button>
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
