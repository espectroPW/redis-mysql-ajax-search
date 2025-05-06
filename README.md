<h1 align="center">Wyszukiwarka w czasie rzeczywistym</h1>

<p align="center">
  <img src="[https://img.shields.io/badge/PHP-7.4%2B-blue"](https://img.shields.io/badge/PHP-7.4%2B-blue") alt="PHP 7.4+">
  <img src="[https://img.shields.io/badge/Redis-latest-red"](https://img.shields.io/badge/Redis-latest-red") alt="Redis">
  <img src="[https://img.shields.io/badge/MySQL-5.7%2B-orange"](https://img.shields.io/badge/MySQL-5.7%2B-orange") alt="MySQL 5.7+">
  <img src="[https://img.shields.io/badge/jQuery-3.6.0-blue"](https://img.shields.io/badge/jQuery-3.6.0-blue") alt="jQuery 3.6.0">
  <img src="[https://img.shields.io/badge/license-MIT-green"](https://img.shields.io/badge/license-MIT-green") alt="License MIT">
</p>

<p align="center">
  System wyszukiwania w czasie rzeczywistym wykorzystujący PHP, Redis i jQuery.
</p>

<hr>

<h2>📋 Wymagania</h2>

<ul>
  <li>PHP 7.4+</li>
  <li>Redis</li>
  <li>MySQL/MariaDB</li>
  <li>Composer</li>
</ul>

<h2>🚀 Instalacja</h2>

<ol>
  <li>
    <p>Sklonuj repozytorium:</p>
  </li>
  <li>
    <p>Zainstaluj zależności:</p>
    <pre><code>composer install</code></pre>
  </li>
  <li>
    <p>Utwórz plik .env na podstawie .env.example:</p>
    <pre><code>cp .env.example .env</code></pre>
  </li>
  <li>
    <p>Wypełnij plik .env swoimi danymi dostępowymi:</p>
    <pre><code># MySQL Configuration
MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_USER=root
MYSQL_PASS=your_secure_password
MYSQL_DB=search_db

# Redis Configuration
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_DB=0
REDIS_PASSWORD=null

# Application Configuration
SEARCH_PREFIX=search:</code></pre>
  </li>
  <li>
    <p>Utwórz bazę danych MySQL:</p>
    <pre><code>CREATE DATABASE search_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</code></pre>
  </li>
  <li>
    <p>Utwórz tabelę w bazie danych MySQL:</p>
    <pre><code>USE search_db;

CREATE TABLE articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);</code></pre>
  </li>
  <li>
    <p>Dodaj przykładowe dane do tabeli:</p>
    <pre><code>INSERT INTO articles (title, content) VALUES 
('PHP i Redis', 'Artykuł o integracji PHP z Redis dla szybkiego wyszukiwania w czasie rzeczywistym.'),
('Wyszukiwanie pełnotekstowe', 'Jak zbudować wydajną wyszukiwarkę w czasie rzeczywistym z wykorzystaniem Redis.'),
('jQuery i AJAX', 'Wykorzystanie jQuery do asynchronicznych zapytań AJAX w aplikacjach webowych.'),
('NoSQL vs SQL', 'Porównanie baz danych NoSQL i SQL pod kątem wydajności i zastosowań.'),
('Optymalizacja wyszukiwania', 'Techniki optymalizacji wyszukiwania w dużych zbiorach danych.'),
('Redis jako cache', 'Wykorzystanie Redis jako warstwy cache dla aplikacji webowych.'),
('PHP 8 nowe funkcje', 'Przegląd nowych funkcji i ulepszeń w PHP 8, które przyspieszają rozwój aplikacji.'),
('JavaScript w aplikacjach SPA', 'Jak wykorzystać JavaScript do budowy aplikacji Single Page Application.'),
('Bezpieczeństwo aplikacji webowych', 'Najlepsze praktyki zabezpieczania aplikacji webowych przed atakami.');</code></pre>
  </li>
  <li>
    <p>Uruchom skrypt synchronizacji danych:</p>
    <pre><code>php sync_data.php</code></pre>
  </li>
