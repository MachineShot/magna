<?php
    include '../../phpUtils/connectToDB.php';

    $user = "uzs1"; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

    # Peržiūrėti visas reklamas
    function db_get_all_ads() {
        $sql = "SELECT `reklama`.*, `fizine_reklama`.`miestas`, `fizine_reklama`.`adresas`,
        `fizine_reklama`.`koordinates`, `fizine_reklama`.`dydis`,
        `internetine_reklama`.`puslapio_adresas`, `internetine_reklama`.`tipas`
                FROM `reklama`
                LEFT JOIN `fizine_reklama`
                    ON `reklama`.`id` = `fizine_reklama`.`fk_reklamos_id`
                LEFT JOIN `internetine_reklama`
                    ON `reklama`.`id` = `internetine_reklama`.`fk_reklamos_id`
                WHERE `aktyvi` = 1";
        return db_send_query($sql);
    }

    # Peržiūrėti viena reklama
        function db_get_ad($id) {
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

    # Gauti vieną užsakytą reklamą
    function db_get_ordered_ad($id) {
        global $user; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED
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
        global $user; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        # Get info about advert
        $data = db_get_ad($id);
        $price = $data['kaina'];

        $sql = "INSERT INTO `uzsakymas`
                    (`kaina`, `sudarymo_data`, `pabaigos_data`, `fk_uzsakovo_slapyvardis`, `fk_reklama_id`)
                VALUES
                    ('$price', '$start_date', '$end_date', '$user', '$id')";
        db_send_query($sql);
    }

    # Gauti ataskaitos duomenis
    function db_get_orders_report($date_start, $date_end, $price_start, $price_end) {
        # Due to my lack of mental capacity I had to use
        # 3 queries instead of 1 and count totals with php.
        $employees = db_get_filtered_agency_employees($stazas_start, $stazas_end);
        $ads = db_get_filtered_agency_ads($date_start, $date_end);
        $orders = db_get_filtered_agency_orders($date_start, $date_end);

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

        return $final_results;
    }
?>
