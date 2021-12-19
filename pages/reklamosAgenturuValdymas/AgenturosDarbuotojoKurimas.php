<!DOCTYPE html>
<html>
    <?php
            include '../../phpUtils/renderHead.php';
            if ($_SESSION['username_login']=="")
            {
               header("Location:../../index.php");
               exit();
            }
        include '../../phpScripts/reklamosAgenturuValdymas.php';

        $error = "";
        $success = "";

        $adresas = "";
        $stazas = 0;

        if ($_POST != null) {
            $adresas = $_POST['adresas'];
            $stazas = $_POST['stazas'];
            $slapyvardis = $_POST['slapyvardis'];


            if ($slapyvardis == "") {
                $error .= "*Privalote pasirinkti sistemoje užregistruotą tiekėją.<br>";
            }

            if ($stazas == "") {
                $error .= "*Privalote nurodyti dabartinį tiekėjo darbo stažą.<br>";
            }

            if ($adresas == "") {
                $error .= "*Privalote nurodyti darbuotojo adresą.<br>";
            }
            
            db_add_agency_employee($adresas, $stazas, $slapyvardis);
            $success = "Agentūroje sėkmingai įdarbintas naujas darbuotojas.";
            $adresas = "";
            $stazas = 0;
        }
    ?>
        <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
        <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>

            <h1>Agentūros darbuotojo įdarbinimas</h1>

            <?php
    	        if ($error != "") {
                    echo "<p class='status-msg-error'>".$error."</p>";
                }
                else if ($success != "") {
                    echo "<p class='status-msg-success'>".$success."</p>";
                }

                $result = db_get_all_providers();
                if (count($result) == 0) {
                    echo "<h4>Sistemoje registruotų tiekėjų, kuriuos būtų galima įdarbinti, nėra.</h4>";
                    $invisible = 1;
                }
            ?>

            <div class="form-wrapper" style="<?php if ($invisible == 1) echo 'display:none'?>">
                <form method="post" id="new_employee_form">
                    <div>
                        <label for="slapyvardis">Pasirinkite tiekėją, kurį norite įdarbinti:</label><br>
                        <select id="slapyvardis" name="slapyvardis" form="new_employee_form">
                            <option disabled selected value="">Slapyvardis - Vardas Pavardė</option>
                            <?php
                                foreach ($result as $row) {
                                    $curr_slapyvardis = $row['slapyvardis'];
                                    $curr_vardas = $row['vardas'];
                                    $curr_pavarde = $row['pavarde'];
                                    echo "<option value='$curr_slapyvardis'>"
                                    ."$curr_slapyvardis - $curr_vardas $curr_pavarde</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="adresas">Tiekėjo adresas:</label><br>
                        <input name='adresas' id="adresas" type='text' maxlength="255" value="<?php echo $adresas; ?>" required>
                    </div>	
                    <div>
                        <label for="stazas">Tiekėjo darbo stažas:</label><br>
                        <input name='stazas' id="stazas" type='number' min="0" step="0.1" value="<?php echo $stazas; ?>" required>
                    </div>	
                    <div>
                        <input type="submit" name="new_employee_form" value="Įdarbinti tiekėją" class="form-submit-button">
                    </div>
                </form>
            </div>

        </div>

        <script src="../../components/navigation.js"></script>
        <script>
            const app = new Vue({el: '#app'});
        </script>
    </body>
</html>
