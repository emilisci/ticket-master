# Klaidų sąrašas

| # | Klaida | Eiga | Tikėta | Faktas | Pavojingas | Pr. | Statusas |
|---|--------|------|--------|--------|------------|-----|----------|
| 1 | Išsaugomas tuščias pavadinimas | Įvedžiau „“ (tuščią pavadinimą) ir paspaudžiau „Create“ | Pranešimas apie klaidą, užklausa neišsaugoma | Rodoma klaida „The title field is required.“, užklausa neišsaugoma | Naudotojui | P1 | Patikrinta – neatsikartoja (testas `TicketValidationTest`, atvejis „title is an empty string“) |
| 2 | id=999 atidaro tuščią puslapį | Atidariau seną nuorodą `/tickets/999` ir `/tickets/999/edit` | Puslapis „nerasta“ | Rodomas 404 „Not Found“ puslapis | Naudotojui | P2 | Patikrinta – neatsikartoja (testas `TicketAccessTest::test_missing_ticket_shows_not_found_page`) |
