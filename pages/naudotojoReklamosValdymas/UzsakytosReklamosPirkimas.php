<!DOCTYPE html>
<html>
    <?php
            include '../../phpUtils/renderHead.php';
            include '../../phpScripts/naudotojoReklamosValdymas.php';

            $error = "";
            $success = "";

            date_default_timezone_set('Europe/Vilnius');
            $curr_date = date("Y-m-d");

            if ($_POST != null) {
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                $last_date = $_POST['last_date'];
                $id = $_POST['id'];

                if ($start_date == "") {
                    $error .= "*Privalote nurodyti pradžios datą.<br>";
                }
                if ($end_date == "") {
                    $error .= "*Privalote nurodyti pabaigos datą.<br>";
                }
                if($start_date > $end_date){
                    $error .= "*Pradžios data negali būti didesnė už pabaigos datą.<br>";
                }
                if($start_date > $last_date){
                    $error .= "*Pradžios data negali būti didesnė už pasiūlymo galiojimo datą.<br>";
                }
                if($curr_date > $start_date){
                    $error .= "*Pradžios data negali būti mažesnė už dabartinę datą.<br>";
                }
                if($curr_date > $end_date){
                    $error .= "*Pabaigos data negali būti mažesnė už dabartinę datą.<br>";
                }
                if($curr_date > $last_date){
                    $error .= "*Dabartinė data negali būti didesnė už pasiūlymo galiojimo datą.<br>";
                }
                if($start_date > $last_date){
                    $error .= "*Pradžios data negali būti didesnė už pasiūlymo galiojimo datą.<br>";
                }

                if($error === ""){
                    db_add_order($start_date, $end_date, $id);
                    $success = "Sėkmingai sukurtas užsakymas.";
                }
            }
        ?>
            <link rel='stylesheet' href='../../styles/forms.css'>
        </head>
        <body>
            <div id="app">
                <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>

                <h1>Reklamos užsakymo kūrimas</h1>

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
                        echo "  <h4>URL neteisingai nurodytas reklamos indeksas.</h4>
                                <h4>Patikrinkite puslapio adresą ir bandykite dar kartą.</h4>";
                        die();
                    }

                    if ($error != "") {
                        echo "<p class='status-msg-error'>".$error."</p>";
                    }
                    else if ($success != "") {
                        echo "<p class='status-msg-success'>".$success."</p>";
                    }

                    $data = db_get_ad($id);
                    $start_date = date("Y-m-d\TH:i");
                    $end_date = $data['galiojimo_laikotarpis'];
                ?>

                <table>
                    <tr>
                        <th>Pavadinimas</th>
                        <th>Kaina</th>
                        <th>Pasiūlymo sudarymo data</th>
                        <th>Pasiūlymo galiojimo laikotarpis</th>
                        <th>Tiekėjas</th>
                    </tr>

                    <?php
                        echo "  <tr class='table-filter-row'>
                                    <td>".$data['pavadinimas']."</td>
                                    <td>".$data['kaina']."</td>
                                    <td>".$data['sudarymo_data']."</td>
                                    <td>".$data['galiojimo_laikotarpis']."</td>
                                    <td>".$data['fk_tiekejo_id']."</td>
                                </tr>
                            ";
                    ?>
                </table>

                <div class="form-wrapper">
                    <form method="post" id="update_order_form">
                        <div>
                            <label for="start_date">Užsakymo pradžios data:</label><br>
                            <input name='start_date' id="start_date" type='datetime-local' value="<?php echo $start_date; ?>" required>
                        </div>
                        <div>
                            <label for="end_date">Užsakymo pabaigos data:</label><br>
                            <input name='end_date' id="end_date" type='datetime-local' value="<?php echo $end_date; ?>" required>
                        </div>
                        <input name='id' type='hidden' value="<?php echo $data['id']; ?>">
                        <input name='last_date' type='hidden' value="<?php echo $data['galiojimo_laikotarpis']; ?>">
                        <div>
                            <button type='button' class='form-submit-button' onclick='toggleFormSubmit()'>
                                Pateikti duomenis
                            </button>
                        </div>
                    </form>
                    <div class='form-submit-wrapper'>
                        <div class='form-submit-wrapper__content'>
                            <h3>Ar tikrai norite pateikti užsakymo duomenis?</h3>
                            <input class='form-submit-button form-submit-button--green' type='submit' form='update_order_form' value='Patvirtinti' onclick='toggleFormSubmit();'>
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
