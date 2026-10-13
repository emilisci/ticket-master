Technologijų sprendimas

Kalbos / karkaso palyginimas

Kriterijus — Variantas A: Laravel + Filament — Variantas B: Django
Oficiali dokumentacija — yra, daug pavyzdžių — yra, geras tutorialas
Bendruomenė — didelė, atsakymus randu greitai — irgi didelė
Diegimas kitoje mašinoje — apie 5 žingsnius — apie 5 žingsnius
Mano pasirengimas — Laravel moku, Filament reikės pasimokyti — Django nemoku

Duomenų bazės palyginimas

Kriterijus — Variantas A: MySQL — Variantas B: SQLite
Kaip atvaizduoja esybes ir ryšius — lentelės ir ryšiai, viskas gerai — tas pats, bet silpniau su daug naudotojų
Kas reikalinga paleisti — įdiegti MySQL (Laragon) — nieko, tik failas
Migracijos / atsarginės kopijos — Laravel migracijos, mysqldump — Laravel migracijos, nukopijuoti failą

Sistemos tipas

Naršyklė — kodėl: darbuotojams nereikės nieko diegti, užtenka atsidaryti puslapį.

Sprendimas

Rinkinys: PHP + Laravel + Filament + MySQL + naršyklė

Argumentas 1: Filament greitai sukuria formas, sąrašus su filtrais ir dashboard (FR-2, FR-7, FR-8).

Argumentas 2: Laravel jau moku, nereikės mokytis naujo karkaso.

Didžiausia rizika: Filament nemoku.

Atsarginis planas: darbuotojo puslapius daryti su paprastu Blade.
