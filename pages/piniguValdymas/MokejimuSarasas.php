<!DOCTYPE html>
<html>
    <?php  include '../../phpUtils/renderHead.php';

        if ($_SESSION['username_login']=="")
        {
            header("Location:../../index.php");
            exit();
        }
        include '../../phpScripts/piniguPosistemesValdymas.php';

        $error = "";
        $success = "";

        if ($_POST != null) {
            $adresas = $_POST['adresas'];
            $stazas = $_POST['stazas'];
            $id = $_POST['id'];

            if ($stazas == "") {
                $error .= "*Privalote nurodyti dabartinį tiekėjo darbo stažą.<br>";
            }

            if ($adresas == "") {
                $error .= "*Privalote nurodyti darbuotojo adresą.<br>";
            }
            
            db_update_agency_employee_info($adresas, $stazas, $id);
            $success = "Sėkmingai atnaujinta darbuotojo informacija.";
        }
    ?>
    </head>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $usertype;?>"></navigation>

            <h1>Mokejimu saraso langas.</h1>

            <?php
                if ($error != "") {
                    echo "<p class='status-msg-error'>".$error."</p>";
                }
                else if ($success != "") {
                    echo "<p class='status-msg-success'>".$success."</p>";
                }

                $result = db_get_all_payments();
                if ($result->num_rows == 0) {
                    echo "<h4>Jūs neturite nei vieno atlikto mokėjimo.</h4>
                    <form action='/isp'>
                        <button class='td-remove-entry__button' type='submit'>Atgal į pagrindinį puslapį</button>
                    </form>";
                    die();
                }
            ?>

            <table id='data-table' style='width: 40%'>
                <tr>
                    <th>Apmokėjimo data</th>
                    <th>Suma</th>
                </tr>
                
                <?php
                    while($row = mysqli_fetch_assoc($result))
                    {
                        echo "  <tr class='table-filter-row'>
                                    <td>".$row['apmokejimo_data']."</td>
                                    <td>".$row['suma']."</td>
                            ";
                    }
                ?>
            </table>
        </div>

        <script src="../../components/navigation.js"></script>
        <script>
            const app = new Vue({
                el: '#app',
                data: {
                    message: '1. If you see this then Vue works.'
                }
            });
        </script>
    </body>
</html>
