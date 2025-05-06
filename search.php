<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/redis_service.php';

header('Content-Type: application/json');

// SprawdŸ czy zapytanie jest typu GET i zawiera parametr 'q'
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $query = trim($_GET['q']);
    
    // Jeœli zapytanie jest puste, zwróæ pust¹ tablicê
    if (empty($query)) {
        echo json_encode(['results' => []]);
        exit;
    }
    
    try {
        $redis = new RedisService();
        
        // Wyszukaj ID pasuj¹cych rekordów
        $resultIds = $redis->search($query);
        
        // Jeœli nie znaleziono wyników, zwróæ pust¹ tablicê
        if (empty($resultIds)) {
            echo json_encode(['results' => []]);
            exit;
        }
        
        // Pobierz pe³ne dane dla znalezionych ID
        $results = $redis->getRecordsByIds($resultIds);
        
        // Zwróæ wyniki w formacie JSON
        echo json_encode(['results' => $results]);
    } catch (Exception $e) {
        // W przypadku b³êdu, zwróæ komunikat o b³êdzie
        http_response_code(500);
        echo json_encode(['error' => 'Wyst¹pi³ b³¹d podczas wyszukiwania: ' . $e->getMessage()]);
    }
} else {
    // Jeœli brak parametru 'q', zwróæ b³¹d 400 Bad Request
    http_response_code(400);
    echo json_encode(['error' => 'Brak parametru wyszukiwania']);
}