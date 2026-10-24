Ticket Master

Sistema leidžia įmonės darbuotojams ir IT skyriui registruoti, sekti ir spręsti IT pagalbos užklausas vienoje vietoje.

## Reikalavimai

- PHP 8.3 ar naujesnė su `intl`, `pdo_sqlite`, `mbstring`, `openssl` plėtiniais
- [Composer](https://getcomposer.org/)
- Node.js 22+ ir npm
- Git

Lokaliam testavimui naudojama SQLite, todėl atskiro duomenų bazės serverio nereikia.

## Diegimas

1. Nuklonuokite repozitoriją ir pereikite į projekto katalogą:

   ```bash
   git clone <repozitorijos-url> ticket-master
   cd ticket-master
   ```

2. Įdiekite PHP priklausomybes:

   ```bash
   composer install
   ```

3. Sukurkite `.env` failą ir sugeneruokite aplikacijos raktą:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Sukurkite SQLite duomenų bazės failą (jis neįtrauktas į Git):

   ```bash
   touch database/database.sqlite
   ```

   Windows PowerShell: `New-Item database/database.sqlite -ItemType File`

5. Paleiskite migracijas su pradiniais duomenimis:

   ```bash
   php artisan migrate --seed
   ```

6. Įdiekite ir sukompiliuokite frontend:

   ```bash
   npm install
   npm run build
   ```

## Paleidimas

```bash
composer run dev 
```

arba

```bash
php artisan serve
```

Ši komanda vienu metu paleidžia web serverį, eilės (queue) darbuotoją ir Vite. Aplikacija pasiekiama adresu <http://localhost:8000>.

## Testiniai naudotojai

Po `php artisan migrate --seed` sukuriami šie naudotojai (slaptažodis visiems — `password`):

| Rolė       | El. paštas               |
|------------|--------------------------|
| Superadmin | superadmin@example.com   |
| Admin      | admin@example.com        |
| Naudotojas | user@example.com         |

Duomenų bazę iš naujo galima sukurti su `php artisan migrate:fresh --seed`.

## Testai ir kodo kokybė

```bash
php artisan test     # testai
composer run lint    # kodo formatavimas (Pint)
composer run test    # Pint patikra, PHPStan ir testai
```

Gynimui pasiruošta!