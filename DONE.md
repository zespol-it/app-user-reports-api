# DONE

## Co zostało zrobione
- Utworzono projekt Laminas z REST API
- Skonfigurowano Doctrine ORM z bazą SQLite
- Utworzono encje User i Education
- Zaimplementowano endpointy CRUD dla obu encji
- Dodano sortowanie, filtrowanie i paginację w API użytkowników
- Dodano eksport do XLS (PhpSpreadsheet) i PDF (Dompdf)
- Dodano seedowanie przykładowych danych
- Pokryto kluczowe funkcje testami PHPUnit

## Przemyślenia
- Laminas dobrze sprawdza się do budowy modularnych API, choć konfiguracja jest bardziej rozbudowana niż np. w Symfony.
- Doctrine z SQLite działa bezproblemowo do celów testowych i deweloperskich.
- Testy jednostkowe i integracyjne pozwalają szybko wykryć regresje.
- Eksport do XLS i PDF wymagał dodatkowych bibliotek, ale integracja przebiegła sprawnie.
- Projekt można łatwo rozbudować o dashboard (np. frontend w React/Vue lub Laminas MVC).
- Kod jest gotowy do dalszego rozwoju i wdrożenia. 