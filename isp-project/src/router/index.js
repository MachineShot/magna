import { createWebHistory, createRouter } from "vue-router";
import Home from "@/views/Home.vue";

// naudotojo dalies valdymas
import Prisijungimas from "@/views/naudotojoDaliesValdymas/Prisijungimas.vue";
import Registracija from "@/views/naudotojoDaliesValdymas/Registracija.vue";
import PaskyrosTrynimas from "@/views/naudotojoDaliesValdymas/PaskyrosTrynimas.vue";
import PaskyrosInformacijosPerziura from "@/views/naudotojoDaliesValdymas/PaskyrosInformacijosPerziura.vue";
import PaskyrosInformacijosKeitimas from "@/views/naudotojoDaliesValdymas/PaskyrosInformacijosKeitimas.vue";
import PaskyrosSlaptazodzioKeitimas from "@/views/naudotojoDaliesValdymas/PaskyrosSlaptazodzioKeitimas.vue";

// naudotojo reklamos valdymas
import Reklamos from "@/views/naudotojoReklamosValdymas/Reklamos.vue";
import ReklamosPirkimas from "@/views/naudotojoReklamosValdymas/ReklamosPirkimas.vue";
import UzsakytosReklamosRedagavimas from "@/views/naudotojoReklamosValdymas/UzsakytosReklamosRedagavimas.vue";
import ReklamuAtaskaitosKurimas from "@/views/naudotojoReklamosValdymas/ReklamuAtaskaitosKurimas.vue";
import ReklamuAtaskaita from "@/views/naudotojoReklamosValdymas/ReklamuAtaskaita.vue";

// pinigu valdymas
import Mokejimas from "@/views/piniguValdymas/Mokejimas.vue";
import MokejimoDuomenuPerziura from "@/views/piniguValdymas/MokejimoDuomenuPerziura.vue";
import MokejimuSarasas from "@/views/piniguValdymas/MokejimuSarasas.vue";
import SaskaitosAtaskaitosKurimas from "@/views/piniguValdymas/SaskaitosAtaskaitosKurimas.vue";
import SaskaitosAtaskaita from "@/views/piniguValdymas/SaskaitosAtaskaita.vue";

// reklamos agenturu valdymas
import AgenturosDarbuotojoKurimas from "@/views/reklamosAgenturuValdymas/AgenturosDarbuotojoKurimas.vue";
import AgenturosDarbuotojuValdymas from "@/views/reklamosAgenturuValdymas/AgenturosDarbuotojuValdymas.vue";
import AgenturosDarbuotojuSarasas from "@/views/reklamosAgenturuValdymas/AgenturosDarbuotojuSarasas.vue";
import AgenturosAtaskaitosKurimas from "@/views/reklamosAgenturuValdymas/AgenturosAtaskaitosKurimas.vue";
import AgenturosVeiklosAtaskaita from "@/views/reklamosAgenturuValdymas/AgenturosVeiklosAtaskaita.vue";

// tiekejo reklamos valdymas
import UzsakytosReklamos from "@/views/tiekejoReklamosValdymas/UzsakytosReklamos.vue";
import SiulomosReklamosKurimas from "@/views/tiekejoReklamosValdymas/SiulomosReklamosKurimas.vue";
import SiulomosReklamos from "@/views/tiekejoReklamosValdymas/SiulomosReklamos.vue";
import AtliktuReklamuAtaskaitosKurimas from "@/views/tiekejoReklamosValdymas/AtliktuReklamuAtaskaitosKurimas.vue";
import AtliktuReklamuAtaskaita from "@/views/tiekejoReklamosValdymas/AtliktuReklamuAtaskaita.vue";

const homeRoute = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
];

const naudotojoDaliesValdymoRoutes = [
  {
    path: "/prisijungimas",
    name: "Prisijungimas",
    component: Prisijungimas,
  },
  {
    path: "/registracija",
    name: "Registracija",
    component: Registracija,
  },
  {
    path: "/paskyrosTrynimas",
    name: "PaskyrosTrynimas",
    component: PaskyrosTrynimas,
  },
  {
    path: "/paskyrosInformacijosPerziura",
    name: "PaskyrosInformacijosPerziura",
    component: PaskyrosInformacijosPerziura,
  },
  {
    path: "/paskyrosInformacijosKeitimas",
    name: "PaskyrosInformacijosKeitimas",
    component: PaskyrosInformacijosKeitimas,
  },
  {
    path: "/paskyrosSlaptazodzioKeitimas",
    name: "PaskyrosSlaptazodzioKeitimas",
    component: PaskyrosSlaptazodzioKeitimas,
  },
];

