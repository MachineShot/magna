<!DOCTYPE html>
<html>
<?php
    include '../../phpScripts/naudotojoDaliesValdymas.php';
    include '../../phpUtils/startSession.php';
    include '../../phpUtils/renderHead.php';
    if (isset($_POST['Registracija'])){
    inisession("part");
    procregister();
    }
    if ($_SESSION['username_login']!="" || ($_SESSION['prev'] != "index" && $_SESSION['prev'] != "prisijungimas" && $_SESSION['prev'] != "procregister"))
    {
       header("Location:../../index.php");
       exit();
    }
    if($_SESSION['prev'] != "procregister"){inisession("part");}
    $_SESSION['prev'] = "registracija";
    $tipas = "";
?>
    <link rel="stylesheet" href="../../styles/InputForm.css" />
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8" />
    </head>
    <body>
        <div id="app" style="padding: 0;">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"></navigation>
            <?php echo $_SESSION['message'];?>
            <h2>Registracija</h2>
        </div>
        <div class="card">
            <form action="" method="post">
                <div>
                    <hr><label>VARTOTOJO INFORMACIJA</label><br><hr>
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
                     <label>Pasirinkite vartotojo tipą</label><br>
                      Vadovas <input  class="coupon_question" type="checkbox" name="type" value="vadovas" onchange="valueChanged()" onclick="onlyOne(this)"/><br>
                      Užsakovas <input checked="checked" type="checkbox" name="type" value="uzsakovas" onchange="valueChanged()" onclick="onlyOne(this)"/><br>
                      Tiekėjas <input type="checkbox" name="type" value="tiekejas" onchange="valueChanged()" onclick="onlyOne(this)"/><br>
                      <div class="answer" style="display:none;">
                      <hr><label>AGENTŪROS INFORMACIJA</label><br><hr>
                         <label>Pavadinimas</label><br>
                         <input class="s1" name="agencyname" type="text"/><br>
                         <?php echo $_SESSION['agencyname_error'];?><br>
                         <label>Adresas</label><br>
                         <input class="s1" name="agencyadress" type="text"/><br>
                         <?php echo $_SESSION['agencyadress_error'];?><br>
                         <label>Agentūros aprašymas</label><br>
                         <input class="s1" name="agencydescription" type="text"/><br>
                         <?php echo $_SESSION['agencydescription_error'];?><br>
                         <label>Įmonės kodas</label><br>
                         <input class="s1" name="agencycode" type="text"/><br>
                         <?php echo $_SESSION['agencycode_error'];?><br>
                         <label>Miestas</label><br>
                         <input class="s1" name="agencycity" type="text"/><br>
                         <?php echo $_SESSION['agencycity_error'];?><br>
                         <label>Pašto kodas</label><br>
                         <input class="s1" name="agencymailcode" type="text"/><br>
                         <?php echo $_SESSION['agencymailcode_error'];?><br>
                      </div>
                      <button type="submit" value="Registruoti" name="Registracija">Registruotis</button>

                </div>
            </form>
            <button class="button" onclick="location.href = '../../index.php';">Atgal</button>
        </div>
    </body>
    <script src="../../components/navigation.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        const app = new Vue({
            el: "#app",
        });
        function onlyOne(checkbox) {
            var checkboxes = document.getElementsByName('type')
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false
            })
        }
            function valueChanged()
            {
                if($('.coupon_question').is(":checked"))
                    $(".answer").show();
                else
                    $(".answer").hide();
            }
    </script>
</html>
