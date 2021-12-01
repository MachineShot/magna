<!DOCTYPE html>
<html>
    <?php
        include '../../phpUtils/renderHead.php';
        include '../../phpScripts/reklamosAgenturuValdymas.php';
    ?>
        <link rel='stylesheet' href='./ataskaitaStyle.css'>
    </head>
    <body>
        <div id="app">
            <?php include '../../phpUtils/renderNavigation.php'; ?>

            <h1>Agentūros veiklos ataskaita</h1>

            <?php
                # get passed parameters from url
                $url_components = parse_url($_SERVER['REQUEST_URI']);
                $is_wrong_url = false;

                
                # Check if url params are declared properly
                function check_if_valid_param($param, $params) {
                    if (isset($params[$param]) && $params[$param] != null) {
                        return true;
                    }
                    return false;
                }

                if (isset($url_components['query'])) {
                    parse_str($url_components['query'], $params);

                    # date_start
                    if (check_if_valid_param('date_start', $params)) {
                        $date_start = $params['date_start'];
                    } else {
                        $is_wrong_url = true;
                    }
                    
                    # date_end
                    if (check_if_valid_param('date_end', $params)) {
                        $date_end = $params['date_end'];
                    } else {
                        $is_wrong_url = true;
                    }

                    # stazas_start
                    if (check_if_valid_param('stazas_start', $params)) {
                        $stazas_start = $params['stazas_start'];
                    } else {
                        $is_wrong_url = true;
                    }

                    # stazas_end
                    if (check_if_valid_param('stazas_end', $params)) {
                        $stazas_end = $params['stazas_end'];
                    } else {
                        $is_wrong_url = true;
                    }

                } else {
                    $is_wrong_url = true;
                }

                if ($is_wrong_url) {
                    echo "  <h4>URL neteisingai nurodyti duomenys.</h4>
                            <h4>Patikrinkite puslapio adresą ir bandykite dar kartą.</h4>";
                    die();
                }

                $date_today = date('Y-m-d H:i:s');
                $agency_data = db_get_agency_data();
                $report_data = db_get_agency_report($date_start, $date_end, $stazas_start, $stazas_end);
            ?>

            <section>
                <aside>
                    <p><b>Ataskaitos sudarymo data ir laikas:</b> <?php echo $date_today ?></p>

                    <div class="description-container">
                        <ul class="list">
                            <li><b>Agentūros vadovo informacija:</b></li>
                            <li><b>Vardas:</b> <?php echo $agency_data['vardas'] ?></li>
                            <li><b>Pavardė:</b> <?php echo $agency_data['pavarde'] ?></li>
                            <li><b>Tel. nr.:</b> <?php echo $agency_data['tel_nr'] ?></li>
                            <li><b>El-paštas:</b> <?php echo $agency_data['email'] ?></li>
                        </ul>

                        <ul class="list">
                            <li><b>Agentūros informacija:</b></li>
                            <li><b>Pavadinimas:</b> <?php echo $agency_data['pavadinimas'] ?></li>
                            <li><b>Sukūrimo data:</b> <?php echo $agency_data['sukurimo_data'] ?></li>
                            <li><b>Miestas:</b> <?php echo $agency_data['miestas'] ?></li>
                            <li><b>Adresas:</b> <?php echo $agency_data['adresas'] ?></li>
                            <li><b>Pašto kodas:</b> <?php echo $agency_data['pasto_kodas'] ?></li>
                            <li><b>Įmonės kodas:</b> <?php echo $agency_data['imones_kodas'] ?></li>
                            <li><b>Aprašymas:</b> <?php echo $agency_data['aprasymas'] ?></li>
                        </ul>
                    </div>

                    <h4>Ataskaitai pritaikyti filtrai:</h4>
                    <p>
                        Užregistruotų reklamų sudarymo data nuo
                        <b><?php echo $date_start ?></b>
                        iki <b><?php echo $date_end?></b>
                    </p>
                    <p>
                        <?php
                            if ($stazas_start >= 0 && $stazas_end >= 0) {
                                echo "
                                    Darbuotojų darbo stažas nuo
                                    <b>$stazas_start</b>
                                    iki <b>$stazas_end</b> metų
                                ";
                            } else if ($stazas_start >= 0 && $stazas_end == -1) {
                                echo "
                                    Darbuotojų darbo stažas nuo
                                    <b>$stazas_start</b> metų
                                ";
                            } else if ($stazas_start == -1 && $stazas_end >= 0) {
                                echo "
                                    Darbuotojų darbo stažas iki
                                    <b>$stazas_end</b> metų
                                ";
                            }
                        ?>
                    </p>
                </aside>
                <article>
                    <table id='data-table'>
                        <tr>
                            <th>Darbuotojo vardas, pavardė</th>
                            <th>Slapyvardis</th>
                            <th>Įsidarbinimo data</th>
                            <th>Darbo stažas</th>
                            <th>Aktyvių reklamų sk.</th>
                            <th>Neatyvių reklamų sk.</th>
                            <th>Aktyvių užsakymų sk.</th>
                            <th>Neaktyvių užsakymų sk.</th>
                        </tr>
                        
                        <?php
                            foreach($report_data as $row) {
                                echo "  <tr class='table-filter-row'>
                                            <td class='table__vardas-pavarde'>".$row['vardas'].' '.$row['pavarde']."</td>
                                            <td>".$row['fk_naudotojo_slapyvardis']."</td>
                                            <td>".$row['isidarbinimo_data']."</td>
                                            <td>".$row['darbo_stazas']."</td>";
                                foreach ($row as $person) {
                                    if(gettype($person) == 'object') {
                                        echo "
                                            <td>".$person->ads_active."</td>
                                            <td>".$person->ads_inactive."</td>
                                            <td>".$person->orders_active."</td>
                                            <td>".$person->orders_inactive."</td>";
                                    }
                                }
                                echo "  </tr>";
                            }
                        ?>
                    </table>
                </article>
            </section>

        </div>

        <script>
            const app = new Vue({el: '#app'});
        </script>
    </body>
</html>
