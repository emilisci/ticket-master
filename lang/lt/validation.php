<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Lithuanian versions of Laravel's default validation messages. Phrasing
    | avoids number agreement ("5 simboliai" vs "10 simbolių"), which
    | validation messages cannot express.
    |
    */

    'accepted' => 'Laukas „:attribute“ turi būti patvirtintas.',
    'accepted_if' => 'Laukas „:attribute“ turi būti patvirtintas, kai :other yra :value.',
    'active_url' => 'Laukas „:attribute“ turi būti veikianti nuoroda.',
    'after' => 'Laukas „:attribute“ turi būti data po :date.',
    'after_or_equal' => 'Laukas „:attribute“ turi būti data po :date arba ta pati.',
    'alpha' => 'Laukas „:attribute“ gali turėti tik raides.',
    'alpha_dash' => 'Laukas „:attribute“ gali turėti tik raides, skaičius, brūkšnelius ir pabraukimus.',
    'alpha_num' => 'Laukas „:attribute“ gali turėti tik raides ir skaičius.',
    'array' => 'Laukas „:attribute“ turi būti masyvas.',
    'ascii' => 'Laukas „:attribute“ gali turėti tik lotyniškus simbolius.',
    'before' => 'Laukas „:attribute“ turi būti data prieš :date.',
    'before_or_equal' => 'Laukas „:attribute“ turi būti data prieš :date arba ta pati.',
    'between' => [
        'array' => 'Elementų skaičius lauke „:attribute“ turi būti nuo :min iki :max.',
        'file' => 'Failo „:attribute“ dydis turi būti nuo :min iki :max KB.',
        'numeric' => 'Laukas „:attribute“ turi būti tarp :min ir :max.',
        'string' => 'Simbolių skaičius lauke „:attribute“ turi būti nuo :min iki :max.',
    ],
    'boolean' => 'Laukas „:attribute“ turi būti „taip“ arba „ne“.',
    'can' => 'Lauke „:attribute“ yra neleistina reikšmė.',
    'confirmed' => 'Lauko „:attribute“ patvirtinimas nesutampa.',
    'contains' => 'Laukui „:attribute“ trūksta privalomos reikšmės.',
    'current_password' => 'Slaptažodis neteisingas.',
    'date' => 'Laukas „:attribute“ turi būti data.',
    'date_equals' => 'Laukas „:attribute“ turi būti data, lygi :date.',
    'date_format' => 'Laukas „:attribute“ turi atitikti formatą :format.',
    'decimal' => 'Laukas „:attribute“ turi turėti :decimal skaitmenis po kablelio.',
    'declined' => 'Laukas „:attribute“ turi būti atmestas.',
    'declined_if' => 'Laukas „:attribute“ turi būti atmestas, kai :other yra :value.',
    'different' => 'Laukai „:attribute“ ir „:other“ turi skirtis.',
    'digits' => 'Laukas „:attribute“ turi būti :digits skaitmenų ilgio.',
    'digits_between' => 'Laukas „:attribute“ turi būti nuo :min iki :max skaitmenų ilgio.',
    'dimensions' => 'Paveikslėlio „:attribute“ matmenys netinkami.',
    'distinct' => 'Lauko „:attribute“ reikšmė kartojasi.',
    'doesnt_contain' => 'Laukas „:attribute“ negali turėti šių reikšmių: :values.',
    'doesnt_end_with' => 'Laukas „:attribute“ negali baigtis: :values.',
    'doesnt_start_with' => 'Laukas „:attribute“ negali prasidėti: :values.',
    'email' => 'Laukas „:attribute“ turi būti teisingas el. pašto adresas.',
    'ends_with' => 'Laukas „:attribute“ turi baigtis viena iš šių reikšmių: :values.',
    'enum' => 'Pasirinkta „:attribute“ reikšmė neleistina.',
    'exists' => 'Pasirinkta „:attribute“ reikšmė neleistina.',
    'extensions' => 'Failas „:attribute“ turi būti vieno iš šių tipų: :values.',
    'file' => 'Laukas „:attribute“ turi būti failas.',
    'filled' => 'Laukas „:attribute“ turi būti užpildytas.',
    'gt' => [
        'array' => 'Elementų skaičius lauke „:attribute“ turi būti didesnis nei :value.',
        'file' => 'Failo „:attribute“ dydis turi būti didesnis nei :value KB.',
        'numeric' => 'Laukas „:attribute“ turi būti didesnis nei :value.',
        'string' => 'Simbolių skaičius lauke „:attribute“ turi būti didesnis nei :value.',
    ],
    'gte' => [
        'array' => 'Elementų skaičius lauke „:attribute“ turi būti ne mažesnis nei :value.',
        'file' => 'Failo „:attribute“ dydis turi būti ne mažesnis nei :value KB.',
        'numeric' => 'Laukas „:attribute“ turi būti ne mažesnis nei :value.',
        'string' => 'Simbolių skaičius lauke „:attribute“ turi būti ne mažesnis nei :value.',
    ],
    'hex_color' => 'Laukas „:attribute“ turi būti teisinga šešioliktainė spalva.',
    'image' => 'Laukas „:attribute“ turi būti paveikslėlis.',
    'in' => 'Pasirinkta „:attribute“ reikšmė neleistina.',
    'in_array' => 'Lauko „:attribute“ reikšmės nėra lauke „:other“.',
    'in_array_keys' => 'Lauke „:attribute“ turi būti bent vienas iš šių raktų: :values.',
    'integer' => 'Laukas „:attribute“ turi būti sveikasis skaičius.',
    'ip' => 'Laukas „:attribute“ turi būti teisingas IP adresas.',
    'ipv4' => 'Laukas „:attribute“ turi būti teisingas IPv4 adresas.',
    'ipv6' => 'Laukas „:attribute“ turi būti teisingas IPv6 adresas.',
    'json' => 'Laukas „:attribute“ turi būti teisinga JSON eilutė.',
    'list' => 'Laukas „:attribute“ turi būti sąrašas.',
    'lowercase' => 'Laukas „:attribute“ turi būti parašytas mažosiomis raidėmis.',
    'lt' => [
        'array' => 'Elementų skaičius lauke „:attribute“ turi būti mažesnis nei :value.',
        'file' => 'Failo „:attribute“ dydis turi būti mažesnis nei :value KB.',
        'numeric' => 'Laukas „:attribute“ turi būti mažesnis nei :value.',
        'string' => 'Simbolių skaičius lauke „:attribute“ turi būti mažesnis nei :value.',
    ],
    'lte' => [
        'array' => 'Elementų skaičius lauke „:attribute“ negali būti didesnis nei :value.',
        'file' => 'Failo „:attribute“ dydis negali būti didesnis nei :value KB.',
        'numeric' => 'Laukas „:attribute“ negali būti didesnis nei :value.',
        'string' => 'Simbolių skaičius lauke „:attribute“ negali būti didesnis nei :value.',
    ],
    'mac_address' => 'Laukas „:attribute“ turi būti teisingas MAC adresas.',
    'max' => [
        'array' => 'Elementų skaičius lauke „:attribute“ negali būti didesnis nei :max.',
        'file' => 'Failo „:attribute“ dydis negali būti didesnis nei :max KB.',
        'numeric' => 'Laukas „:attribute“ negali būti didesnis nei :max.',
        'string' => 'Simbolių skaičius lauke „:attribute“ negali būti didesnis nei :max.',
    ],
    'max_digits' => 'Skaitmenų skaičius lauke „:attribute“ negali būti didesnis nei :max.',
    'mimes' => 'Failas „:attribute“ turi būti vieno iš šių tipų: :values.',
    'mimetypes' => 'Failas „:attribute“ turi būti vieno iš šių tipų: :values.',
    'min' => [
        'array' => 'Elementų skaičius lauke „:attribute“ turi būti ne mažesnis nei :min.',
        'file' => 'Failo „:attribute“ dydis turi būti ne mažesnis nei :min KB.',
        'numeric' => 'Laukas „:attribute“ turi būti ne mažesnis nei :min.',
        'string' => 'Simbolių skaičius lauke „:attribute“ turi būti ne mažesnis nei :min.',
    ],
    'min_digits' => 'Skaitmenų skaičius lauke „:attribute“ turi būti ne mažesnis nei :min.',
    'missing' => 'Laukas „:attribute“ turi nebūti pateiktas.',
    'missing_if' => 'Laukas „:attribute“ turi nebūti pateiktas, kai :other yra :value.',
    'missing_unless' => 'Laukas „:attribute“ turi nebūti pateiktas, nebent :other yra :value.',
    'missing_with' => 'Laukas „:attribute“ turi nebūti pateiktas, kai yra :values.',
    'missing_with_all' => 'Laukas „:attribute“ turi nebūti pateiktas, kai yra :values.',
    'multiple_of' => 'Laukas „:attribute“ turi būti :value kartotinis.',
    'not_in' => 'Pasirinkta „:attribute“ reikšmė neleistina.',
    'not_regex' => 'Lauko „:attribute“ formatas neteisingas.',
    'numeric' => 'Laukas „:attribute“ turi būti skaičius.',
    'password' => [
        'letters' => 'Laukas „:attribute“ turi turėti bent vieną raidę.',
        'mixed' => 'Laukas „:attribute“ turi turėti bent vieną didžiąją ir vieną mažąją raidę.',
        'numbers' => 'Laukas „:attribute“ turi turėti bent vieną skaičių.',
        'symbols' => 'Laukas „:attribute“ turi turėti bent vieną simbolį.',
        'uncompromised' => 'Šis „:attribute“ buvo rastas nutekintų duomenų sąrašuose. Pasirinkite kitą.',
    ],
    'present' => 'Laukas „:attribute“ turi būti pateiktas.',
    'present_if' => 'Laukas „:attribute“ turi būti pateiktas, kai :other yra :value.',
    'present_unless' => 'Laukas „:attribute“ turi būti pateiktas, nebent :other yra :value.',
    'present_with' => 'Laukas „:attribute“ turi būti pateiktas, kai yra :values.',
    'present_with_all' => 'Laukas „:attribute“ turi būti pateiktas, kai yra :values.',
    'prohibited' => 'Laukas „:attribute“ draudžiamas.',
    'prohibited_if' => 'Laukas „:attribute“ draudžiamas, kai :other yra :value.',
    'prohibited_if_accepted' => 'Laukas „:attribute“ draudžiamas, kai :other patvirtintas.',
    'prohibited_if_declined' => 'Laukas „:attribute“ draudžiamas, kai :other atmestas.',
    'prohibited_unless' => 'Laukas „:attribute“ draudžiamas, nebent :other yra :values.',
    'prohibits' => 'Laukas „:attribute“ draudžia pateikti :other.',
    'regex' => 'Lauko „:attribute“ formatas neteisingas.',
    'required' => 'Laukas „:attribute“ yra privalomas.',
    'required_array_keys' => 'Lauke „:attribute“ turi būti šie raktai: :values.',
    'required_if' => 'Laukas „:attribute“ yra privalomas, kai :other yra :value.',
    'required_if_accepted' => 'Laukas „:attribute“ yra privalomas, kai :other patvirtintas.',
    'required_if_declined' => 'Laukas „:attribute“ yra privalomas, kai :other atmestas.',
    'required_unless' => 'Laukas „:attribute“ yra privalomas, nebent :other yra :values.',
    'required_with' => 'Laukas „:attribute“ yra privalomas, kai yra :values.',
    'required_with_all' => 'Laukas „:attribute“ yra privalomas, kai yra :values.',
    'required_without' => 'Laukas „:attribute“ yra privalomas, kai nėra :values.',
    'required_without_all' => 'Laukas „:attribute“ yra privalomas, kai nėra nė vieno iš :values.',
    'same' => 'Laukai „:attribute“ ir „:other“ turi sutapti.',
    'size' => [
        'array' => 'Lauke „:attribute“ turi būti lygiai :size elementų.',
        'file' => 'Failo „:attribute“ dydis turi būti :size KB.',
        'numeric' => 'Laukas „:attribute“ turi būti :size.',
        'string' => 'Simbolių skaičius lauke „:attribute“ turi būti :size.',
    ],
    'starts_with' => 'Laukas „:attribute“ turi prasidėti viena iš šių reikšmių: :values.',
    'string' => 'Laukas „:attribute“ turi būti tekstas.',
    'timezone' => 'Laukas „:attribute“ turi būti teisinga laiko juosta.',
    'unique' => 'Tokia „:attribute“ reikšmė jau naudojama.',
    'uploaded' => 'Nepavyko įkelti failo „:attribute“.',
    'uppercase' => 'Laukas „:attribute“ turi būti parašytas didžiosiomis raidėmis.',
    'url' => 'Laukas „:attribute“ turi būti teisinga nuoroda.',
    'ulid' => 'Laukas „:attribute“ turi būti teisingas ULID.',
    'uuid' => 'Laukas „:attribute“ turi būti teisingas UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [],

];
