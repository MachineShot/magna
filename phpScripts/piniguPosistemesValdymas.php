<?php
    include '../../phpUtils/connectToDB.php';

    $username = $_SESSION['username_login'];; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

    # "Peržiūrėti darbuotojus"
    function db_get_all_payment_methods() {
        global $username; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        $sql = "SELECT * FROM `mokejimo_duomenys`
            WHERE `fk_naudotojo_slapyvardis` = '$username'";
        return db_send_query($sql);
    }
    function db_get_all_payment_method_cardNum() {
        global $username; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        $sql = "SELECT `korteles_nr` FROM `mokejimo_duomenys`
            WHERE `fk_naudotojo_slapyvardis` = '$username'";
        return db_send_query($sql);
    }
function db_get_filtered_data_for_report($dateFrom, $dateTo, $sumFrom, $sumTo) {
        global $username; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        $sql = "SELECT * FROM `saskaita` 
        WHERE 
        `suma` >= '$sumFrom' AND `suma` <= '$sumTo' 
        AND `apmokejimo_data` <= '$dateTo' AND `apmokejimo_data` >= '$dateFrom'";
        return db_send_query($sql);
    }

    # get data of passed employee id
    function db_get_payment_method($payment_id) {
        global $username; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        $sql = "SELECT *
            FROM `mokejimo_duomenys`
            WHERE `fk_naudotojo_slapyvardis` = '$username' AND `id` = '$payment_id'";
        return mysqli_fetch_assoc(db_send_query($sql));
    }

    # "Kurti mokejimo duomenis"
    function db_add_payment_method($cardNum, $cvv, $date, $name, $surname, $address, $city, $postal_code) {
        global $username; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        # add entry in 'tiekejas' table
        $sql = "INSERT INTO `mokejimo_duomenys`
                    (`korteles_nr`, `cvv`, `galiojimo_data`, `vardas`, `pavarde`, `adresas`, `miestas`, `pasto_kodas`, `fk_naudotojo_slapyvardis`)
                VALUES
                    ('$cardNum', '$cvv', '$date', '$name', '$surname', '$address', '$city', '$postal_code', '$username')";
        db_send_query($sql);
    }

    # check whether a user  has at least 1 payment method available
    function db_does_employee_have_ads($id) {
        $sql = "SELECT
                    `id`
                FROM `mokejimo_duomenys`
                WHERE `fk_naudotojo_slapyvardis` = '$id'";
        $result = db_send_query($sql);
        
        if ($result->num_rows == 0) {
            return false;
        }

        return true;
    }
        
    # "Šalinti mokejimo duomenis"
    function db_remove_payment_method($id) {
        global $username; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED
        
        # check whether an employee can be removed from the agency
        $does_have_ads = db_does_employee_have_ads($id);

        if (!$does_have_ads) {
            $sql = "DELETE FROM `mokejimo_duomenys`
                    WHERE `id` = '$id' AND `fk_naudotojo_slapyvardis` = '$username'";
            db_send_query($sql);

            return true;
        }
        return false;
    }

    # "Redaguoti mokejimo duomenis"
    function db_update_payment_info($id, $cardNum, $cvv, $date, $address, $city, $postal_code) {
        global $username; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

		$sql = "UPDATE `mokejimo_duomenys`
				SET
                    `korteles_nr` = '$cardNum',
                    `cvv` = '$cvv',
                    `galiojimo_data` = '$date',
                    `adresas` = '$address',
                    `miestas` = '$city',
                    `pasto_kodas` = '$postal_code'
				WHERE `id` = '$id' ";
		db_send_query($sql);
    }

    # get all filtered agency orders and their statuses
    function db_get_all_payments() {
        global $username; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED
        
        $sql = "SELECT 
                    `apmokejimo_data`,
                    `suma`
                FROM `saskaita` 
                INNER JOIN `uzsakymas`
                ON `saskaita`.`fk_uzsakymo_nr` = `uzsakymas`.`nr`
                WHERE `uzsakymas`.`fk_uzsakovo_slapyvardis` = '$username'";
        return db_send_query($sql);
    }

    # get all filtered ads and their statuses of an agency
    function db_get_filtered_agency_ads($date_start, $date_end) {
        global $agenturos_id; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        $sql = "SELECT
                    `tiekejas`.`fk_naudotojo_slapyvardis` as 'slapyvardis',
                    `reklama`.`aktyvi` as 'reklamos_busena'
                FROM `idarbina`
                INNER JOIN `tiekejas`
                    ON `tiekejas`.`id` = `fk_tiekejas_id`
                LEFT JOIN `reklama`
                    ON `tiekejas`.`id` = `reklama`.`fk_tiekejo_id`
                WHERE
                    `fk_agentura_id` = '$agenturos_id' AND
                    `reklama`.`sudarymo_data` >= '$date_start' AND
                    `reklama`.`sudarymo_data` <= '$date_end'";
        return db_send_query($sql);
    }

    # get all filtered agency employees
    function db_get_filtered_agency_employees($stazas_start, $stazas_end) {
        global $agenturos_id; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        # if $stazas_end == -1 then it means it was not set
        $stazas_filter = '';
        if ($stazas_end != -1) {
            $stazas_filter = " AND `darbo_stazas` <= '$stazas_end'";
        }

        $sql = "SELECT
                `id`,
                `isidarbinimo_data`,
                `adresas`,
                `darbo_stazas`,
                `fk_naudotojo_slapyvardis`,
                `vardas`,
                `pavarde`
            FROM `idarbina`
            INNER JOIN `tiekejas`
                ON `tiekejas`.`id` = `fk_tiekejas_id`
            INNER JOIN `naudotojas`
                ON `fk_naudotojo_slapyvardis` = `slapyvardis`
            WHERE
                `fk_agentura_id` = '$agenturos_id' AND
                `darbo_stazas` >= '$stazas_start'
                $stazas_filter
            ORDER BY `darbo_stazas` DESC";
        return db_send_query($sql);
    }
        
    # "Sukurti agentūros atliktų reklamų ataskaitą"
    function db_get_agency_report($date_start, $date_end, $stazas_start, $stazas_end) {
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

    # get all data about current agency 
    function db_get_agency_data() {
        global $agenturos_id; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        $sql = "SELECT
                    `pavadinimas`,
                    `sukurimo_data`,
                    `adresas`,
                    `aprasymas`,
                    `imones_kodas`,
                    `miestas`,
                    `pasto_kodas`,
                    `vardas`,
                    `pavarde`,
                    `tel_nr`,
                    `email`
                FROM `agentura`
                INNER JOIN `naudotojas`
                ON `slapyvardis` = `fk_vadovo_slapyvardis`
                WHERE `agentura`.`id` = '$agenturos_id'";
        return mysqli_fetch_assoc(db_send_query($sql));
    }

    # check whether an employee already works in this agency
    function db_is_employee_already_in_this_agency($slapyvardis) {
        global $agenturos_id; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED
        
        # get id of the newly created entry
        $sql = "SELECT
                    `tiekejas`.`id`
                FROM `tiekejas`
                INNER JOIN `idarbina`
                ON (`idarbina`.`fk_agentura_id` = '$agenturos_id' AND `idarbina`.`fk_tiekejas_id` = `tiekejas`.`id`)
                WHERE `tiekejas`.`fk_naudotojo_slapyvardis` = '$slapyvardis'";
        $result = db_send_query($sql);
        
        if ($result->num_rows == 0) {
            return false;
        }

        return true;
    }

    # Get all users with user type "tiekejas"
    function db_get_all_providers() {
		$sql = "SELECT 
                    `slapyvardis`,
                    `vardas`,
                    `pavarde`
				FROM `naudotojas`
				WHERE `tipas` = 'tiekejas'";
        $result = db_send_query($sql);

        # filter out employees that already work in this agency
        $final_results = array();

        while ($row = mysqli_fetch_assoc($result)) {
            if (!db_is_employee_already_in_this_agency($row['slapyvardis'])) {
                array_push($final_results, $row);
            }
        }

		return $final_results;
    }
?>
