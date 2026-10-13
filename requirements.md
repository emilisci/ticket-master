Reikalavimai

Funkciniai reikalavimai (FR)

Nr. — Reikalavimas — MoSCoW
FR-1 — Sistema turi leisti naudotojui prisijungti su el. paštu ir slaptažodžiu. — M
FR-2 — Sistema turi leisti darbuotojui sukurti užklausą (pavadinimas, aprašymas, kategorija, prioritetas). — M
FR-3 — Sistema turi leisti darbuotojui matyti savo užklausų sąrašą ir jų būsenas. — M
FR-4 — Sistema turi leisti IT specialistui priskirti užklausą sau arba kitam specialistui. — M
FR-5 — Sistema turi leisti IT specialistui keisti užklausos būseną (nauja, vykdoma, laukiama, išspręsta, uždaryta). — M
FR-6 — Sistema turi leisti darbuotojui ir IT specialistui rašyti komentarus prie užklausos. — S
FR-7 — Sistema turi leisti IT specialistui filtruoti užklausas pagal būseną, prioritetą ir atsakingą asmenį. — S
FR-8 — Sistema turi leisti vadovui matyti atvirų, vėluojančių ir išspręstų užklausų skaičių. — C

Nefunkciniai reikalavimai (NFR)

Nr. — Reikalavimas — Matas — MoSCoW
NFR-1 — Užklausų sąrašas turi užsikrauti greitai. — < 2 s su 1000 užklausų — M
NFR-2 — Darbuotojas mato tik savo užklausas, o ne kitų. — 0 svetimų užklausų darbuotojo sąraše — M
NFR-3 — Sistema veikia naršyklėje kompiuteryje ir telefone. — Veikia Chrome ir Firefox, ekranas nuo 375 px pločio — S

User stories su priimtinumo kriterijais

US-1
Kaip darbuotojas, noriu pateikti užklausą per formą, kad nereikėtų skambinti ar rašyti laiškų.
AC: Kai užpildau privalomus laukus ir paspaudžiu „Sukurti“, tada užklausa atsiranda mano sąraše su būsena „Nauja“.

US-2
Kaip darbuotojas, noriu matyti savo užklausos būseną, kad žinočiau, ar ji jau sprendžiama.
AC: Kai IT specialistas pakeičia būseną, tada atnaujinęs puslapį matau naują būseną savo sąraše.

US-3
Kaip IT specialistas, noriu priskirti užklausą sau, kad kiti žinotų, jog ją sprendžiu.
AC: Kai paspaudžiu „Priskirti man“, tada užklausos lauke „Atsakingas“ rodomas mano vardas.

US-4
Kaip IT specialistas, noriu filtruoti užklausas pagal prioritetą, kad pirmiausia spręsčiau svarbiausias.
AC: Kai pasirenku filtrą „Aukštas“, tada sąraše rodomos tik aukšto prioriteto užklausos.

US-5
Kaip vadovas, noriu matyti vėluojančių užklausų skaičių, kad laiku pastebėčiau problemas.
AC: Kai atidarau pradinį puslapį, tada matau atvirų ir vėluojančių (senesnių nei 3 darbo dienos) užklausų skaičių.
