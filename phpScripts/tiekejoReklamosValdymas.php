<?php
    include '../../phpUtils/connectToDB.php';
    include '../../phpUtils/settings.php';

    $user = $_SESSION['username_login']; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

    # Peržiūrėti tiekejo užsakytas reklamas(veikia kaip reikia)
    function db_get_all_ordered_ads() {
        global $user;  # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED
        //echo var_dump($user);
        $tiekejoID = mysqli_fetch_assoc(db_send_query("SELECT id FROM tiekejas WHERE fk_naudotojo_slapyvardis='$user'"));
        $sql = "SELECT `uzsakymas`.`nr`, `uzsakymas`.`kaina`, `uzsakymas`.`sudarymo_data`, `uzsakymas`.`pabaigos_data`,
		            `uzsakymas`.`busena`, `uzsakymas`.`fk_uzsakovo_slapyvardis`
                    FROM `uzsakymas`
                    INNER JOIN `reklama`
         	            ON reklama.id = uzsakymas.fk_reklama_id 
                    INNER JOIN `tiekejas`
         	            ON reklama.fk_tiekejo_id = tiekejas.id
			        WHERE tiekejas.id = '$tiekejoID[id]'";
        return db_send_query($sql);
    }
    function get_provider_info()
    {
        global $user;

        return mysqli_fetch_assoc(db_send_query("SELECT * FROM naudotojas WHERE slapyvardis='$user'"));
    }

    # Peržiūrėti tiekejo siulomas reklamas(veikia kaip reikia)
    function db_get_all_provided_ads() {
        global $user;

        $tiekejoID = mysqli_fetch_assoc(db_send_query("SELECT id FROM tiekejas WHERE fk_naudotojo_slapyvardis='$user'"));
        $sql = "SELECT * FROM `reklama`
                WHERE `reklama`.`fk_tiekejo_id` = '$tiekejoID[id]' AND `reklama`.`id` NOT IN 
                      (SELECT `uzsakymas`.`fk_reklama_id` FROM `uzsakymas`)";
        return db_send_query($sql);
    }

    # Peržiūrėti viena reklama
        function db_get_ad_provider($id) {
            $sql = "SELECT * FROM `reklama`
                    WHERE `id` = '$id'";
            return mysqli_fetch_assoc(db_send_query($sql));
        }

    # Peržiūrėti visas užsakytas reklamas
    function db_get_ordered_ads() {
        global $user; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED
        $sql = "SELECT  `uzsakymas`.`nr` as `id`, `uzsakymas`.`kaina`, `uzsakymas`.`sudarymo_data`,
                        `uzsakymas`.`pabaigos_data`, `uzsakymas`.`busena`,
                        `reklama`.`pavadinimas`
                FROM `uzsakymas`
                INNER JOIN `reklama`
                	ON `reklama`.`id` = `fk_reklama_id`
                WHERE fk_uzsakovo_slapyvardis = '$user'";
        return db_send_query($sql);
    }

    # Gauti vieną užsakytą reklamą (veikia kaip reikia)
    function db_get_ordered_ad_provider($id) {
        global $user; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED
        $sql = "SELECT  `uzsakymas`.`nr` as `id`, `uzsakymas`.`kaina`, `uzsakymas`.`sudarymo_data`,
                        `uzsakymas`.`pabaigos_data`, `uzsakymas`.`busena`,
                        `reklama`.`pavadinimas`
                FROM `uzsakymas`
                INNER JOIN `reklama`
                    ON `reklama`.`id` = `fk_reklama_id`
                WHERE `uzsakymas`.`nr` = '$id'";
        return mysqli_fetch_assoc(db_send_query($sql));
    }

    # "Redaguoti užsakymo informaciją"
    function db_update_ordered_ad_info_provider($busena, $id) {
        $sql = "UPDATE `uzsakymas`
                SET `busena` = '$busena'
                WHERE `nr` = '$id'";
        db_send_query($sql);
    }

    function db_update_ad_info_provider($kaina, $pavadinimas, $galiojimo_data, $aktyvi, $id) {
        $sql = "UPDATE `reklama`
                    SET `kaina` = '$kaina',
                        `pavadinimas` = '$pavadinimas',
                        `galiojimo_laikotarpis` = '$galiojimo_data',
                        `aktyvi` = '$aktyvi'
                    WHERE `id` = '$id'";
        db_send_query($sql);
    }

    function db_remove_ad_provider($id) {
        global $user; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        # check whether an employee can be removed from the agency
            $sql = "DELETE FROM `internetine_reklama`
                    WHERE `fk_reklamos_id` = '$id'";
            db_send_query($sql);

            $sql = "DELETE FROM `fizine_reklama`
                    WHERE `fk_reklamos_id` = '$id'";
            db_send_query($sql);

            $sql = "DELETE FROM `reklama`
                    WHERE `id` = '$id'";
            db_send_query($sql);
    }

    # "Kurti nauja siuloma reklama"
    function db_add_new_ad($kaina, $pavadinimas, $aktyvi, $start_date, $end_date, $puslapio_adresas, $tipas, $miestas, $adresas, $koordinates, $dydis) {
        global $user; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        //$username = $_SESSION['username'];
        $tiekejas = mysqli_fetch_assoc(db_send_query("SELECT id FROM tiekejas WHERE fk_naudotojo_slapyvardis='$user'"));

        $sql = "INSERT INTO `reklama`
                    (`kaina`, `pavadinimas`, `sudarymo_data`, `galiojimo_laikotarpis`, `aktyvi`, `fk_tiekejo_id`)
                VALUES
                    ('$kaina', '$pavadinimas', '$start_date', '$end_date', '$aktyvi', '$tiekejas[id]')";

        db_send_query($sql);

        $query = "SELECT id FROM `reklama` WHERE `pavadinimas` = '$pavadinimas'";
        $reklamos_id = mysqli_fetch_assoc(db_send_query($query));

        $sql2 = "INSERT INTO `internetine_reklama`
                    (`puslapio_adresas`, `tipas`, `fk_reklamos_id`)
                 VALUES
                    ('$puslapio_adresas', '$tipas', '$reklamos_id[id]')";
        db_send_query($sql2);

        if (!empty($miestas) AND !empty($adresas) AND !empty($koordinates) AND !empty($dydis))
        {
            $sql3 = "INSERT INTO `fizine_reklama`
                    (`miestas`, `adresas`, `koordinates`, `dydis`, `fk_reklamos_id`)
                VALUES
                    ('$miestas', '$adresas', '$koordinates', '$dydis', '$reklamos_id[id]')";
            db_send_query($sql3);
        }
    }

    function db_get_filtered_provider_ads($date_start, $date_end) {
        include '../../phpUtils/startSession.php';
        global $user;

        $sql = "SELECT
                    `tiekejas`.`fk_naudotojo_slapyvardis` as 'slapyvardis',
                    `uzsakymas`.`busena` as 'uzsakymo_busena'
                FROM `idarbina`
                INNER JOIN `tiekejas`
                    ON `tiekejas`.`id` = `fk_tiekejas_id`
                LEFT JOIN `reklama`
                    ON `tiekejas`.`id` = `reklama`.`fk_tiekejo_id`
                LEFT JOIN `uzsakymas`
                    ON `uzsakymas`.`fk_reklama_id` = `reklama`.`id`
                WHERE 
                    `fk_agentura_id` = '$agenturos_id' AND
                    `uzsakymas`.`sudarymo_data` >= '$date_start' AND
                    `uzsakymas`.`sudarymo_data` <= '$date_end'";
        return db_send_query($sql);
    }


    function db_filtering($date_start, $date_end, $price_start, $price_end, $func, $group_by) {
        global $user; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        $whereClauseString = "";
        if(!empty($date_start)) {
            $whereClauseString .= " AND DATE(`uzsakymas`.`sudarymo_data`)>='$date_start'";
            if(!empty($date_end)) {
                $whereClauseString .= " AND DATE(`uzsakymas`.`sudarymo_data`) <= '$date_end'";
            }
        } else {
            if(!empty($date_end)) {
                $whereClauseString .= " AND DATE(`uzsakymas`.`sudarymo_data`) <= '$date_end'";
            }
        }

        if(!empty($price_start)) {
            $whereClauseString .= " AND `uzsakymas`.`kaina` >= '$price_start'";
            if(!empty($price_end)) {
                $whereClauseString .= " AND `uzsakymas`.`kaina` <= '$price_end'";
            }
        } else {
            if(!empty($price_end)) {
                $whereClauseString .= " AND `uzsakymas`.`kaina` <= '$price_end'";
            }
        }

        #var_dump($whereClauseString);

        $sql = "$func
                FROM `uzsakymas`
                INNER JOIN `reklama` ON `reklama`.`id` = `uzsakymas`.`fk_reklama_id`
                INNER JOIN `tiekejas` ON `tiekejas`.`id` = `reklama`.`fk_tiekejo_id`
                INNER JOIN `idarbina` ON `idarbina`.`fk_tiekejas_id` = `reklama`.`fk_tiekejo_id`
                INNER JOIN `agentura` ON `agentura`.`id` = `idarbina`.`fk_agentura_id`
                WHERE 
                    `fk_uzsakovo_slapyvardis` = '$user' 
                    $whereClauseString
                $group_by
            ";
        return db_send_query($sql);
    }

    # Gauti ataskaitos duomenis
    function db_get_orders_report($date_start, $date_end, $price_start, $price_end) {
        global $user;

        $whereClauseString = "";
        if(!empty($date_start)) {
            $whereClauseString .= " AND DATE(`uzsakymas`.`sudarymo_data`)>='$date_start'";
            if(!empty($date_end)) {
                $whereClauseString .= " AND DATE(`uzsakymas`.`sudarymo_data`) <= '$date_end'";
            }
        } else {
            if(!empty($date_end)) {
                $whereClauseString .= " AND DATE(`uzsakymas`.`sudarymo_data`) <= '$date_end'";
            }
        }

        if(!empty($price_start)) {
            $whereClauseString .= " AND `uzsakymas`.`kaina` >= '$price_start'";
            if(!empty($price_end)) {
                $whereClauseString .= " AND `uzsakymas`.`kaina` <= '$price_end'";
            }
        } else {
            if(!empty($price_end)) {
                $whereClauseString .= " AND `uzsakymas`.`kaina` <= '$price_end'";
            }
        }

        $tiekejoID = mysqli_fetch_assoc(db_send_query("SELECT id FROM tiekejas WHERE fk_naudotojo_slapyvardis='$user'"));
        //$orders_money_sum = db_filtering($date_start, $date_end, $price_start, $price_end, "SELECT SUM(`uzsakymas`.`kaina`) as 'sum'", "");
        $count_orders = "SELECT COUNT(`reklama`.id) as 'count' FROM `reklama`
                WHERE `reklama`.`fk_tiekejo_id` = '$tiekejoID[id]' {$whereClauseString} AND `reklama`.`id` IN 
                      (SELECT `uzsakymas`.`fk_reklama_id` FROM `uzsakymas`)";
        //echo $count_orders;
        /*
        $vendor_info = db_filtering($date_start, $date_end, $price_start, $price_end,
            "SELECT `tiekejas`.`fk_naudotojo_slapyvardis`, COUNT(`reklama`.`fk_tiekejo_id`) as 'count', SUM(`uzsakymas`.`kaina`) as 'sum'",
            "GROUP BY `reklama`.`fk_tiekejo_id` ORDER BY `sum` DESC;");
        $agency_info = db_filtering($date_start, $date_end, $price_start, $price_end,
            "SELECT `agentura`.`pavadinimas`, COUNT(`agentura`.`id`) as 'count', SUM(`uzsakymas`.`kaina`) as 'sum'",
            "GROUP BY `agentura`.`id` ORDER BY `sum` DESC;");
        $count_not_ordered_ads = $count_orders = db_filtering($date_start, $date_end, $price_start, $price_end, "SELECT COUNT(`reklama`.id) as 'count2' FROM `reklama`
                WHERE `reklama`.`fk_tiekejo_id` = '$tiekejoID[id]' AND `reklama`.`id` NOT IN 
                      (SELECT `uzsakymas`.`fk_reklama_id` FROM `uzsakymas`)", "");
        */
        $results = array(
            ""
            //"orders_money_sum" => $orders_money_sum,
            //"count_orders" => $count_orders,
            //"vendor_info" => $vendor_info,
            //"agency_info" => $agency_info,
            //"not_ordered_ads" => $count_not_ordered_ads
        );




        /*
        # create arrays of data so that i can be reused later
        $employees_arr = array();
        $ads_arr = array();
        $orders_arr = array();

        while($row = mysqli_fetch_assoc($employees)) {
            array_push($employees_arr, $row);
        }

        while($row = mysqli_fetch_assoc($ads)) {
            array_push($ads_arr, $row);
        }

        while($row = mysqli_fetch_assoc($orders)) {
            array_push($orders_arr, $row);
        }

        $final_results = array();

        # Count totals of ads and orders for each employee
        foreach ($employees_arr as $employee) {
            # count totals of ads
            $ads_active = 0;
            $ads_inactive = 0;
            foreach ($ads_arr as $ad) {
                if ($ad['slapyvardis'] == $employee['fk_naudotojo_slapyvardis']) {
                    $val = $ad['reklamos_busena'];
                    if ($val != null) {
                        if ($val == 1) {
                            $ads_active++;
                        } else {
                            $ads_inactive++;
                        }
                    }
                }
            }

            # count totals of orders
            $orders_active = 0;
            $orders_inactive = 0;
            foreach ($orders_arr as $order) {
                if ($order['slapyvardis'] == $employee['fk_naudotojo_slapyvardis']) {
                    $val = $order['uzsakymo_busena'];
                    if ($val != null) {
                        if ($val == "neaktyvi") {
                            $orders_inactive++;
                        } else {
                            $orders_active++;
                        }
                    }
                }
            }

            # push counted totals to the array(employee) of current iteration
            array_push($employee, (object) [
                'ads_active' => $ads_active,
                'ads_inactive' => $ads_inactive,
                'orders_active' => $orders_active,
                'orders_inactive' => $orders_inactive
            ]);

            # push results to final results array
            array_push($final_results, $employee);
        }
        */

        //return $results;
    }
?>