</ol>

<h2>💻 Użycie</h2>

<ol>
  <li>
    <p>Uruchom serwer PHP:</p>
    <pre><code>php -S localhost:8000</code></pre>
  </li>
  <li>
    <p>Otwórz przeglądarkę i przejdź do <code>http://localhost:8000</code></p>
  </li>
  <li>
    <p>Zacznij wpisywać tekst w polu wyszukiwania, aby zobaczyć wyniki w czasie rzeczywistym.</p>
  </li>
</ol>

<h2>📁 Struktura projektu</h2>

<pre>
.
├── .env                  # Konfiguracja środowiska
├── .env.example          # Przykładowa konfiguracja
├── bootstrap.php         # Plik inicjalizujący aplikację
├── composer.json         # Zależności projektu
├── db_connector.php      # Klasa do połączenia z MySQL
├── redis_service.php     # Klasa do obsługi Redis
├── sync_data.php         # Skrypt synchronizacji danych z MySQL do Redis
├── search.php            # Endpoint API do wyszukiwania
├── cronjob.php           # Skrypt do uruchamiania przez cron
├── index.html            # Interfejs użytkownika
└── js/
    └── search.js         # Skrypt JavaScript do obsługi wyszukiwania
</pre>

<h2>🔍 Jak to działa</h2>

<ol>
  <li>Dane są przechowywane w bazie danych MySQL.</li>
  <li>Skrypt <code>sync_data.php</code> pobiera dane z MySQL i zapisuje je w Redis.</li>
  <li>Dla każdego słowa w tytule i treści artykułu tworzone są indeksy w Redis.</li>
  <li>Gdy użytkownik wpisuje zapytanie, JavaScript wysyła asynchroniczne żądanie do <code>search.php</code>.</li>
  <li><code>search.php</code> korzysta z Redis do szybkiego wyszukiwania pasujących rekordów.</li>
  <li>Wyniki są zwracane jako JSON i wyświetlane na stronie bez przeładowania.</li>
</ol>

<h2>⏰ Synchronizacja danych</h2>

<p>Aby regularnie synchronizować dane z MySQL do Redis, możesz dodać zadanie cron:</p>

<pre><code>*/30 * * * * php /ścieżka/do/projektu/cronjob.php >> /ścieżka/do/logu/sync.log 2>&1</code></pre>

<p>To zadanie będzie synchronizować dane co 30 minut.</p>

<h2>❓ Rozwiązywanie problemów</h2>

<h3>Nie można połączyć się z Redis</h3>
<ul>
  <li>Sprawdź czy serwer Redis jest uruchomiony: <code>redis-cli ping</code></li>
  <li>Sprawdź konfigurację w pliku .env</li>
</ul>

<h3>Nie można połączyć się z MySQL</h3>
<ul>
  <li>Sprawdź czy serwer MySQL jest uruchomiony</li>
  <li>Sprawdź poprawność danych logowania w pliku .env</li>
  <li>Sprawdź czy baza danych istnieje</li>
</ul>

<h3>Brak wyników wyszukiwania</h3>
<ul>
  <li>Uruchom <code>php sync_data.php</code> aby zsynchronizować dane</li>
  <li>Sprawdź czy w bazie MySQL są jakieś dane</li>
  <li>Sprawdź logi błędów PHP</li>
</ul>

<h2>🔧 Przykład uruchomienia</h2>

<p>Oto kompletny przykład uruchomienia systemu od zera:</p>

<pre><code># Instalacja Redis (Ubuntu/Debian)
sudo apt update
sudo apt install redis-server
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Instalacja MySQL (Ubuntu/Debian)
sudo apt install mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql

# Konfiguracja MySQL
sudo mysql_secure_installation
sudo mysql -e "CREATE DATABASE search_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'searchuser'@'localhost' IDENTIFIED BY 'password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON search_db.* TO 'searchuser'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Tworzenie tabeli i dodawanie przykładowych danych
mysql -u searchuser -p search_db < setup.sql

