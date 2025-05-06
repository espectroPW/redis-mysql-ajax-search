<?php
require_once __DIR__ . '/bootstrap.php';

class RedisService {
    private $redis;
    
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        try {
            $this->redis = new Predis\Client([
                'scheme' => 'tcp',
                'host' => $_ENV['REDIS_HOST'],
                'port' => (int)$_ENV['REDIS_PORT'],
                'database' => (int)$_ENV['REDIS_DB'],
                'password' => $_ENV['REDIS_PASSWORD'] !== 'null' ? $_ENV['REDIS_PASSWORD'] : null
            ]);
            
            // Testowe połączenie
            $this->redis->ping();
        } catch (Exception $e) {
            error_log("Błąd podczas łączenia z Redis: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function storeData($key, $data) {
        try {
            return $this->redis->set($key, json_encode($data, JSON_UNESCAPED_UNICODE));
        } catch (Exception $e) {
            error_log("Błąd podczas zapisywania danych w Redis: " . $e->getMessage());
            return false;
        }
    }
    
    public function getData($key) {
        try {
            $data = $this->redis->get($key);
            if ($data) {
                return json_decode($data, true);
            }
            return null;
        } catch (Exception $e) {
            error_log("Błąd podczas pobierania danych z Redis: " . $e->getMessage());
            return null;
        }
    }
    
    public function storeSearchIndex($id, $text) {
        $searchPrefix = $_ENV['SEARCH_PREFIX'];
        $words = preg_split('/\s+/', mb_strtolower(trim($text), 'UTF-8'));
        
        foreach ($words as $word) {
            $word = trim($word);
            if (!empty($word)) {
                $this->redis->sadd($searchPrefix . $word, $id);
            }
        }
    }
    
    public function search($query) {
        $searchPrefix = $_ENV['SEARCH_PREFIX'];
        $words = preg_split('/\s+/', mb_strtolower(trim($query), 'UTF-8'));
        $words = array_filter($words, function($word) {
            return !empty(trim($word));
        });
        
        if (empty($words)) {
            return [];
        }
        
        // Pobierz ID dla pierwszego słowa
        $firstWord = trim($words[0]);
        $resultIds = $this->redis->smembers($searchPrefix . $firstWord);
        
        // Wykonaj przecięcie zbiorów dla pozostałych słów
        for ($i = 1; $i < count($words); $i++) {
            $word = trim($words[$i]);
            $wordIds = $this->redis->smembers($searchPrefix . $word);
            $resultIds = array_intersect($resultIds, $wordIds);
        }
        
        return $resultIds;
    }
    
    public function clearSearchIndex() {
        $searchPrefix = $_ENV['SEARCH_PREFIX'];
        $keys = $this->redis->keys($searchPrefix . '*');
        if (!empty($keys)) {
            $this->redis->del($keys);
        }
    }
    
    public function getRecordsByIds($ids) {
        $results = [];
        foreach ($ids as $id) {
            $data = $this->getData("article:" . $id);
            if ($data) {
                $results[] = $data;
            }
        }
        return $results;
    }
}