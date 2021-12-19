<?php
    include '../../phpUtils/connectToDB.php';

    $user = $_SESSION['username_login'];

    # Peržiūrėti visas reklamas
    function db_get_all_ads() {
        $sql = "SELECT `reklama`.*, `fizine_reklama`.`miestas`, `fizine_reklama`.`adresas`,
        `fizine_reklama`.`koordinates`, `fizine_reklama`.`dydis`,
        `internetine_reklama`.`puslapio_adresas`, `internetine_reklama`.`tipas`, `tiekejas`.`fk_naudotojo_slapyvardis` as `tiekejas`
                FROM `reklama`
                LEFT JOIN `fizine_reklama`
                    ON `reklama`.`id` = `fizine_reklama`.`fk_reklamos_id`
                LEFT JOIN `internetine_reklama`
                    ON `reklama`.`id` = `internetine_reklama`.`fk_reklamos_id`
                LEFT JOIN `tiekejas`
                    ON `reklama`.`fk_tiekejo_id` = `tiekejas`.`id`
                WHERE `aktyvi` = 1";
        return db_send_query($sql);
    }

    # Peržiūrėti viena reklama
        function db_get_ad($id) {
            $sql = "SELECT `reklama`.*, `tiekejas`.`fk_naudotojo_slapyvardis` as `tiekejas`
                    FROM `reklama`
                    LEFT JOIN `tiekejas`
                        ON `reklama`.`fk_tiekejo_id` = `tiekejas`.`id`
                    WHERE `reklama`.`id` = '$id'
                    ";
            return mysqli_fetch_assoc(db_send_query($sql));
        }

    # Peržiūrėti visas užsakytas reklamas
    function db_get_ordered_ads() {
        global $user; 
        $sql = "SELECT  `uzsakymas`.`nr` as `id`, `uzsakymas`.`kaina`, `uzsakymas`.`sudarymo_data`,
                        `uzsakymas`.`pabaigos_data`, `uzsakymas`.`busena`,
                        `reklama`.`pavadinimas`, `reklama`.`fk_tiekejo_id`, `fizine_reklama`.`miestas`, `fizine_reklama`.`adresas`,
                        `fizine_reklama`.`koordinates`, `fizine_reklama`.`dydis`,
                        `internetine_reklama`.`puslapio_adresas`, `internetine_reklama`.`tipas`, `tiekejas`.`fk_naudotojo_slapyvardis` as `tiekejas`
                FROM `uzsakymas`
                INNER JOIN `reklama`
                	ON `reklama`.`id` = `fk_reklama_id`
                LEFT JOIN `fizine_reklama`
                    ON `reklama`.`id` = `fizine_reklama`.`fk_reklamos_id`
                LEFT JOIN `internetine_reklama`
                    ON `reklama`.`id` = `internetine_reklama`.`fk_reklamos_id`
                LEFT JOIN `tiekejas`
                    ON `reklama`.`fk_tiekejo_id` = `tiekejas`.`id`
                WHERE fk_uzsakovo_slapyvardis = '$user'";
        return db_send_query($sql);
    }

    # Gauti vieną užsakytą reklamą
    function db_get_ordered_ad($id) {
        global $user; 
        $sql = "SELECT  `uzsakymas`.`nr` as `id`, `uzsakymas`.`kaina`, `uzsakymas`.`sudarymo_data`,
                        `uzsakymas`.`pabaigos_data`, `uzsakymas`.`busena`,
                        `reklama`.`pavadinimas`
                FROM `uzsakymas`
                INNER JOIN `reklama`
                    ON `reklama`.`id` = `fk_reklama_id`
                WHERE `fk_uzsakovo_slapyvardis` = '$user' AND `uzsakymas`.`nr` = '$id'";
        return mysqli_fetch_assoc(db_send_query($sql));
    }

    # "Redaguoti užsakymo informaciją"
    function db_update_ordered_ad_info($end_date, $id) {
        $sql = "UPDATE `uzsakymas`
                SET
                    `pabaigos_data` = '$end_date'
                WHERE `nr` = '$id'";
        db_send_query($sql);
    }

    # "Kurti užsakymą"
    function db_add_order($start_date, $end_date, $id) {
        global $user;

        # Get info about advert
        $data = db_get_ad($id);
        $price = $data['kaina'];

        $sql = "INSERT INTO `uzsakymas`
                    (`kaina`, `sudarymo_data`, `pabaigos_data`, `fk_uzsakovo_slapyvardis`, `fk_reklama_id`)
                VALUES
                    ('$price', '$start_date', '$end_date', '$user', '$id')";
        db_send_query($sql);
    }

    function db_remove_ordered_ad($id) {
        $sql = "DELETE FROM `uzsakymas`
                WHERE `nr` = '$id' AND `busena` = 'neaktyvi'
        ";
        db_send_query($sql);

        $sql = "SELECT * FROM `uzsakymas`
                WHERE `nr` = '$id'
        ";

        return db_send_query($sql);
    }

    function db_filtering($date_start, $date_end, $price_start, $price_end, $func, $group_by) {
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
        $orders_money_sum = db_filtering($date_start, $date_end, $price_start, $price_end, "SELECT SUM(`uzsakymas`.`kaina`) as 'sum'", "");
        $count_orders = db_filtering($date_start, $date_end, $price_start, $price_end, "SELECT COUNT(`uzsakymas`.`nr`) as 'count'", "");
        $vendor_info = db_filtering($date_start, $date_end, $price_start, $price_end,
            "SELECT `tiekejas`.`fk_naudotojo_slapyvardis`, COUNT(`reklama`.`fk_tiekejo_id`) as 'count', SUM(`uzsakymas`.`kaina`) as 'sum'",
            "GROUP BY `reklama`.`fk_tiekejo_id` ORDER BY `sum` DESC;");
        $agency_info = db_filtering($date_start, $date_end, $price_start, $price_end,
            "SELECT `agentura`.`pavadinimas`, COUNT(`agentura`.`id`) as 'count', SUM(`uzsakymas`.`kaina`) as 'sum'",
            "GROUP BY `agentura`.`id` ORDER BY `sum` DESC;");

        $results = array(
            "orders_money_sum" => $orders_money_sum,
            "count_orders" => $count_orders,
            "vendor_info" => $vendor_info,
            "agency_info" => $agency_info
        );

        return $results;
    }
?>
