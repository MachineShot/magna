## Projekto paleidimas
1. Įsirašyti xampp: https://www.apachefriends.org/download.html
2. Projekto failų direktorija turi būti xampp/htdocs/isp/ tokiu formatu, kad pvz. index.php failas būtų xampp/htdocs/isp/index.php vietoje.
3. Paleisti xampp/xampp-control.exe failą.
4. Atsidariusiame xampp lange įjungti (Start) Apache ir MySQL modulius.
5. Jei žingsniai buvo atlikti teisingai, projekto pagrindinis puslapis turėtų būti pasiekiamas adresu http://localhost/isp/

## Aprašas
Projektuojama sistema “Reklamos agentūros ir paslaugos”  yra skirta skirtingų reklamos agentūrų teikiamų paslaugų teikimui/užsakymui. Sistemos naudotojai yra skirstomi į tris kategorijas:
Vartotojas;
Tiekėjas (agentūros darbuotojas);
Agentūros vadovas ( vartotojas, užregistravęs agentūrą);

Sistemos prisijungimo/registracijos skiltyje naudotojai turi galimybę pasirinkti naudotojo registracijos/prisijungimo tipą, pagal kurį bus prieinamos skirtingos sistemos posistemės:
Naudotojai, kurie yra vartotojo tipo turės prieigą prie naudotojų dalies valdymo, naudotojų reklamos valdymo bei pinigų valdymo posistemių;
Naudotojai, kurie yra tiekėjo tipo turės prieigą prie naudotojų dalies valdymo, tiekėjo reklamos valdymo bei pinigų valdymo posistemių;
Naudotojai, kurie yra agentūros vadovo tipo turės prieigą prie naudotojų dalies valdymo, reklamos agentūros valdymo bei pinigų valdymo posistemių;

Kiekviena sistemos posistemė yra skirta atlikti tam tikrą bendro sistemos funkcionalumo dalį:
Naudotojų dalies valdymas - posistemė, skirta bendriniam veiksmam, susijusiems su naudotojų valdymų. Šios posistemės pirmoji funkcinė dalis yra skirta naudotojų registracijai ir prisijungimui (visi minėti vartotojų tipai registruotis bei prisijungs prie sistemos naudodamiesi ta pačia posistemės funkcija, pasirinkdami atitinkamo naudotojo tipą). Likusios naudotojų dalies valdymo posistemės funkcijos  bus pasiekiamos naudotojui prisijungus prie sistemos - paskyros informacijos keitimo funkcija yra skirta viešai prieinamos naudotojo informacijos pakeitimui (agentūros vadovas naudodamasis šia funkcija turės galimybę pakeisti informaciją apie užregistruotą agentūrą) bei šios informacijos peržiūros funkcija. Taip pat visi naudotojai paskyros ištrynimo funkcija turės galimybę ištrinti/deaktyvuoti asmeninę paskyrą. Sudėtingesnė posistemės funkcija yra elektroninių paštų siuntimas - ši funkcija yra skirta įvairių automatinių ar neautomatinių elektroninių paštų siuntimui, pavyzdžiui, reklamos užsakymo ar sąskaitos apmokėjimo priminimo elektroniniai paštai. Taip pat posistemėje bus naudotojo slaptažodžio keitimo funkcija.
Vartotojo reklamos valdymo posistemė - skirta veiksmam, susijusiems su reklamų valdymu iš kliento pusės. Šioje posistemėje klientas galės pirkti reklamas, peržiūrėti esamus reklamų užsakymus ar juos atšaukti. Taip pat klientui bus leidžiama koreguoti kiekvieną užsakymą, pvz.: pratęsti reklamos sutarties laikotarpį. Sudetingesnė šios posistemės funkcija yra reklamos užsakymų ataskaita. Su ja bus galima pamatyti esančių užsakymų ataskaitą, filtruotą pagal datą, tiekėją, agentūrą ir kainą.
Reklamos agentūrų valdymas - posistemė, skirta valdyti sukurtą agentūrą. Šioje posistemėje agentūros vadovas gali peržiūrėti visus agentūros darbuotojus, į agentūrą pridėti (įdarbinti) naujus darbuotojus, juos pašalinti (atleisti) ir matyti bei redaguoti turimą informaciją apie kiekvieną darbuotoją. Sudėtingesnė posistemės funkcija yra galimybė agentūros vadovui sukurti visų agentūros atliktų reklamų ataskaitą, kurioje pateikiama informacija apie kiekvieno darbuotojo atliktas reklamas.
Tiekėjo reklamos valdymas - posistemė yra skirta reklamų valdymui iš tiekėjo (darbuotojo) pusės. Šioje posistemėje tiekėjas galės sukurti naujus reklamų pasiūlymus, peržiūrėti jau užsakytas reklamas, galės redaguoti siūlomas, tačiau dar neužsakytas reklamas ir taip pat galės atšaukti dar neužsakytas reklamas. Sudėtingesnė posistemės funkcija yra atliktų ar užsakytų reklamų ataskaita. Šią ataskaitą bus galima filtruoti pagal datą ir kainą.
Pinigų valdymo posistemė - skirta veiksmam, susijusiems su pinigais, mokėjimais ir sąskaitomis. Šioje posistemėje automatiškai bus sukurtos sąskaitos po mokėjimo ir atsiūstos į el. paštą, klientas galės peržiūrėti mokėjimų istorija, koreguoti savo mokėjimo duomenis (juos sukurti, redaguoti ir panašiai)  ir juos pašalinti. Sudetingesnė šios posistemės funkcija yra sudaryti pasirinkto laikotarpio sąskaitų ataskaitą. Sąskaitas ataskaitoje bus galima filtruoti pagal kainą, agentūrą ir užsakymo tipą.
