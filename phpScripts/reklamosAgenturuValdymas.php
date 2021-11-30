<?php
    include '../../phpUtils/connectToDB.php';

    $agenturos_id = 1; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

    # "Peržiūrėti darbuotojus"
    function db_get_agency_employees() {
        global $agenturos_id; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

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
            WHERE `fk_agentura_id` = '$agenturos_id'";
        return db_send_query($sql);
    }

    # "Kurti darbuotoją"
    function db_add_agency_employee($adresas, $stazas, $slapyvardis) {
        global $agenturos_id; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED

        # add entry in 'tiekejas' table
        $date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `tiekejas`
                    (`isidarbinimo_data`, `adresas`, `darbo_stazas`, `fk_naudotojo_slapyvardis`)
                VALUES
                    ('$date', '$adresas', '$stazas', '$slapyvardis')";
        db_send_query($sql);

        # get id of the newly created entry
        $sql = "SELECT
                    `id`
                FROM `tiekejas`
                WHERE `fk_naudotojo_slapyvardis` = '$slapyvardis'";
        $id = mysqli_fetch_assoc(db_send_query($sql))['id'];

        # add a new entry into the table 'idarbina'
        # that shows in which agency this employee works
        $sql = "INSERT INTO `idarbina`
                    (`fk_agentura_id`, `fk_tiekejas_id`)
                VALUES
                    ('$agenturos_id', '$id')";
        db_send_query($sql);
    }

    # check whether an employee has at least 1 ad available
    function db_does_employee_have_ads($id) {
        $sql = "SELECT
                    `id`
                FROM `reklama`
                WHERE `fk_tiekejo_id` = '$id'";
        $result = db_send_query($sql);
        
        if ($result->num_rows == 0) {
            return false;
        }

        return true;
    }
        
    # "Šalinti darbuotoją"
    function db_remove_agency_employee($id) {
        global $agenturos_id; # TEMPORARY - DELETE WHEN AUTHENTICATION IS IMPLEMENTED
        
        # check whether an employee can be removed from the agency
        $does_have_ads = db_does_employee_have_ads($id);

        if (!$does_have_ads) {
            $sql = "DELETE FROM `idarbina`
                    WHERE `fk_tiekejas_id` = '$id' AND `fk_agentura_id` = '$agenturos_id'";
            db_send_query($sql);

            $sql = "DELETE FROM `tiekejas`
                    WHERE `id` = '$id'";
            db_send_query($sql);
            return true;
        }
        return false;
    }
        
    # "Redaguoti darbuotojo informaciją"
    function db_update_agency_employee_info() {

    }
        
    # "Sukurti agentūros atliktų reklamų ataskaitą"
    function db_get_agency_report() {

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
