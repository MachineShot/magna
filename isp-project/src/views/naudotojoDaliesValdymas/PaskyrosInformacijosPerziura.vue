<template>
  <div>
    <h1>Paskyros informacijos perziuros langas</h1>
    <ul>
      <li><router-link to="/paskyrosInformacijosKeitimas">Paskyros Informacijos Keitimas</router-link></li>
      <li><router-link to="/paskyrosSlaptazodzioKeitimas">Paskyros Slaptažodzio Keitimas</router-link></li>
    </ul>
    <router-view />
  </div>
</template>