<?php
	// prisijungti prie duomenu bazes
	$server = "localhost";
	$db = "itproj";
	$user = "stud";
	$password = "stud";
	$lentele = "itproj";

	$dbc = mysqli_connect($server,$user,$password, $db);
	if(!$dbc) {
		die ("Nepavyko prisijungti prie MySQL:".mysqli_error($dbc));
	}

	date_default_timezone_set("Europe/Vilnius");

	// issiusti uzklausa i duomenu baze
	function db_send_query($sql) {
		global $dbc;
	    $result = mysqli_query($dbc, $sql);
	    if(!$result) {
	        die ('Nepavyko įvykdyti SQL užklausos:'.mysqli_error($dbc));
	    }
	    return $result;
	}

	// sekundes paversti HH:MM:SS laiko formatu
	// t = sekundes, f = skyriklis
	function format_time($t, $f=':')  {
		return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
	}

	// prisijungti
	function db_login($name, $pass) {
		$hashed_pass = hash('md5', $name.$pass);
		$sql = "SELECT
					`slapyvardis`,
					`tipas`
				FROM `naudotojas`
				WHERE `slapyvardis` = '$name' AND `slaptazodis` = '$hashed_pass'";
		$result = db_send_query($sql);
		if ($result->num_rows == 0) {
			return false;
		} else {
			$data = mysqli_fetch_assoc($result);

			include 'startSession.php';
			$_SESSION["usertype"] = $data['tipas'];
			$_SESSION["username"] = $data['slapyvardis'];
			return true;
		}
	}

	// returns the id of a person to whom a new problem will be assigned
	function db_find_to_whom_to_assing_a_problem($tipas) {
		// is darbuotojo lenteles gauti visus darbuotojus
		// + isrikiuoti pagal gedimu pasalinimo skaiciu
		$darbuotojai_sql = "SELECT
								`id_darbuotojas`,
								`statusas`
							FROM `darbuotojas`
							ORDER BY `darbuotojas`.`pasalintu_gedimu_skaicius` ASC";
		$darbuotojai_result = db_send_query($darbuotojai_sql);

		// gauti duomenis apie darbuotoju salinamus gedimu tipus ir
		// atfiltruoti darbuotojus, kuriems tinka naujo gedimo tipas
		$salina_sql = "SELECT
							`salina`.`darbuotojo_id`
						FROM `salina`
						INNER JOIN salinami_gedimai
						ON `salina`.`gedimo_id` = `salinami_gedimai`.`id`
						WHERE `salinami_gedimai`.`tipas` = '$tipas'";
		$salina_result = db_send_query($salina_sql);

		$darbuotojai_salina = array();
		while($salina_row = mysqli_fetch_assoc($salina_result)) {
			array_push($darbuotojai_salina, $salina_row['darbuotojo_id']);
		}

		// jei yra tik vienas darbuotojas - return
		if (count($darbuotojai_salina) == 1) {
			return $darbuotojai_salina[0];
		}

		// surasyti visus tinkamus darbuotojus i masyva
		$darbuotojai = array();
		while($darbuotojai_row = mysqli_fetch_assoc($darbuotojai_result)) {
			$id = $darbuotojai_row['id_darbuotojas'];
			if (in_array($id, $darbuotojai_salina)) {
				array_push($darbuotojai, (object)[ (string)$id => $darbuotojai_row['statusas'] ]);
			}
		}

		// jei yra tik vienas darbuotojas - return
		if (count($darbuotojai) == 1) {
			foreach ($darbuotojai as $darbuotojas) {
				foreach ($darbuotojas as $key=>$value) {
					return $key;
				}
			}
		}

		// atfiltruoti darbuotojus, kurie yra laisvi
		$laisvi_darbuotojai = array();

		foreach ($darbuotojai as $darbuotojas) {
			foreach ($darbuotojas as $key=>$value) {
				if ($value === "laisvas") {
					array_push($laisvi_darbuotojai, $key);
				}
			}
		}

		// jei yra bent vienas laisvas darbuotojas - return pirma darbuotoja
		// (pirmas yra pasalines maziausiai gedimu)
		if (count($laisvi_darbuotojai) > 0) {
			foreach ($laisvi_darbuotojai as $darbuotojas) {
				return $darbuotojas;
			}
		}

		// jei nera nei vieno laisvo darbuotojo, tada
		// return pirma darbuotoja is visu tinkamu darbuotoju saraso
		foreach ($darbuotojai as $darbuotojas) {
			foreach ($darbuotojas as $key=>$value) {
				return $key;
			}
		}
	}

	// uzsiregistruoti
	function db_register($slapyvardis, $vardas, $pavarde, $email, $slaptazodis, $tipas, $taisomi_tipai){
		// prideti nauja irasa i naudotoju lentele
		$hashed_pass = hash('md5', $slapyvardis.$slaptazodis);
		$sql = "INSERT INTO `naudotojas`
					(`slapyvardis`, `vardas`, `pavarde`, `email`, `slaptazodis`, `tipas`)
				VALUES
					('$slapyvardis', '$vardas', '$pavarde', '$email', '$hashed_pass', '$tipas')";
		db_send_query($sql);

		// jei registruojamos paskyros naudotojo tipas yra darbuotojas:
		if ($tipas == "darbuotojas") {
			// 1. prideti irasa i darbuotoju lentele
			$sql = "INSERT INTO `darbuotojas`
						(`pasalintu_gedimu_skaicius`, `sureagavimu_i_gedimus_skaicius`, `vidutinis_gedimo_pasalinimo_laikas`, `vidutinis_sureagavimo_i_gedima_laikas`, `kiek_laiko_salino_gedimus`, `statusas`, `fk_naudotojas_slapyvardis`)
					VALUES
						(0, 0, 0, 0, 0, 'laisvas', '$slapyvardis')";
			db_send_query($sql);

			// gauti sio naujo darbuotojo id
			$sql = "SELECT `id_darbuotojas`
					FROM darbuotojas
					WHERE `fk_naudotojas_slapyvardis` = '$slapyvardis'";
			$result = db_send_query($sql);

			$curr_darbuotojo_id;
			while($row = mysqli_fetch_assoc($result)) {
				$curr_darbuotojo_id = $row['id_darbuotojas'];
			}

			// 2. prideti irasus i salinamu gedimu lentele su darbuotojo salinamais gedimais
			foreach($taisomi_tipai as $gedimo_tipas) {
				$curr_gedimo_id;
				if ($gedimo_tipas == "pc") $curr_gedimo_id = 1;
				else if ($gedimo_tipas == "lan") $curr_gedimo_id = 2;
				else if ($gedimo_tipas == "kitas") $curr_gedimo_id = 3;
				$sql = "INSERT INTO `salina`
							(`darbuotojo_id`, `gedimo_id`)
						VALUES
							('$curr_darbuotojo_id', '$curr_gedimo_id')";
				db_send_query($sql);
			}
		}

		include 'startSession.php';
		$_SESSION["usertype"] = $tipas;
		$_SESSION["username"] = $slapyvardis;
	}

	// patikrinti ar jau egzistuoja naudotojas su duotu slapyvardziu
	function db_check_if_username_exists($val) {
		$sql = "SELECT `slapyvardis`
				FROM `naudotojas`
				WHERE `slapyvardis` = '$val'";
		$result = db_send_query($sql);
		if ($result->num_rows == 0) {
			return false;
		} else {
			return true;
		}
	}

	// patikrinti ar jau egzistuoja naudotojas su duotu el. pasto adresu
	function db_check_if_email_exists($val) {
		$sql = "SELECT `slapyvardis`
				FROM `naudotojas`
				WHERE `email` = '$val'";
		$result = db_send_query($sql);
		if ($result->num_rows == 0) {
			return false;
		} else {
			return true;
		}
	}

	// uzregistruoti nauja gedima
	function db_add_new_problem($aprasymas, $tipas) {
		include 'startSession.php';
		$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

		$date = date('Y-m-d H:i:s');
		$kuriam_priskirt = db_find_to_whom_to_assing_a_problem($tipas);
		$sql = "INSERT INTO `gedimas`
			(`registravimo_data`, `priskyrimo_data`, `darbo_pradzios_data`, `pasalinimo_data`, `aprasymas`, `statusas`, `tipas`, `fk_darbuotojas_id`, `fk_naudotojas_slapyvardis`)
			VALUES ('$date', '$date', NULL, NULL, '$aprasymas', 'registruota', '$tipas', '$kuriam_priskirt', '$username')";
		db_send_query($sql);

		$sql = "UPDATE `darbuotojas`
				SET `statusas` = 'uzimtas'
				WHERE `darbuotojas`.`id_darbuotojas` = '$kuriam_priskirt'";
		db_send_query($sql);
	}

	// gauti nurodyto naudotojo uzregistruotus gedimus
	function db_get_my_problems() {
		include 'startSession.php';
		$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

		$sql = "SELECT
					`gedimas`.`id` as `id`,
					`gedimas`.`registravimo_data` as `registravimo_data`,
					`gedimas`.`priskyrimo_data` as `priskyrimo_data`,
					`gedimas`.`darbo_pradzios_data` as `darbo_pradzios_data`,
					`gedimas`.`pasalinimo_data` as `pasalinimo_data`,
					`gedimas`.`aprasymas` as `aprasymas`,
					`gedimas`.`statusas` as `statusas`,
					`gedimas`.`tipas` as `tipas`,
					`darbuotojas`.`fk_naudotojas_slapyvardis` as `darbuotojas`
				FROM gedimas
				INNER JOIN `darbuotojas`
				ON `gedimas`.`fk_darbuotojas_id` = `darbuotojas`.`id_darbuotojas`
				WHERE `gedimas`.`fk_naudotojas_slapyvardis` = '$username'
				ORDER BY `gedimas`.`id` ASC";
		return db_send_query($sql);
	}	

	// gauti visus gedimus
	function db_get_all_problems() {
		$sql = "SELECT
					`gedimas`.`id` as `id`,
					`gedimas`.`registravimo_data` as `registravimo_data`,
					`gedimas`.`priskyrimo_data` as `priskyrimo_data`,
					`gedimas`.`darbo_pradzios_data` as `darbo_pradzios_data`,
					`gedimas`.`pasalinimo_data` as `pasalinimo_data`,
					`gedimas`.`aprasymas` as `aprasymas`,
					`gedimas`.`statusas` as `statusas`,
					`gedimas`.`tipas` as `tipas`,
				    `naudotojas`.`vardas`,
				    `naudotojas`.`pavarde`,
					`darbuotojas`.`fk_naudotojas_slapyvardis` as `darbuotojas`
				FROM gedimas
				INNER JOIN `darbuotojas`
				ON `gedimas`.`fk_darbuotojas_id` = `darbuotojas`.`id_darbuotojas`
				INNER JOIN `naudotojas`
				ON `gedimas`.`fk_naudotojas_slapyvardis` = `naudotojas`.`slapyvardis`
				ORDER BY `gedimas`.`id` ASC";
		return db_send_query($sql);
	}	

	// gauti darbuotoju statistikas
	function db_get_staff_stats() {
		$sql = "SELECT *
				FROM darbuotojas
				INNER JOIN `naudotojas`
				ON `darbuotojas`.`fk_naudotojas_slapyvardis` = `naudotojas`.`slapyvardis`
				ORDER BY `darbuotojas`.`pasalintu_gedimu_skaicius` DESC";
		return db_send_query($sql);
	}

	// gauti visus darbuotojus
	function db_get_staff() {
		$sql = "SELECT
					`id_darbuotojas`,
					`fk_naudotojas_slapyvardis` as `darb_slapyvardis`
				FROM darbuotojas";
		return db_send_query($sql);
	}

	// gauti priskirtu gedimu informacija
	function db_get_assigned_problems() {
		include 'startSession.php';
		$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";
		$sql = "SELECT
					`gedimas`.`id` as `id`,
				    `gedimas`.`registravimo_data` as `registravimo_data`,
				    `gedimas`.`priskyrimo_data` as `priskyrimo_data`,
				    `gedimas`.`darbo_pradzios_data` as `darbo_pradzios_data`,
				    `gedimas`.`aprasymas` as `aprasymas`,
				    `gedimas`.`statusas` as `statusas`,
				    `gedimas`.`tipas` as `tipas`,
				    `naudotojas`.`vardas`,
				    `naudotojas`.`pavarde`
				FROM `gedimas`
				INNER JOIN `darbuotojas` ON `gedimas`.`fk_darbuotojas_id` = `darbuotojas`.`id_darbuotojas`
				INNER JOIN `naudotojas` ON `gedimas`.`fk_naudotojas_slapyvardis` = `naudotojas`.`slapyvardis`
				WHERE `darbuotojas`.`fk_naudotojas_slapyvardis` = '$username' AND `gedimas`.`statusas` <> 'sutvarkytas'
				ORDER BY `gedimas`.`id` ASC";

		return db_send_query($sql);
	}	

	// gauti visu darbuotojams priskirtu gedimu informacija
	function db_get_all_assigned_problems() {
		include 'startSession.php';
		$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";
		$sql = "SELECT
					`gedimas`.`id` as `id`,
				    `gedimas`.`registravimo_data` as `registravimo_data`,
				    `gedimas`.`priskyrimo_data` as `priskyrimo_data`,
				    `gedimas`.`darbo_pradzios_data` as `darbo_pradzios_data`,
				    `gedimas`.`aprasymas` as `aprasymas`,
				    `gedimas`.`statusas` as `statusas`,
				    `gedimas`.`tipas` as `tipas`,
				    `naudotojas`.`vardas`,
				    `naudotojas`.`pavarde`,
					`darbuotojas`.`fk_naudotojas_slapyvardis` as `darb_slapyvardis`,
					`darbuotojas`.`id_darbuotojas` as `darbuotojo_id`
				FROM `gedimas`
				INNER JOIN `darbuotojas` ON `gedimas`.`fk_darbuotojas_id` = `darbuotojas`.`id_darbuotojas`
				INNER JOIN `naudotojas` ON `gedimas`.`fk_naudotojas_slapyvardis` = `naudotojas`.`slapyvardis`
				WHERE `gedimas`.`statusas` <> 'sutvarkytas'
				ORDER BY `gedimas`.`id` ASC";

		return db_send_query($sql);
	}

	// priskirti gedima kitam darbuotojui
	function db_reassign_problem($gedimo_id, $naujas_darbuotojo_id, $gedimo_statusas, $senas_darbuotojo_id) {
		// atnaujinti gedimo lenteles irasa su nauju priskirtu darbuotoju
		$date = date('Y-m-d H:i:s');
		$sql = "UPDATE `gedimas`
				SET
					`fk_darbuotojas_id` = '$naujas_darbuotojo_id',
					`priskyrimo_data` = '$date',
					`darbo_pradzios_data` = NULL,
					`statusas` = 'registruota'
				WHERE `gedimas`.`id` = '$gedimo_id'";
		db_send_query($sql);

		// jei darbuotojas is kurio pakeite gedima kitam darbuotojui
		// daugiau gedimu neturi, tada pakeisti to darbuotojo statusa i "laisvas"
		$sql = "SELECT
					`darbuotojas`.`id_darbuotojas` as `darbuotojo_id`
				FROM `gedimas`
				INNER JOIN `darbuotojas` ON `gedimas`.`fk_darbuotojas_id` = `darbuotojas`.`id_darbuotojas`
				WHERE `gedimas`.`statusas` <> 'sutvarkytas' AND `darbuotojas`.`id_darbuotojas` = '$senas_darbuotojo_id'";
		$result = db_send_query($sql);

		$count = 0;
		while($row = mysqli_fetch_assoc($result)) {
			$count++;
		}

		if ($count == 0) {
			$sql = "UPDATE `darbuotojas`
					SET `statusas` = 'laisvas'
					WHERE `darbuotojas`.`id_darbuotojas` = '$senas_darbuotojo_id'";
			db_send_query($sql);
		}

		// jei darbuotojas, kuriam priskirtas gedimas neturejo nei vieno priskirto gedimo,
		// pakeisti jo statusa i "uzimtas"
		$sql = "SELECT
				`darbuotojas`.`id_darbuotojas` as `darbuotojo_id`
			FROM `gedimas`
			INNER JOIN `darbuotojas` ON `gedimas`.`fk_darbuotojas_id` = `darbuotojas`.`id_darbuotojas`
			WHERE `gedimas`.`statusas` <> 'sutvarkytas' AND `darbuotojas`.`id_darbuotojas` = '$naujas_darbuotojo_id'";
		$result = db_send_query($sql);

		$count = 0;
		while($row = mysqli_fetch_assoc($result)) {
			$count++;
		}

		if ($count == 1) {
			$sql = "UPDATE `darbuotojas`
					SET `statusas` = 'uzimtas'
					WHERE `darbuotojas`.`id_darbuotojas` = '$naujas_darbuotojo_id'";
			db_send_query($sql);
		}
	}

	// surasti kiekvieno darbuotojo gedimu tipus prie kuriu jie dirba
	function db_get_problems_on_which_staff_works() {
		$sql = "SELECT
					`darbuotojas`.`fk_naudotojas_slapyvardis`,
					`salinami_gedimai`.`tipas`
				FROM `salina`
				INNER JOIN `salinami_gedimai`
				ON `salina`.`gedimo_id` = `salinami_gedimai`.`id`
				INNER JOIN `darbuotojas`
				ON `darbuotojas`.`id_darbuotojas` = `salina`.`darbuotojo_id`
				ORDER BY `salinami_gedimai`.`tipas` ASC";

		return db_send_query($sql);
	}

	// patikrinti ar nurodytas darbuotojas salina nurodyta gedimo tipa
	function db_check_if_darbuotojas_salina_gedimo_tipa($darbuotojo_id, $gedimo_tipas) {
		$sql = "SELECT
					`salina`.`darbuotojo_id`
				FROM `salina`
				INNER JOIN salinami_gedimai
				ON `salina`.`gedimo_id` = `salinami_gedimai`.`id`
				WHERE `salina`.`darbuotojo_id` = '$darbuotojo_id'
				AND `salinami_gedimai`.`tipas` = '$gedimo_tipas'";
		$result = db_send_query($sql);

		while($row = mysqli_fetch_assoc($result)) {
			if ($row['darbuotojo_id'] == $darbuotojo_id) {
				return true;
			} else {
				return false;
			}
		}
	}

	// keisti priskirto gedimo statusa
	function db_update_assigned_problem($naujas_statusas, $gedimo_id, $gedimo_priskyrimo_data) {
		include 'startSession.php';
		$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

		$date = date('Y-m-d H:i:s');
		$date_time = strtotime($date);

		$priskyrimo_date_time = strtotime($gedimo_priskyrimo_data);
		$sureagavimo_date_diff_seconds = $date_time - $priskyrimo_date_time;

		if ($naujas_statusas == "salinama") {
			// atnaujinti gedimo lenteles irasa
			$sql = "UPDATE `gedimas`
					SET `statusas` = '$naujas_statusas', `darbo_pradzios_data` = '$date'
					WHERE `gedimas`.`id` = '$gedimo_id'";
			db_send_query($sql);

			// gauti duomenis, kurie bus naudojami nauju
			// darbuotojo statistiku skaiciavimui
			$sql = "SELECT
						`vidutinis_sureagavimo_i_gedima_laikas`,
						`pasalintu_gedimu_skaicius`,
						`sureagavimu_i_gedimus_skaicius`
					FROM `darbuotojas`
					WHERE `darbuotojas`.`fk_naudotojas_slapyvardis` = '$username'";
			$result = db_send_query($sql);
			$result = mysqli_fetch_assoc($result);

			// apskaiciuoti naujas darbuotojo statistikas
			$gedimu_skaicius = $result['sureagavimu_i_gedimus_skaicius'];
			$visas_sureagavimo_laikas = $gedimu_skaicius * $result['vidutinis_sureagavimo_i_gedima_laikas'] + $sureagavimo_date_diff_seconds;
			$gedimu_skaicius++;
			$naujas_laikas = $visas_sureagavimo_laikas / $gedimu_skaicius;

			// i darbuotojo lentele irasyti naujus duomenis
			$sql = "UPDATE `darbuotojas`
					SET
						`statusas` = 'uzimtas',
						`vidutinis_sureagavimo_i_gedima_laikas` = '$naujas_laikas',
						`sureagavimu_i_gedimus_skaicius` = '$gedimu_skaicius'
					WHERE `darbuotojas`.`fk_naudotojas_slapyvardis` = '$username'";
			db_send_query($sql);
		} else if ($naujas_statusas == "sutvarkytas") {
			// atnaujinti gedimo lenteles irasa
			$sql = "UPDATE `gedimas`
					SET `statusas` = '$naujas_statusas', `pasalinimo_data` = '$date'
					WHERE `gedimas`.`id` = '$gedimo_id'";
			db_send_query($sql);

			// is gedimo lenteles gauti sio gedimo darbo pradzios data
			$sql = "SELECT `darbo_pradzios_data`
					FROM `gedimas`
					WHERE `gedimas`.`id` = '$gedimo_id'";
			$result = db_send_query($sql);
			$result = mysqli_fetch_assoc($result);
			$darbo_pradzios_data = $result['darbo_pradzios_data'];

			// is darbuotojo lenteles gauti darbuotojo statistikas
			$sql = "SELECT
						`pasalintu_gedimu_skaicius`,
						`vidutinis_gedimo_pasalinimo_laikas`,
						`kiek_laiko_salino_gedimus`
					FROM `darbuotojas`
					WHERE `darbuotojas`.`fk_naudotojas_slapyvardis` = '$username'";
			$result = db_send_query($sql);
			$result = mysqli_fetch_assoc($result);

			// apskaiciuoti naujas darbuotojo statistikas
			$darbo_pradzios_date_time = strtotime($darbo_pradzios_data);
			$sio_gedimo_salinimo_laikas = $date_time - $darbo_pradzios_date_time;

			$gedimu_skaicius = $result['pasalintu_gedimu_skaicius'];
			$visas_gedimo_pasalinimo_laikas = $gedimu_skaicius * $result['vidutinis_gedimo_pasalinimo_laikas'] + $sio_gedimo_salinimo_laikas;
			$gedimu_skaicius++;
			$naujas_laikas = $visas_gedimo_pasalinimo_laikas / $gedimu_skaicius;
			$kiek_laiko_salino_gedimus = $result['kiek_laiko_salino_gedimus'] + $sio_gedimo_salinimo_laikas; 

			// patikrinti ar darbuotojas turi bent viena priskirta gedima
			// tam, kad teisingai atnaujinti nauja darbuotojo statusa
			$sql = "SELECT
						`id`
					FROM `gedimas`
					INNER JOIN `darbuotojas`
					ON `gedimas`.`fk_darbuotojas_id` = `darbuotojas`.`id_darbuotojas`
					WHERE `darbuotojas`.`fk_naudotojas_slapyvardis` = '$username'
					AND `gedimas`.`statusas` <> 'sutvarkytas'";
			$result = db_send_query($sql);

			$naujas_darbuotojo_statusas = "uzimtas";
			if ($result->num_rows == 0) {
				$naujas_darbuotojo_statusas = "laisvas";
			}

			// i darbuotojo lentele irasyti naujus duomenis
			$sql = "UPDATE `darbuotojas`
					SET `statusas` = '$naujas_darbuotojo_statusas',
						`pasalintu_gedimu_skaicius` = '$gedimu_skaicius',
						`vidutinis_gedimo_pasalinimo_laikas` = '$naujas_laikas',
						`kiek_laiko_salino_gedimus` = '$kiek_laiko_salino_gedimus'
					WHERE `darbuotojas`.`fk_naudotojas_slapyvardis` = '$username'";
			db_send_query($sql);
		}
	}
?>
