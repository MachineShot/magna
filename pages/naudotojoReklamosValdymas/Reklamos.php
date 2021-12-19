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

                        <h1>Reklamų sąrašas</h1>

                        <?php
                            if ($error != "") {
                                echo "<p class='status-msg-error'>".$error."</p>";
                            }
                            else if ($success != "") {
                                echo "<p class='status-msg-success'>".$success."</p>";
                            }

                            $result = db_get_all_ads();
                            if ($result->num_rows == 0) {
                                echo "<h4>Nėra nei vienos reklamos.</h4>";
                                die();
                            }
                        ?>

                        <h4 id='no-data-id' class='invisible'>Filtrus atitinkančių darbuotojų nėra.</h4>

                        <table id='data-table'>
                            <tr>
                                <th>Pavadinimas</th>
                                <th>Kaina</th>
                                <th>Sudarymo data</th>
                                <th>Pasiūlymo galiojimo laikotarpis</th>
                                <th>Tiekėjas</th>
                                <th>Fizine</th>
                                <th>Miestas</th>
                                <th>Adresas</th>
                                <th>Koordinatės</th>
                                <th>Dydis</th>
                                <th>Internetine</th>
                                <th>Puslapio adresas</th>
                                <th>Dydis</th>
                            </tr>

                            <?php
                                while($row = mysqli_fetch_assoc($result))
                                {
                                    $id = $row['id'];
                                    echo "  <tr class='table-filter-row'>
                                                <td>".$row['pavadinimas']."</td>
                                                <td>".$row['kaina']."</td>
                                                <td>".$row['sudarymo_data']."</td>
                                                <td>".$row['galiojimo_laikotarpis']."</td>
                                                <td>".$row['fk_tiekejo_id']."</td>";
                                                if($row['miestas'] == null){
                                                    echo "  <td>&#10060;</td>
                                                            <td>&#10060;</td>
                                                            <td>&#10060;</td>
                                                            <td>&#10060;</td>
                                                            <td>&#10060;</td>
                                                    ";
                                                }
                                                else{
                                                    echo "  <td>&#9989;</td>
                                                            <td>".$row['miestas']."</td>
                                                            <td>".$row['adresas']."</td>
                                                            <td>".$row['koordinates']."</td>
                                                            <td>".$row['dydis']."</td>
                                                    ";
                                                }
                                                if($row['puslapio_adresas'] == null){
                                                    echo "  <td>&#10060;</td>
                                                            <td>&#10060;</td>
                                                            <td>&#10060;</td>
                                                    ";
                                                }
                                                else{
                                                    echo "  <td>&#9989;</td>
                                                            <td>".$row['puslapio_adresas']."</td>
                                                            <td>".$row['tipas']."</td>
                                                    ";
                                                }
                                                echo "
                                                <td class='td-remove-entry'>
                                                    <button type='button' class='td-remove-entry__button' onclick='redirect($id);'>
                                                        Pirkti
                                                    </button>
                                                </td>
                                            </tr>
                                        ";
                                }
                            ?>
                        </table>
                    </div>

                    <script src="../../components/navigation.js"></script>
                    <script>
                        const app = new Vue({el: '#app'});

                        const redirect = (id) => {
                            window.location.href = `/isp/pages/naudotojoReklamosValdymas/UzsakytosReklamosPirkimas.php?id=${id}`;
                        };
                    </script>
                </body>
</html>
