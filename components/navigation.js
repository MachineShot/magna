import Vue from 'vue'
Vue.component("navigation", {
    props: ['usertype'],
    template : `
        <div>
            <div>
                <h2>Magna Advertisements</h2>
            </div>
            <hr>
            <nav class='main-nav'>
            <ul>
            </ul>
                <ul>
                    <li v-if="usertype !== ''">Informacinė skiltis</li>
                    <li v-if="usertype !== ''"><a href='/isp/pages/naudotojoDaliesValdymas/Atsijungti.php'>Atsijungti</li>
                    <li v-if="usertype !== ''"><a href='/isp/index.php'>Atgal į pagrindinį langą</li>
                    <li v-if="usertype !== ''">
                        <b>Paskyros tipas: {{usertype}}</b> <br>
                    </li>
                </ul>
                <ul v-if="usertype === ''">
                    <li><a href='/isp/pages/naudotojoDaliesValdymas/prisijungimas.php'>Prisijungimas</a></li>
                </ul>  
                <ul v-if="usertype === ''">
                    <li><a href='/isp/pages/naudotojoDaliesValdymas/registracija.php'>Registracija</a></li>
                </ul>         
                <ul v-if="usertype !== ''">
                    <li>Naudotojo Dalies Valdymas:</li>
                    <li v-if="usertype !== ''"><a href='/isp/pages/naudotojoDaliesValdymas/paskyrosInformacijosPerziura.php'>Paskyros Informacijos Peržiura</a></li>
                </ul>

                <ul v-if="usertype === 'uzsakovas'">
                    <li>Naudotojo Reklamų Valdymas:</li>
                    <li><a href='/isp/pages/naudotojoReklamosValdymas/reklamos.php'>Reklamos</a></li>
                    <li><a href='/isp/pages/naudotojoReklamosValdymas/uzsakytosReklamos.php'>Užsakytos reklamos</a></li>
                    <li><a href='/isp/pages/naudotojoReklamosValdymas/reklamuAtaskaitosKurimas.php'>Reklamų Ataskaitos Kūrimas</a></li>
                </ul>

                <ul v-if="usertype !== ''">
                    <li>Pinigų Valdymas:</li>
                    <li><a href='/isp/pages/piniguValdymas/mokejimas.php'>Mokėjimai</a></li>
                    <li><a href='/isp/pages/piniguValdymas/mokejimuSarasas.php'>Mokejimų Sąrašas</a></li>
                    <li><a href='/isp/pages/piniguValdymas/mokejimoDuomenuPerziura.php'>Mokejimo Duomenų Peržiūra</a></li>
                    <li><a href='/isp/pages/piniguValdymas/saskaitosAtaskaitosKurimas.php'>Sąskaitų Ataskaitos Kūrimas</a></li>
                </ul>

                <ul v-if="usertype === 'tiekejas'">
                    <li>Tiekėjo Reklamų Valdymas:</li>
                    <li><a href='/isp/pages/tiekejoReklamosValdymas/uzsakytosReklamos.php'>Užsakytos Reklamos</a></li>
                    <li><a href='/isp/pages/tiekejoReklamosValdymas/siulomosReklamos.php'>Siūlomos Reklamos</a></li>
                    <li><a href='/isp/pages/tiekejoReklamosValdymas/siulomosReklamosKurimas.php'>Siūlomos Reklamos Kūrimas</a></li>
                    <li><a href='/isp/pages/tiekejoReklamosValdymas/atliktuReklamuAtaskaitosKurimas.php'>Atliktų Reklamų Ataskaitos Kūrimas</a></li>
                </ul>

                <ul v-if="usertype === 'vadovas'">
                    <li>Reklamos Agentūrų Valdymas:</li>
                    <li><a href='/isp/pages/reklamosAgenturuValdymas/agenturosDarbuotojuSarasas.php'>Agentūros Darbuotojų Sąrašas</a></li>
                    <li><a href='/isp/pages/reklamosAgenturuValdymas/agenturosDarbuotojoKurimas.php'>Agentūros Darbuotojo Kūrimas</a></li>
                    <li><a href='/isp/pages/reklamosAgenturuValdymas/agenturosAtaskaitosKurimas.php'>Agentūros Veiklos Ataskaitos Kūrimas</a></li>
                </ul>
            </nav>
            <hr>
        </div>
    ` 
});
