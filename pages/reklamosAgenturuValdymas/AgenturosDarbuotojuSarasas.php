<!DOCTYPE html>
<html>
    <?php
        include '../../phpUtils/renderHead.php';
        include '../../phpScripts/reklamosAgenturuValdymas.php';
        
        $error = "";
        $success = "";

        if ($_POST != null) {
            $id = $_POST['id'];
            
            db_remove_agency_employee($id);
            $success = "Iš agentūros sėkmingai pašalintas darbuotojas.";
        }
    ?>
        <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <?php include '../../phpUtils/renderNavigation.php'; ?>

            <h1>Agentūros darbuotojų sąrašas</h1>

            <?php
                if ($error != "") {
                    echo "<p class='status-msg-error'>".$error."</p>";
                }
                else if ($success != "") {
                    echo "<p class='status-msg-success'>".$success."</p>";
                }

                $result = db_get_agency_employees();
                if ($result->num_rows == 0) {
                    echo "<h4>Jūsų agentūroje nėra nei vieno darbuotojo.</h4>";
                    die();
                }
            ?>

            <div class='filtering-wrapper'>
                <label for='filtering-input-id'>
                    Sąrašo filtravimas pagal darbuotojo
                    <strong>vardą</strong>
                    arba
                    <strong>pavardę</strong>:
                </label><br>
                <input id='filtering-input-id' class='filtering-input' onkeyup='filterData();'></input>
            </div>

            <h4 id='no-data-id' class='invisible'>Filtrus atitinkančių darbuotojų nėra.</h4>

            <table style="margin: 0px auto" id='data-table'>
                <tr>
                    <th>Vardas, Pavardė</th>
                    <th>Slapyvardis</th>
                    <th>Įsidarbinimo data</th>
                    <th>Adresas</th>
                    <th>Darbo stažas</th>
                </tr>
                
                <?php
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $id = $row['id'];
                        echo "  <tr class='table-filter-row'>
                                    <td class='table__vardas-pavarde'>".$row['vardas'].' '.$row['pavarde']."</td>
                                    <td>".$row['fk_naudotojo_slapyvardis']."</td>
                                    <td>".$row['isidarbinimo_data']."</td>
                                    <td>".$row['adresas']."</td>
                                    <td>".$row['darbo_stazas']."</td>
                                    <td class='td-remove-entry'>
                                        <form method='post' id='remove_employee_form".$id."'>
                                            <input name='id' type='hidden' value='$id'>
                                            <button type='button' class='td-remove-entry__button' onclick='toggleFormSubmit($id);'>
                                                Šalinti darbuotoją
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <div class='form-submit-wrapper wrapper-id-".$id."'>
                                    <div class='form-submit-wrapper__content wrapper-content-id-".$id."'>
                                        <h3>Ar tikrai norite iš agentūros pašalinti darbuotoją?</h3>
                                        <input class='form-submit-button form-submit-button--green' type='submit' form='remove_employee_form".$id."' value='Patvirtinti' onclick='toggleFormSubmit($id);'>
                                        <input class='form-submit-button form-submit-button--red' type='button' value='Atšaukti' onclick='toggleFormSubmit($id)'>
                                    </div>
                                </div>
                            ";
                    }
                ?>
            </table>
        </div>

        <script>
            const app = new Vue({el: '#app'});

            const toggleFormSubmit = (id) => {
                const wrapper = document.getElementsByClassName(`wrapper-id-${id}`)[0];
                wrapper.classList.toggle("form-visible");
            }

            const showNoDataMessage = () => {
                const el = document.getElementById("no-data-id");
                const table = document.getElementById("data-table");
                el.classList.remove("invisible");
                table.classList.add("invisible");
            };

            const filterData = () => {
                const input = document.getElementById("filtering-input-id");
                const eilutes = Array.from(document.getElementsByClassName("table-filter-row"));
                const vardaiPavardes = Array.from(document.getElementsByClassName("table__vardas-pavarde"));

                let visible = 0;
                if (input !== "") {
                    eilutes.forEach((eilute, id) => {
                        if (vardaiPavardes[id].innerText.toLowerCase().includes(input.value.toLowerCase())) {
                            eilute.classList.remove("invisible");
                            visible++;
                        } else {
                            eilute.classList.add("invisible");
                        }
                    });
                }

                if (visible === 0) {
                    showNoDataMessage();
                } else {
                    const el = document.getElementById("no-data-id");
                    const table = document.getElementById("data-table");
                    el.classList.add("invisible");
                    table.classList.remove("invisible");
                }
            };
        </script>
    </body>
</html>
