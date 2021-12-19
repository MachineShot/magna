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
?>
<link rel='stylesheet' href='./ataskaitaStyle.css'>
</head>
<body>
<div id="app">
    <navigation usertype="<?php echo $_SESSION['ulevel'];?>"> </navigation>

    <h1>Reklamų ataskaita</h1>

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

        if (check_if_valid_param('date_start', $params)) {
            $date_start = $params['date_start'];
        } else {
            $is_wrong_url = true;
        }

        if (check_if_valid_param('date_end', $params)) {
            $date_end = $params['date_end'];
        } else {
            $is_wrong_url = true;
        }

        if (check_if_valid_param('price_start', $params)) {
            $price_start = $params['price_start'];
        } else {
            $is_wrong_url = true;
        }

        if (check_if_valid_param('price_end', $params)) {
            $price_end = $params['price_end'];
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
    $providerInfo = get_provider_info();
    ?>

    <section>
        <aside>
            <p><b><?php echo $providerInfo[0]['vardas']?></b></p>
            <p><b>Ataskaitos sudarymo data ir laikas:</b> <?php echo $date_today ?></p>

            <h4>Ataskaitoje vaizduojami duomenys su žemiau pritaikytais filtrais:</h4>
            <p>
                Siūlomų ir užsakytų reklamų sudarymo data nuo
                <b><?php echo $date_start ?></b>
                iki <b><?php echo $date_end?></b>
            </p>
            <p>
                <?php
                if ($price_start >= 0 && $price_end > 0) {
                    echo "
                                    Mėnesinė kaina nuo
                                    <b>$price_start</b>
                                    iki <b>$price_end</b> &euro;
                                ";
                } else if ($price_start >= 0) {
                    echo "
                                    Mėnesinė kaina nuo
                                    <b>$price_start</b> &euro;
                                ";
                } else if ($price_end > 0) {
                    echo "
                                    Mėnesinė kaina iki
                                    <b>$price_end</b> &euro;
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

        <div class="description-container">
            <ul class="list">
                <li><b>Ataskaitos informacija:</b></li>
                <li><b>Viso siūlomų reklamų:</b> <?php echo implode(" ",$report_data['not_ordered_ads']->fetch_assoc()); ?></li>
                <li><b>Viso užsakymų:</b> <?php echo implode(" ",$report_data['count_orders']->fetch_assoc()); ?></li>
                <li><b>Užsakymų suma:</b> <?php echo implode(" ",$report_data['orders_money_sum']->fetch_assoc()); ?></li>
            </ul>
        </div>

        <article>
            <table>
                <tr>
                    <th>Darbuotojo slapyvardis</th>
                    <th>Užsakymų kiekis</th>
                    <th>Užsakymų kainos suma</th>
                </tr>

                <?php
                foreach($report_data['vendor_info'] as $row) {
                    echo "  <tr>
                                            <td>".$row['fk_naudotojo_slapyvardis']."</td>
                                            <td>".$row['count']."</td>
                                            <td>".$row['sum']."</td>
                                        </tr>";
                }
                ?>
            </table>
        </article>
        <article>
            <h4>Filtrus atitinkančių duomenų bendros statistikos:</h4>
            <table>
                <tr>
                    <th>Agentūros pavadinimas</th>
                    <th>Užsakymų kiekis</th>
                    <th>Užsakymų kainos suma</th>
                </tr>

                <?php
                foreach($report_data['agency_info'] as $row) {
                    echo "  <tr>
                                                <td>".$row['pavadinimas']."</td>
                                                <td>".$row['count']."</td>
                                                <td>".$row['sum']."</td>
                                            </tr>";
                }
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
