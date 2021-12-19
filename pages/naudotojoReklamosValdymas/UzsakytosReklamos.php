<!DOCTYPE html>
<html>
    <?php
    include '../../phpUtils/renderHead.php';
    include '../../phpScripts/naudotojoReklamosValdymas.php';
    $error = "";
    $success = "";
    ?>
    <link rel='stylesheet' href='../../styles/forms.css'>
    </head>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>

                        <h1>Užsakytų reklamų sąrašas</h1>

                        <?php
                            if ($error != "") {
                                echo "<p class='status-msg-error'>".$error."</p>";
                            }
                            else if ($success != "") {
                                echo "<p class='status-msg-success'>".$success."</p>";
                            }

                            $result = db_get_ordered_ads();
                            if ($result->num_rows == 0) {
                                echo "<h4>Neturite nei vienos reklamos.</h4>";
                                die();
                            }
                        ?>

                        <h4 id='no-data-id' class='invisible'>Filtrus atitinkančių darbuotojų nėra.</h4>

                        <table id='data-table'>
                            <tr>
                                <th>Pavadinimas</th>
                                <th>Kaina</th>
                                <th>Sudarymo data</th>
                                <th>Pabaigos data</th>
                                <th>Būsena</th>
                            </tr>

                            <?php
                                while($row = mysqli_fetch_assoc($result))
                                {
                                    $id = $row['id'];
                                    echo "  <tr class='table-filter-row'>
                                                <td>".$row['pavadinimas']."</td>
                                                <td>".$row['kaina']."</td>
                                                <td>".$row['sudarymo_data']."</td>
                                                <td>".$row['pabaigos_data']."</td>
                                                <td>".$row['busena']."</td>
                                                <td class='td-remove-entry'>
                                                    <form method='post' id='remove_ordered_ad_form".$id."'>
                                                        <input name='id' type='hidden' value='$id'>
                                                        <button type='button' class='td-remove-entry__button' onclick='toggleFormSubmit($id);'>
                                                            Atšaukti
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class='td-remove-entry'>
                                                    <button type='button' class='td-remove-entry__button' onclick='redirect($id);'>
                                                        Redaguoti
                                                    </button>
                                                </td>
                                            </tr>
                                            <div class='form-submit-wrapper wrapper-id-".$id."'>
                                                <div class='form-submit-wrapper__content'>
                                                    <h3>Ar tikrai norite pašalinti užsakymą?</h3>
                                                    <input class='form-submit-button form-submit-button--green' type='submit' form='remove_ordered_ad_form".$id."' value='Patvirtinti' onclick='toggleFormSubmit($id);'>
                                                    <input class='form-submit-button form-submit-button--red' type='button' value='Atšaukti' onclick='toggleFormSubmit($id)'>
                                                </div>
                                            </div>
                                        ";
                                }
                            ?>
                        </table>
                    </div>

                    <script src="../../components/navigation.js"></script>
                    <script>
                        const app = new Vue({el: '#app'});

                        const redirect = (id) => {
                            window.location.href = `/isp/pages/naudotojoReklamosValdymas/UzsakytosReklamosRedagavimas.php?id=${id}`;
                        };

                        const toggleFormSubmit = (id) => {
                            const wrapper = document.getElementsByClassName(`wrapper-id-${id}`)[0];
                            wrapper.classList.toggle("form-visible");
                        };
                    </script>
                </body>
</html>