# Klonowanie repozytorium
git clone https://github.com/username/realtime-search.git
cd realtime-search

# Instalacja zależności
composer install

# Konfiguracja środowiska
cp .env.example .env
# Edytuj plik .env, aby dostosować konfigurację

# Synchronizacja danych
php sync_data.php

# Uruchomienie serwera PHP
php -S localhost:8000

# Teraz otwórz przeglądarkę i przejdź do http://localhost:8000</code></pre>

<h3>Przykład pliku setup.sql</h3>

<pre><code>CREATE TABLE articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO articles (title, content) VALUES 
('PHP i Redis', 'Artykuł o integracji PHP z Redis dla szybkiego wyszukiwania w czasie rzeczywistym.'),
('Wyszukiwanie pełnotekstowe', 'Jak zbudować wydajną wyszukiwarkę w czasie rzeczywistym z wykorzystaniem Redis.'),
('jQuery i AJAX', 'Wykorzystanie jQuery do asynchronicznych zapytań AJAX w aplikacjach webowych.'),
('NoSQL vs SQL', 'Porównanie baz danych NoSQL i SQL pod kątem wydajności i zastosowań.'),
('Optymalizacja wyszukiwania', 'Techniki optymalizacji wyszukiwania w dużych zbiorach danych.'),
('Redis jako cache', 'Wykorzystanie Redis jako warstwy cache dla aplikacji webowych.'),
('PHP 8 nowe funkcje', 'Przegląd nowych funkcji i ulepszeń w PHP 8, które przyspieszają rozwój aplikacji.'),
('JavaScript w aplikacjach SPA', 'Jak wykorzystać JavaScript do budowy aplikacji Single Page Application.'),
('Bezpieczeństwo aplikacji webowych', 'Najlepsze praktyki zabezpieczania aplikacji webowych przed atakami.');</code></pre>

<h2>📊 Wydajność</h2>

<p>System został zoptymalizowany pod kątem wydajności:</p>

<ul>
  <li>Redis zapewnia szybkie wyszukiwanie z czasem odpowiedzi poniżej 10ms</li>
  <li>Indeksowanie słów umożliwia wyszukiwanie pełnotekstowe</li>
  <li>Asynchroniczne zapytania AJAX zapewniają płynne działanie interfejsu</li>
  <li>Opóźnione wyszukiwanie (debouncing) zmniejsza liczbę zapytań podczas wpisywania</li>
</ul>

<h2>🔜 Plany rozwoju</h2>

<ul>
  <li>Dodanie zaawansowanych filtrów wyszukiwania</li>
  <li>Implementacja podświetlania wyników wyszukiwania</li>
  <li>Dodanie opcji sortowania wyników</li>
  <li>Wsparcie dla paginacji wyników</li>
  <li>Dodanie statystyk wyszukiwania</li>
</ul>

<h2>🤝 Wkład w rozwój</h2>

<ol>
  <li>Forkuj repozytorium</li>
  <li>Utwórz gałąź dla swojej funkcji (<code>git checkout -b feature/amazing-feature</code>)</li>
  <li>Zatwierdź swoje zmiany (<code>git commit -m 'Add some amazing feature'</code>)</li>
  <li>Wypchnij do gałęzi (<code>git push origin feature/amazing-feature</code>)</li>
  <li>Otwórz Pull Request</li>
</ol>

<h2>📝 Licencja</h2>

<p>Rozpowszechniane na licencji MIT. Zobacz <code>LICENSE</code> aby uzyskać więcej informacji.</p>

<h2>📞 Kontakt</h2>

<p>Twoje Imię - <a href="https://twitter.com/username">@username</a> - email@example.com</p>

<p>Link do projektu: <a href="https://github.com/username/realtime-search">https://github.com/username/realtime-search</a></p>

<hr>

<p align="center">
  <a href="#wyszukiwarka-w-czasie-rzeczywistym">Powrót do góry</a>
</p>
