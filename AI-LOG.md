AI-LOG — DI naudojimo žurnalas

Data — Problema — Kaip naudotas DI — Ką pasiūlė — Ką priėmiau / pakeičiau / atmečiau
2026-10-13 — Reikėjo pagal šabloną parengti pradinius projekto dokumentus — Daviau šabloną ir projekto idėją (ticketing sistema, Laravel + Filament), paprašiau užpildyti — README, problem.md, requirements.md, technology-decision.md juodraščius — Priėmiau kaip juodraštį, skaičius ir pavyzdžius pataisysiu pagal savo situaciją.

2026-10-14 — Reikėjo sukurti Laravel projektą — Paprašiau DI įdiegti Laravel pagal laravel.com/for/agents instrukciją — Laravel 13 su Livewire starter kit, SQLite, Laravel Boost — Priėmiau, bet MySQL kol kas nenaudoju, nes neįdiegtas; Cloud CLI neveikia su PHP 8.3, praleidau.

2026-10-14 — Nereikia atskiro frontend, užtenka Filament — Paprašiau DI suplanuoti ir padaryti viską Filament su rolėmis — Pašalinti starter kit, Filament panelė per /, users lentelėje laukas role (superadmin, admin, user), vartotojus kuria superadmin — Priėmiau. Pasirinkau vieną users lentelę su role, nes paprasčiau nei atskiros lentelės adminams ir vartotojams

2026-10-15 — Seeder lūžo su klaida „Call to undefined method Ticket::factory()“ — Įklijavau klaidos tekstą DI — Pridėti HasFactory į Ticket modelį ir pataisyti migraciją: admin_id ->constrained() rodė į neegzistuojančią „admins“ lentelę, reikia ->constrained('users') — Priėmiau abu pataisymus, seeder veikia.

2026-10-15 — Reikėjo užpildyti Filament redagavimo ir peržiūros formas — Paprašiau DI užpildyti formas — Būseną ir prioritetą laikyti enum su lietuviškomis etiketėmis ir spalvomis (kaip UserRole), formą suskirstyti į sekcijas, naudotoją ir adminą rinktis iš sąrašo, parašyti testus — Priėmiau. Enum pasirinkau, kad reikšmes būtų lengviau koreguoti vienoje vietoje.

2026-10-15 — Bet kuris prisijungęs naudotojas matė ir galėjo redaguoti visus ticket'us — Paprašiau DI pridėti teisių kontrolę ir sutvarkyti lentelę — DI pagal requirements.md (NFR-2, US-1) pasiūlė TicketPolicy: darbuotojas mato tik savo ticket'us ir gali juos kurti, admin_id padaryti nullable, lentelėje rodyti vardus vietoj ID — Priėmiau. admin_id padariau nullable, nes naujas ticket'as gali būti dar nepriskirtas.

2026-10-20 — Trūko reikalavimų FR-2, FR-5, FR-7 ir US-3 — Paprašiau DI tęsti pagal sąrašą — 5 būsenos pagal FR-5, kategorijos (enum), filtrai pagal būseną, prioritetą, kategoriją ir atsakingą asmenį, mygtukas „Assign to me“. DI pastebėjo, kad prioritetas rūšiuojamas pagal abėcėlę (high, low, medium), ir pataisė. Schemos pakeitimai įdėti į naują migraciją, nes senoji jau buvo commit'inta — Priėmiau.

2026-10-20 — Teisės neatitiko mano norimos logikos — Pats nurodžiau DI taisykles: admin mato tik jam priskirtus ticket'us, superadmin mato viską, atsakingą adminą keisti gali tik superadmin — DI įgyvendino ir perspėjo, kad tai prieštarauja FR-4 ir US-3 (ten IT specialistas užklausą priskiria pats) — Pakeičiau DI siūlytą logiką savo. FR-4 ir US-3 requirements.md faile dar neatnaujinti.

2026-10-20 — Ar greitai du kartus paspaudus „Create“ nesusikuria du įrašai? — Paklausiau DI — DI patikrino Filament kodą (mygtukas išjungiamas, veikia $isCreating apsauga) ir parašė testą, kuris tai įrodo — Papildomos apsaugos nedariau, užtenka Filament. Testą pasilikau.

2026-10-20 — Formose trūko griežtos validacijos — Paprašiau DI: ne tuščia, ne per ilga, ne vien skaičiai ir pan. — Taisyklė MeaningfulText (min. ilgis be tarpų pradžioje ir pabaigoje, privalo turėti raidžių, draudžia „aaaaaaa“), pavadinimas 5–150, aprašymas 10–5000 simbolių, tarpai nukerpami prieš išsaugant — Priėmiau.

2026-10-20 — Bandžiau diegti į Laravel Cloud — Paleidau DI su deploying-to-cloud skill — DI rado, kad Cloud CLI neprisijungęs (lūžta su segmentation fault), SQLite po diegimo Cloud aplinkoje neišliks, o seeder kuria naudotojus su slaptažodžiu „password“ — Sustabdžiau, diegimą atidėjau.

2026-10-20 — Testiniai duomenys buvo atsitiktinis lotyniškas tekstas — Paprašiau DI realių ticket'ų iš masyvo — 22 lietuviškos IT užklausos (spausdintuvas, VPN, užblokuota paskyra ir t. t.) su tinkama kategorija ir prioritetu, plius testas, kad jos praeina formos validaciją — Priėmiau.

2026-10-20 — Darbuotojas nesužino, kad jo ticket'ą kažkas sprendžia — Paprašiau DI pranešimų Filament sistemoje — Filament duomenų bazės pranešimai per TicketObserver: pranešama, kai pasikeičia būsena, prioritetas ar atsakingas asmuo. Pranešimams reikia veikiančios eilės (queue) — Priėmiau.

2026-10-20 — Reikėjo patikrinti klaidas iš bugs.md šablono — Įklijavau lentelę DI — DI patikrino abi: tuščias pavadinimas neišsaugomas, id=999 rodo 404 „Not Found“. Pridėjo testus ir užpildė bugs.md — Priėmiau. 404 puslapis kol kas angliškas.

2026-10-21 — Reikėjo 10 unit testų — Paprašiau DI paprastų testų (kūrimas, peržiūra, trynimas ir pan.) — 10 TicketPolicy testų be duomenų bazės: ką gali darbuotojas, admin ir superadmin — Priėmiau.

2026-10-23 - Pridėta lietuvių vertimai ir testai

Mano taisyklės sau

- Rašau tik REIKŠMINGUS atvejus (ne kiekvieną klausimą).
- Įrašą rašau TĄ PAČIĄ DIENĄ, kai atvejis įvyko.
- Svarbiausias stulpelis — paskutinis: ką NUSPRĘNDŽIAU ir kodėl.

