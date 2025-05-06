<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/db_connector.php';
require_once __DIR__ . '/redis_service.php';

// Klasa do synchronizacji danych
class DataSynchronizer {
    private $mysql;
    private $redis;
    
    public function __construct() {
        $this->mysql = new MySQLConnector();
        $this->redis = new RedisService();
    }
    
    public function syncData() {
        echo "Rozpoczynanie synchronizacji danych...\n";
        
        // Przykładowe zapytanie - dostosuj do swojej struktury bazy danych
        $query = "SELECT id, title, content FROM articles";
        
        // Pobierz dane z MySQL
        $data = $this->mysql->fetchAllData($query);
        
        if (empty($data)) {
            echo "Brak danych do synchronizacji\n";
            return;
        }
        
        // Wyczyść istniejący indeks wyszukiwania
        $this->redis->clearSearchIndex();
        
        // Zapisz dane w Redis i zbuduj indeks wyszukiwania
        $count = 0;
        foreach ($data as $item) {
            // Zapisz pełne dane rekordu
            $this->redis->storeData("article:" . $item['id'], $item);
            
            // Zindeksuj pola do wyszukiwania
            $searchText = $item['title'] . ' ' . $item['content'];
            $this->redis->storeSearchIndex($item['id'], $searchText);
            $count++;
        }
        
        echo "Synchronizacja zakończona. Zaktualizowano {$count} rekordów.\n";
    }
}

// Uruchom synchronizację jeśli skrypt jest uruchamiany bezpośrednio
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $synchronizer = new DataSynchronizer();
    $synchronizer->syncData();
}