<template>
  <div>
    <HelloWorld msg="vuejs component example"/>

    <hr>
    <nav class="main-nav">
      <ul>
        <li><router-link to="/">Pagrindinis langas</router-link></li>
      </ul>
      <ul>
        <li>Naudotojo Dalies Valdymas:</li>
        <li><router-link to="/prisijungimas">Prisijungimas</router-link></li>
        <li><router-link to="/registracija">Registracija</router-link></li>
        <li><router-link to="/paskyrosInformacijosPerziura">Paskyros Informacijos Peržiura</router-link></li>
      </ul>

      <ul>
        <li>Naudotojo Reklamų Valdymas:</li>
        <li><router-link to="/reklamos">Reklamos</router-link></li>
        <li><router-link to="/reklamosPirkimas">Reklamos Pirkimas</router-link></li>
        <li><router-link to="/uzsakytosReklamosRedagavimas">Užsakytų Reklamų Redagavimas</router-link></li>
        <li><router-link to="/reklamuAtaskaitosKurimas">Reklamų Ataskaitos Kūrimas</router-link></li>
      </ul>

      <ul>
        <li>Pinigų Valdymas:</li>
        <li><router-link to="/mokejimas">Mokėjimai</router-link></li>
        <li><router-link to="/mokejimuSarasas">Mokejimų Sąrašas</router-link></li>
        <li><router-link to="/mokejimoDuomenuPerziura">Mokejimo Duomenų Peržiūra</router-link></li>
        <li><router-link to="/saskaitosAtaskaitosKurimas">Sąskaitų Ataskaitos Kurimas</router-link></li>
      </ul>

      <ul>
        <li>Tiekėjo Reklamų Valdymas:</li>
        <li><router-link to="/uzsakytosReklamos">Užsakytos Reklamos</router-link></li>
        <li><router-link to="/siulomosReklamos">Siūlomos Reklamos</router-link></li>
        <li><router-link to="/siulomosReklamosKurimas">Siūlomos Reklamos Kūrimas</router-link></li>
        <li><router-link to="/atliktuReklamuAtaskaitosKurimas">Atliktų Reklamų Ataskaitos Kūrimas</router-link></li>
      </ul>

      <ul>
        <li>Reklamos Agentūrų Valdymas:</li>
        <li><router-link to="/agenturosDarbuotojuSarasas">Agentūros Darbuotojų Sąrašas</router-link></li>
        <li><router-link to="/agenturosDarbuotojoKurimas">Agentūros Darbuotojo Kūrimas</router-link></li>
        <li><router-link to="/agenturosAtaskaitosKurimas">Agentūros Veiklos Ataskaitos Kūrimas</router-link></li>
      </ul>
    </nav>
    <hr>

    <router-view />

  </div>
</template>

<script>
  import HelloWorld from './components/HelloWorld.vue'

  export default {
    name: 'App',
    components: {
      HelloWorld
    }
  }
</script>

<style>
  #app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
  }

  a {
    color: #2364a5;
    text-decoration: none;
  }

  a:visited {
    color: #2364a5;
  }

  a:hover {
    color: black;
  }

  ul {
    list-style-type: none;
  }
</style>

<style scoped>
  ul:first-of-type {
    margin-top: 60px;
  }

  ul li {
    border-bottom: 1px solid rgb(80, 80, 80);
    padding: 5px 0;
  }

  ul li:first-of-type {
    font-weight: bold;
  }

  ul li:only-child {
    border: none;
    font-weight: normal;
  }

  .main-nav {
    display: flex;
  }
</style>