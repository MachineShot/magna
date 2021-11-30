<!DOCTYPE html>
<html>
    <?php
        include '../../phpUtils/renderHead.php';
        include '../../phpScripts/reklamosAgenturuValdymas.php';
    ?>
        <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <?php include '../../phpUtils/renderNavigation.php'; ?>

            <h1>Agentūros darbuotojų sąrašas</h1>

            <?php
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
                        echo "  <tr class='table-filter-row'>
                                    <td class='table__vardas-pavarde'>".$row['vardas'].' '.$row['pavarde']."</td>
                                    <td>".$row['fk_naudotojo_slapyvardis']."</td>
                                    <td>".$row['isidarbinimo_data']."</td>
                                    <td>".$row['adresas']."</td>
                                    <td>".$row['darbo_stazas']."</td>
                                </tr>
                            ";
                    }
                ?>
            </table>
        </div>

        <script>
            const app = new Vue({el: '#app'});

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
