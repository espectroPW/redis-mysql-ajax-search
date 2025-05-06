<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/redis_service.php';

header('Content-Type: application/json');

// Sprawd� czy zapytanie jest typu GET i zawiera parametr 'q'
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $query = trim($_GET['q']);
    
    // Je�li zapytanie jest puste, zwr�� pust� tablic�
    if (empty($query)) {
        echo json_encode(['results' => []]);
        exit;
    }
    
    try {
        $redis = new RedisService();
        
        // Wyszukaj ID pasuj�cych rekord�w
        $resultIds = $redis->search($query);
        
        // Je�li nie znaleziono wynik�w, zwr�� pust� tablic�
        if (empty($resultIds)) {
            echo json_encode(['results' => []]);
            exit;
        }
        
        // Pobierz pe�ne dane dla znalezionych ID
        $results = $redis->getRecordsByIds($resultIds);
        
        // Zwr�� wyniki w formacie JSON
        echo json_encode(['results' => $results]);
    } catch (Exception $e) {
        // W przypadku b��du, zwr�� komunikat o b��dzie
        http_response_code(500);
        echo json_encode(['error' => 'Wyst�pi� b��d podczas wyszukiwania: ' . $e->getMessage()]);
    }
} else {
    // Je�li brak parametru 'q', zwr�� b��d 400 Bad Request
    http_response_code(400);
    echo json_encode(['error' => 'Brak parametru wyszukiwania']);
}