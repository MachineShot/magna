<!DOCTYPE html>
<html>
    <?php
        include '../../phpUtils/renderHead.php';
        if ($_SESSION['username_login']=="")
        {
            header("Location:../../index.php");
            exit();
        }
        include '../../phpScripts/tiekejoReklamosValdymas.php';
        $error = "";
        $success = "";


        if ($_POST != null) {
            $busena = $_POST['busena'];
            $id = $_POST['id'];

            db_update_ordered_ad_info_provider($busena, $id);
            $success = "Sėkmingai atnaujinta užsakytos reklamos informacija.";
        }
    ?>
        <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>

            <h1>Užsakytos reklamos informacijos redagavimas</h1>

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

                $data = db_get_ordered_ad_provider($id);
                $busena = $data['busena'];
            ?>

            <table>
                <tr>
                    <th>Pavadinimas</th>
                    <th>Kaina</th>
                    <th>Sudarymo data</th>
                    <th>Pabaigos data</th>
                </tr>
                
                <?php
                    echo "  <tr class='table-filter-row'>
                                    <td>".$data['pavadinimas']."</td>
                                    <td>".$data['kaina']."</td>
                                    <td>".$data['sudarymo_data']."</td>
                                    <td>".$data['pabaigos_data']."</td>
                            </tr>
                        ";
                ?>
            </table>

            <div class="form-wrapper">
                <form method="post" id="update_order_provider_form">
                    <div>
                        <label for="busena">Pasirinkite būseną:</label>

                        <select name="busena" id="busena">
                            <option value="ruosiama">Ruošiama</option>
                            <option value="vykdoma">Vykdoma</option>
                            <option value="neaktyvi">Neaktyvi</option>
                        </select>
                    </div>
                    <input name='id' type='hidden' value="<?php echo $data['id']; ?>">
                    <div>
                        <button type='button' class='form-submit-button' onclick='toggleFormSubmit()'>
                            Atnaujinti duomenis
                        </button>
                    </div>
                </form>
                <div class='form-submit-wrapper'>
                    <div class='form-submit-wrapper__content'>
                        <h3>Ar tikrai norite atnaujinti užsakytos reklamos duomenis?</h3>
                        <input class='form-submit-button form-submit-button--green' type='submit' form='update_order_provider_form' value='Patvirtinti' onclick='toggleFormSubmit();'>
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
