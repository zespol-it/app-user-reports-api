# Laminas User Reports

## Wymagania
- PHP 8.1+
- Composer
- Rozszerzenia PHP: pdo_sqlite, ext-dom

## Instalacja
1. Sklonuj repozytorium lub pobierz projekt.
2. Zainstaluj zależności:
   ```
   composer install
   ```
3. Utwórz plik bazy danych SQLite (jeśli nie istnieje):
   ```
   mkdir -p data
   touch data/app_user_reports.sqlite
   ```
4. Utwórz schemat bazy:
   ```
   vendor/bin/doctrine-module orm:schema-tool:create
   ```
5. (Opcjonalnie) Dodaj przykładowe dane:
   - Wywołaj endpoint `/api/education/seed` i `/api/user/seed` (np. przez przeglądarkę lub Postman)

## Uruchomienie serwera
```
php -S 0.0.0.0:8080 -t public
```

## Endpointy REST API
- GET/POST/PUT/DELETE `/api/user` — użytkownicy
- GET/POST/PUT/DELETE `/api/education` — wykształcenie
- GET `/api/user/export-xls` — eksport do XLS
- GET `/api/user/export-pdf` — eksport do PDF

## Testy
```
vendor/bin/phpunit
```

## Eksport
- XLS: `/api/user/export-xls`
- PDF: `/api/user/export-pdf` 