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


?>
