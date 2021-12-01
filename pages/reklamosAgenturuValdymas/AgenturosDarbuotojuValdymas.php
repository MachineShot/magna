<!DOCTYPE html>
<html>
    <?php
        include '../../phpUtils/renderHead.php';
        include '../../phpScripts/reklamosAgenturuValdymas.php';

        $error = "";
        $success = "";

        if ($_POST != null) {
            $adresas = $_POST['adresas'];
            $stazas = $_POST['stazas'];
            $id = $_POST['id'];

            if ($stazas == "") {
                $error .= "Privalote nurodyti dabartinį tiekėjo darbo stažą.<br>";
            }

            if ($adresas == "") {
                $error .= "Privalote nurodyti darbuotojo adresą.<br>";
            }
            
            db_update_agency_employee_info($adresas, $stazas, $id);
            $success = "Sėkmingai atnaujinta darbuotojo informacija.";
        }
    ?>
        <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <?php include '../../phpUtils/renderNavigation.php'; ?>

            <h1>Agentūros darbuotojo informacijos redagavimas</h1>

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

                $data = db_get_agency_employee($id);
                $adresas = $data['adresas'];
                $stazas = $data['darbo_stazas'];
            ?>

            <table>
                <tr>
                    <th>Vardas, Pavardė</th>
                    <th>Slapyvardis</th>
                    <th>Įsidarbinimo data</th>
                    <th>Adresas</th>
                    <th>Darbo stažas</th>
                </tr>
                
                <?php
                    echo "  <tr class='table-filter-row'>
                                <td class='table__vardas-pavarde'>".$data['vardas'].' '.$data['pavarde']."</td>
                                <td>".$data['fk_naudotojo_slapyvardis']."</td>
                                <td>".$data['isidarbinimo_data']."</td>
                                <td>".$data['adresas']."</td>
                                <td>".$data['darbo_stazas']."</td>
                            </tr>
                        ";
                ?>
            </table>

            <div class="form-wrapper">
                <form method="post" id="update_employee_form">
                    <div>
                        <label for="adresas">Tiekėjo adresas:</label><br>
                        <input name='adresas' id="adresas" type='text' maxlength="255" value="<?php echo $adresas; ?>" required>
                    </div>	
                    <div>
                        <label for="stazas">Tiekėjo darbo stažas:</label><br>
                        <input name='stazas' id="stazas" type='number' min="0" step="0.1" value="<?php echo $stazas; ?>" required>
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
                        <h3>Ar tikrai norite atnaujinti darbuotojo duomenis?</h3>
                        <input class='form-submit-button form-submit-button--green' type='submit' form='update_employee_form' value='Patvirtinti' onclick='toggleFormSubmit();'>
                        <input class='form-submit-button form-submit-button--red' type='button' value='Atšaukti' onclick='toggleFormSubmit();'>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const app = new Vue({el: '#app'});

            const toggleFormSubmit = () => {
                const wrapper = document.getElementsByClassName(`form-submit-wrapper`)[0];
                wrapper.classList.toggle("form-visible");
            };
        </script>
    </body>
</html>
