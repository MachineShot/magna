<!DOCTYPE html>
<html>
    <?php
        include '../../phpUtils/renderHead.php';
        include '../../phpScripts/naudotojoReklamosValdymas.php';
    ?>
        <link rel='stylesheet' href='./ataskaitaStyle.css'>
    </head>
    <body>
        <div id="app">
            <navigation usertype="<?php echo $usertype;?>"></navigation>

            <h1>Užsakytų reklamų ataskaita</h1>

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
                    if (check_if_valid_param('price_start', $params)) {
                        $stazas_start = $params['price_start'];
                    } else {
                        $is_wrong_url = true;
                    }

                    # stazas_end
                    if (check_if_valid_param('price_end', $params)) {
                        $stazas_end = $params['price_end'];
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
                $orders_data = db_get_ordered_ads();
                $report_data = db_get_orders_report($date_start, $date_end, $price_start, $price_end);
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

                    <h4>Ataskaitoje vaizduojami duomenys su žemiau pritaikytais filtrais:</h4>
                    <p>
                        Užregistruotų reklamų ir užsakymų sudarymo data nuo
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
                <?php
                    if (count($report_data) == 0) {
                        echo "<h3>Filtrus atitinkančių duomenų nėra.</h3>";
                        die();
                    }
                ?>
                <article>
                    <table>
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
                            $full_darbo_stazas = 0;
                            $sum_ads_active = 0;
                            $sum_ads_inactive = 0;
                            $sum_orders_active = 0;
                            $sum_orders_inactive = 0;
                            foreach($report_data as $row) {
                                $full_darbo_stazas += $row['darbo_stazas'];

                                echo "  <tr>
                                            <td>".$row['vardas'].' '.$row['pavarde']."</td>
                                            <td>".$row['fk_naudotojo_slapyvardis']."</td>
                                            <td>".$row['isidarbinimo_data']."</td>
                                            <td>".$row['darbo_stazas']."</td>";
                                foreach ($row as $person) {
                                    if(gettype($person) == 'object') {
                                        $sum_ads_active += $person->ads_active;
                                        $sum_ads_inactive += $person->ads_inactive;
                                        $sum_orders_active += $person->orders_active;
                                        $sum_orders_inactive += $person->orders_inactive;

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
                <article>
                    <h4>Filtrus atitinkančių duomenų bendros statistikos:</h4>
                    <table>
                        <tr>
                            <th>Darbuotojų sk.</th>
                            <th>Vidutinis darbo stažas</th>
                            <th>Aktyvių reklamų sk.</th>
                            <th>Neatyvių reklamų sk.</th>
                            <th>Aktyvių užsakymų sk.</th>
                            <th>Neaktyvių užsakymų sk.</th>
                        </tr>

                        <?php
                            $employees_count = count($report_data);
                            $avg_darbo_stazas = $full_darbo_stazas / $employees_count;

                            echo "  <tr>
                                        <td>".count($report_data)."</td>
                                        <td>".round($avg_darbo_stazas, 2)."</td>
                                        <td>".$sum_ads_active."</td>
                                        <td>".$sum_ads_inactive."</td>
                                        <td>".$sum_orders_active."</td>
                                        <td>".$sum_orders_inactive."</td>
                                    </tr>";
                        ?>

                    </table>
                </article>
            </section>

        </div>

        <script src="../../components/navigation.js"></script>
        <script>
            const app = new Vue({el: '#app'});
        </script>
    </body>
</html>
