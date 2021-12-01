<?php
	include 'startSession.php';
	$usertype = isset($_SESSION["usertype"]) ? $_SESSION["usertype"] : "";

	// also jei naudotojas prisijunges, rodyti atsijungimo link, kuri paspaudus jis bus atjungtas

	if ($usertype == "") {
		// rodyti atsijungusio naudotojo navigacija
	} else if ($usertype == "uzsakovas") {
		// rodyti uzsakovo naudotojo tipo navigacija
	} else if ($usertype == "tiekejas") {
		// rodyti uzsakovo naudotojo tipo navigacija
	} else if ($usertype == "vadovas") {
		// rodyti uzsakovo naudotojo tipo navigacija
	}

	// temporary: rodyti visa navigacija
	echo 
	"  <div>
			<div>
				<h2>Magna Advertisements</h2>
			</div>
			<hr>
			<nav class='main-nav'>
				<ul>
					<li><a href='/isp'>Pagrindinis langas</a></li>
				</ul>
				<ul>
					<li>Naudotojo Dalies Valdymas:</li>
					<li><a href='/isp/pages/naudotojoDaliesValdymas/prisijungimas.php'>Prisijungimas</a></li>
					<li><a href='/isp/pages/naudotojoDaliesValdymas/registracija.php'>Registracija</a></li>
					<li><a href='/isp/pages/naudotojoDaliesValdymas/paskyrosInformacijosPerziura.php'>Paskyros Informacijos Peržiura</a></li>
				</ul>

				<ul>
					<li>Naudotojo Reklamų Valdymas:</li>
					<li><a href='/isp/pages/naudotojoReklamosValdymas/reklamos.php'>Reklamos</a></li>
					<li><a href='/isp/pages/naudotojoReklamosValdymas/reklamosPirkimas.php'>Reklamos Pirkimas</a></li>
					<li><a href='/isp/pages/naudotojoReklamosValdymas/uzsakytosReklamosRedagavimas.php'>Užsakytų Reklamų Redagavimas</a></li>
					<li><a href='/isp/pages/naudotojoReklamosValdymas/reklamuAtaskaitosKurimas.php'>Reklamų Ataskaitos Kūrimas</a></li>
				</ul>

				<ul>
					<li>Pinigų Valdymas:</li>
					<li><a href='/isp/pages/piniguValdymas/mokejimas.php'>Mokėjimai</a></li>
					<li><a href='/isp/pages/piniguValdymas/mokejimuSarasas.php'>Mokejimų Sąrašas</a></li>
					<li><a href='/isp/pages/piniguValdymas/mokejimoDuomenuPerziura.php'>Mokejimo Duomenų Peržiūra</a></li>
					<li><a href='/isp/pages/piniguValdymas/saskaitosAtaskaitosKurimas.php'>Sąskaitų Ataskaitos Kurimas</a></li>
				</ul>

				<ul>
					<li>Tiekėjo Reklamų Valdymas:</li>
					<li><a href='/isp/pages/tiekejoReklamosValdymas/uzsakytosReklamos.php'>Užsakytos Reklamos</a></li>
					<li><a href='/isp/pages/tiekejoReklamosValdymas/siulomosReklamos.php'>Siūlomos Reklamos</a></li>
					<li><a href='/isp/pages/tiekejoReklamosValdymas/siulomosReklamosKurimas.php'>Siūlomos Reklamos Kūrimas</a></li>
					<li><a href='/isp/pages/tiekejoReklamosValdymas/atliktuReklamuAtaskaitosKurimas.php'>Atliktų Reklamų Ataskaitos Kūrimas</a></li>
				</ul>

				<ul>
					<li>Reklamos Agentūrų Valdymas:</li>
					<li><a href='/isp/pages/reklamosAgenturuValdymas/agenturosDarbuotojuSarasas.php'>Agentūros Darbuotojų Sąrašas</a></li>
					<li><a href='/isp/pages/reklamosAgenturuValdymas/agenturosDarbuotojoKurimas.php'>Agentūros Darbuotojo Kūrimas</a></li>
					<li><a href='/isp/pages/reklamosAgenturuValdymas/agenturosAtaskaitosKurimas.php'>Agentūros Veiklos Ataskaitos Kūrimas</a></li>
				</ul>
			</nav>
			<hr>
		</div>";
?>