const naudotojoReklamosValdymoRoutes = [
  {
    path: "/reklamos",
    name: "Reklamos",
    component: Reklamos,
  },
  {
    path: "/reklamosPirkimas",
    name: "ReklamosPirkimas",
    component: ReklamosPirkimas,
  },
  {
    path: "/uzsakytosReklamosRedagavimas",
    name: "UzsakytosReklamosRedagavimas",
    component: UzsakytosReklamosRedagavimas,
  },
  {
    path: "/reklamuAtaskaitosKurimas",
    name: "ReklamuAtaskaitosKurimas",
    component: ReklamuAtaskaitosKurimas,
  },
  {
    path: "/reklamuAtaskaita",
    name: "ReklamuAtaskaita",
    component: ReklamuAtaskaita,
  },
];

const piniguValdymoRoutes = [
  {
    path: "/mokejimas",
    name: "Mokejimas",
    component: Mokejimas,
  },
  {
    path: "/mokejimoDuomenuPerziura",
    name: "MokejimoDuomenuPerziura",
    component: MokejimoDuomenuPerziura,
  },
  {
    path: "/mokejimuSarasas",
    name: "MokejimuSarasas",
    component: MokejimuSarasas,
  },
  {
    path: "/saskaitosAtaskaitosKurimas",
    name: "SaskaitosAtaskaitosKurimas",
    component: SaskaitosAtaskaitosKurimas,
  },
  {
    path: "/saskaitosAtaskaita",
    name: "SaskaitosAtaskaita",
    component: SaskaitosAtaskaita,
  },
];

const reklamosAgenturuValdymoRoutes = [
  {
    path: "/agenturosDarbuotojoKurimas",
    name: "AgenturosDarbuotojoKurimas",
    component: AgenturosDarbuotojoKurimas,
  },
  {
    path: "/agenturosDarbuotojuValdymas",
    name: "AgenturosDarbuotojuValdymas",
    component: AgenturosDarbuotojuValdymas,
  },
  {
    path: "/agenturosDarbuotojuSarasas",
    name: "AgenturosDarbuotojuSarasas",
    component: AgenturosDarbuotojuSarasas,
  },
  {
    path: "/agenturosAtaskaitosKurimas",
    name: "AgenturosAtaskaitosKurimas",
    component: AgenturosAtaskaitosKurimas,
  },
  {
    path: "/agenturosVeiklosAtaskaita",
    name: "AgenturosVeiklosAtaskaita",
    component: AgenturosVeiklosAtaskaita,
  },
];

const tiekejoReklamosValdymoRoutes = [
  {
    path: "/uzsakytosReklamos",
    name: "UzsakytosReklamos",
    component: UzsakytosReklamos,
  },
  {
    path: "/siulomosReklamosKurimas",
    name: "SiulomosReklamosKurimas",
    component: SiulomosReklamosKurimas,
  },
  {
    path: "/siulomosReklamos",
    name: "SiulomosReklamos",
    component: SiulomosReklamos,
  },
  {
    path: "/atliktuReklamuAtaskaitosKurimas",
    name: "AtliktuReklamuAtaskaitosKurimas",
    component: AtliktuReklamuAtaskaitosKurimas,
  },
  {
    path: "/atliktuReklamuAtaskaita",
    name: "AtliktuReklamuAtaskaita",
    component: AtliktuReklamuAtaskaita,
  },
];

const allRoutes = [
  ...homeRoute,
  ...naudotojoDaliesValdymoRoutes,
  ...naudotojoReklamosValdymoRoutes,
  ...piniguValdymoRoutes,
  ...reklamosAgenturuValdymoRoutes,
  ...tiekejoReklamosValdymoRoutes
];

const router = createRouter({
  history: createWebHistory(),
  routes: allRoutes
});

export default router;
