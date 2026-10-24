<?php

namespace Database\Factories;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Realistic IT support requests from a ~50 person office, so seeded data reads like the real thing.
     *
     * @var list<array{title: string, description: string, category: TicketCategory, priority: TicketPriority}>
     */
    public const SAMPLE_TICKETS = [
        [
            'title' => 'Neveikia spausdintuvas antrame aukšte',
            'description' => 'Spausdintuvas prie apskaitos skyriaus rodo popieriaus strigties klaidą, nors popieriaus viduje nėra. Bandžiau išjungti ir vėl įjungti, bet nepadėjo.',
            'category' => TicketCategory::Hardware,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'Nešiojamas kompiuteris neįsijungia',
            'description' => 'Ryte paspaudus įjungimo mygtuką tik mirkteli lemputė ir daugiau nieko nevyksta. Kroviklis prijungtas, vakar baterija buvo pilna.',
            'category' => TicketCategory::Hardware,
            'priority' => TicketPriority::High,
        ],
        [
            'title' => 'Antras monitorius rodo juodą ekraną',
            'description' => 'Po savaitgalio antras monitorius nebeatpažįstamas. Kabelį perjungiau į kitą lizdą, bet vaizdo vis tiek nėra.',
            'category' => TicketCategory::Hardware,
            'priority' => TicketPriority::Low,
        ],
        [
            'title' => 'Reikia naujos klaviatūros ir pelės',
            'description' => 'Klaviatūroje nebeveikia kelios raidės (Š, Ž), o pelės žymeklis strigčioja. Prašau pakeisti naujomis.',
            'category' => TicketCategory::Hardware,
            'priority' => TicketPriority::Low,
        ],
        [
            'title' => 'Projektorius posėdžių salėje nerodo vaizdo',
            'description' => 'Prijungus nešiojamą kompiuterį per HDMI projektorius rašo „No signal“. Rytoj 10 val. salėje vyks svarbus susitikimas su klientu.',
            'category' => TicketCategory::Hardware,
            'priority' => TicketPriority::High,
        ],
        [
            'title' => 'Outlook nuolat prašo slaptažodžio',
            'description' => 'Nuo vakar Outlook kas kelias minutes rodo langą su prašymu įvesti slaptažodį. Įvedus teisingą, po kurio laiko vėl prašo iš naujo.',
            'category' => TicketCategory::Software,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'Excel užstringa atidarant ataskaitą',
            'description' => 'Atidarant mėnesio ataskaitos failą (apie 40 MB) Excel nustoja reaguoti ir tenka ją uždaryti per užduočių tvarkyklę.',
            'category' => TicketCategory::Software,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'Prašau įdiegti PDF redagavimo programą',
            'description' => 'Darbui reikia redaguoti ir pasirašyti PDF sutartis. Prašau įdiegti Adobe Acrobat arba pasiūlyti kitą tinkamą programą.',
            'category' => TicketCategory::Software,
            'priority' => TicketPriority::Low,
        ],
        [
            'title' => 'Nepavyksta įdiegti Windows atnaujinimų',
            'description' => 'Kiekvieną kartą išjungiant kompiuterį bandoma įdiegti atnaujinimus, bet nepavyksta ir viskas atstatoma atgal. Tai užtrunka apie 20 minučių.',
            'category' => TicketCategory::Software,
            'priority' => TicketPriority::Low,
        ],
        [
            'title' => 'Apskaitos programa nepasiekia serverio',
            'description' => 'Paleidus apskaitos programą rodoma klaida „Nepavyko prisijungti prie serverio“. Kolegoms tame pačiame kabinete programa veikia.',
            'category' => TicketCategory::Software,
            'priority' => TicketPriority::High,
        ],
        [
            'title' => 'Užblokuota paskyra po slaptažodžio keitimo',
            'description' => 'Pakeičiau slaptažodį ir po to kelis kartus suklydau jį įvesdama. Dabar prisijungiant rašoma, kad paskyra užblokuota.',
            'category' => TicketCategory::Access,
            'priority' => TicketPriority::High,
        ],
        [
            'title' => 'Prieiga prie bendro aplanko „Pardavimai“',
            'description' => 'Perėjau dirbti į pardavimų skyrių, bet negaliu atidaryti bendro aplanko „Pardavimai“, nes rašoma, kad neturiu teisių.',
            'category' => TicketCategory::Access,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'Naujos darbuotojos paskyrų sukūrimas',
            'description' => 'Nuo pirmadienio rinkodaros skyriuje pradeda dirbti Rūta Petraitienė. Reikės el. pašto, kompiuterio paskyros ir prieigos prie CRM sistemos.',
            'category' => TicketCategory::Access,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'Pamiršau VPN slaptažodį',
            'description' => 'Šiandien dirbu iš namų ir negaliu prisijungti prie VPN, nes neprisimenu slaptažodžio. Prašau padėti jį atkurti.',
            'category' => TicketCategory::Access,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'Išjungti išeinančio darbuotojo prieigas',
            'description' => 'Penktadienį iš įmonės išeina Tomas Kazlauskas. Prašau nuo šeštadienio išjungti jo paskyras ir el. laiškus persiųsti jo vadovui.',
            'category' => TicketCategory::Access,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'Neveikia internetas trečiame aukšte',
            'description' => 'Visame trečiame aukšte nuo 9 val. nėra interneto nei per laidą, nei per Wi-Fi. Kituose aukštuose viskas veikia normaliai.',
            'category' => TicketCategory::Network,
            'priority' => TicketPriority::High,
        ],
        [
            'title' => 'Nutrūkinėja Wi-Fi posėdžių salėje',
            'description' => 'Posėdžių salėje Wi-Fi ryšys nuolat nutrūksta, todėl vaizdo skambučiai stringa. Kabinetuose ryšys geras.',
            'category' => TicketCategory::Network,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'VPN atsijungia kas kelias minutes',
            'description' => 'Dirbant iš namų VPN ryšys nutrūksta maždaug kas 10 minučių ir tenka jungtis iš naujo. Namų internetas veikia stabiliai.',
            'category' => TicketCategory::Network,
            'priority' => TicketPriority::Medium,
        ],
        [
            'title' => 'Svečių Wi-Fi slaptažodis partnerių vizitui',
            'description' => 'Kitą savaitę atvyksta partneriai iš Latvijos. Koks dabartinis svečių Wi-Fi slaptažodis ir ar jis dar galioja?',
            'category' => TicketCategory::Network,
            'priority' => TicketPriority::Low,
        ],
        [
            'title' => 'Perkelti telefono numerį į naują kabinetą',
            'description' => 'Persikėliau į 204 kabinetą. Prašau perkelti mano stacionaraus telefono numerį į naują darbo vietą.',
            'category' => TicketCategory::Other,
            'priority' => TicketPriority::Low,
        ],
        [
            'title' => 'Įtartinas el. laiškas apie neapmokėtą sąskaitą',
            'description' => 'Gavau laišką neva iš tiekėjo su prisegtu failu „saskaita.zip“. Atrodo įtartinai, todėl jo neatidariau. Ar tai saugu?',
            'category' => TicketCategory::Other,
            'priority' => TicketPriority::High,
        ],
        [
            'title' => 'Ar mano failai kopijuojami į serverį?',
            'description' => 'Norėčiau sužinoti, ar darbalaukyje laikomi failai yra automatiškai kopijuojami į serverį, ar turiu juos saugoti pati.',
            'category' => TicketCategory::Other,
            'priority' => TicketPriority::Low,
        ],
    ];

    /**
     * Resolved tickets get a resolution time between their creation and now, instead of "now".
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Ticket $ticket): void {
            if ($ticket->status->isResolved() && $ticket->resolved_at === null) {
                $ticket->resolved_at = $this->faker->dateTimeBetween($ticket->created_at ?? now(), now());
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sampleTicket = $this->faker->randomElement(self::SAMPLE_TICKETS);

        return [
            'title' => $sampleTicket['title'],
            'description' => $sampleTicket['description'],
            'category' => $sampleTicket['category'],
            'status' => $this->faker->randomElement(TicketStatus::cases()),
            'priority' => $sampleTicket['priority'],
            'user_id' => User::factory(),
            'admin_id' => User::factory()->admin(),
        ];
    }
}
