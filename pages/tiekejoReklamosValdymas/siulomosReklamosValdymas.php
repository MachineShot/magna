<!DOCTYPE html>
<html>
    <?php
        include '../../phpUtils/renderHead.php';
        include '../../phpScripts/tiekejoReklamosValdymas.php';
        include '../../phpUtils/startSession.php';
        if ($_SESSION['username_login']=="")
        {
            header("Location:../../index.php");
            exit();
        }

        $error = "";
        $success = "";

        if ($_POST != null) {
            $pavadinimas = $_POST['pavadinimas'];
            $kaina = $_POST['kaina'];
            //$start_date = $_POST['sudarymo_data'];
            $galiojimo_laikotarpis = $_POST['galiojimo_laikotarpis'];
            $aktyvi = $_POST['aktyvi'];
            $id = $_POST['id'];

//            if ($galiojimo_laikotarpis == "") {
//                $error .= "*Privalote nurodyti galiojimo pabaigos datą.<br>";
//            }
//            if($start_date > $galiojimo_laikotarpis){
//                $error .= "*Pradžios data negali būti didesnė už galiojimo pabaigos datą.<br>";
//            }
            if ($pavadinimas == "") {
                $error .= "*Privalote nurodytį reklamos pavadinimą.<br>";
            }

            if ($kaina == "") {
                $error .= "*Privalote nurodyti kainą.<br>";
            }

            if($error === ""){
                db_update_ad_info_provider($kaina, $pavadinimas, $galiojimo_laikotarpis, $aktyvi, $id);
                $success = "Sėkmingai atnaujinta siūlomos reklamos informacija.";
            }
        }
    ?>
        <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>

            <h1>Siūlomos reklamos informacijos redagavimas</h1>

            <?php
                # get passed id from url parameters
                # and check if it is declared properly
                $url_components = parse_url($_SERVER['REQUEST_URI']);
                $is_wrong_url = false;

                if (isset($url_components['query'])) {
                    parse_str($url_components['query'], $params);
                    if (isset($params['id']) && $params['id'] != null) {
                        $id = $params['id'];
                    } else {
                        $is_wrong_url = true;
                    }
                } else {
                    $is_wrong_url = true;
                }

                if ($is_wrong_url) {
                    echo "  <h4>URL neteisingai nurodytas agentūros darbuotojo indeksas.</h4>
                            <h4>Patikrinkite puslapio adresą ir bandykite dar kartą.</h4>";
                    die();
                }

                if ($error != "") {
                    echo "<p class='status-msg-error'>".$error."</p>";
                }
                else if ($success != "") {
                    echo "<p class='status-msg-success'>".$success."</p>";
                }

                $data = db_get_ad_provider($id);
                $start_date = $data['sudarymo_data'];
                $pavadinimas = $data['pavadinimas'];
                $kaina = $data['kaina'];
                $galiojimo_laikotarpis = $data['galiojimo_laikotarpis'];
                $aktyvi = $data['aktyvi'];
            ?>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Pavadinimas</th>
                    <th>Kaina</th>
                    <th>Sudarymo data</th>
                    <th>Galiojimo pabaigos data</th>
                    <th>Aktyvumas</th>
                </tr>
                
                <?php
                    echo "  <tr class='table-filter-row'>
                                    <td>".$id."</td>
                                    <td>".$data['pavadinimas']."</td>
                                    <td>".$data['kaina']."</td>
                                    <td>".$data['sudarymo_data']."</td>
                                    <td>".$data['galiojimo_laikotarpis']."</td>
                                    <td>".$data['aktyvi']."</td>
                            </tr>
                        ";
                ?>
            </table>

            <div class="form-wrapper">
                <form method="post" id="update_provided_ad_form">
                    <div>
                        <label for="pavadinimas">Pavadinimas:</label><br>
                        <input name='pavadinimas' id="pavadinimas" type='text' required>
                    </div>
                    <div>
                        <label for="kaina">Kaina:</label><br>
                        <input name='kaina' id="kaina" type='number' step="any" required>
                    </div>
                    <div>
                        <label for="galiojimo_laikotarpis">Užsakymo galiojimo pabaigos data:</label><br>
                        <input name='galiojimo_laikotarpis' id="galiojimo_laikotarpis" type='datetime-local' value="<?php echo $curr_date; ?>" required>
                    </div>
                    <div>
                        <label for="aktyvi">Pasirinkite ar reklama bus aktyvi:</label>

                        <select name="aktyvi" id="aktyvi">
                            <option value="0">Neaktyvi</option>
                            <option value="1">Aktyvi</option>
                        </select>
                    </div>
                    <input name='id' type='hidden' value="<?php echo $data['id']; ?>">
                    <input name='sudarymo_data' type='hidden' value="<?php echo $data['sudarymo_data']; ?>">
                    <div>
                        <button type='button' class='form-submit-button' onclick='toggleFormSubmit()'>
                            Atnaujinti duomenis
                        </button>
                    </div>
                </form>
                <div class='form-submit-wrapper'>
                    <div class='form-submit-wrapper__content'>
                        <h3>Ar tikrai norite atnaujinti užsakymo duomenis?</h3>
                        <input class='form-submit-button form-submit-button--green' type='submit' form='update_provided_ad_form' value='Patvirtinti' onclick='toggleFormSubmit();'>
                        <input class='form-submit-button form-submit-button--red' type='button' value='Atšaukti' onclick='toggleFormSubmit();'>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../components/navigation.js"></script>
        <script>
            const app = new Vue({el: '#app'});

            const toggleFormSubmit = () => {
                const wrapper = document.getElementsByClassName(`form-submit-wrapper`)[0];
                wrapper.classList.toggle("form-visible");
            };
        </script>
    </body>
</html>
