<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'db.php';
require_once 'auth.php';

// Start session to check authentication
session_start();

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized - Please login']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = $_SESSION['user_id'];
$is_admin = isAdmin();

// GET ALL CURRENCIES
if ($method === 'GET' && $action === 'get') {
    try {
        $stmt = $pdo->query("SELECT * FROM currencies ORDER BY id");
        $currencies = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $currencies]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// ADD NEW CURRENCY (Admin only)
if ($method === 'POST' && $action === 'add') {
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Admin access required']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $required = ['currency_code', 'buy_rate', 'sell_rate'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            echo json_encode(['success' => false, 'error' => "$field is required"]);
            exit;
        }
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO currencies (currency_code, currency_name, buy_rate, sell_rate, flag_class, symbol, color, label, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['currency_code'],
            $data['currency_name'] ?? $data['currency_code'],
            $data['buy_rate'],
            $data['sell_rate'],
            $data['flag_class'] ?? 'fi-us',
            $data['symbol'] ?? '$',
            $data['color'] ?? 'green',
            $data['label'] ?? 'spot',
            $user_id
        ]);
        
        logActivity($pdo, $user_id, 'add_currency', "Added currency " . $data['currency_code']);
        
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// UPDATE CURRENCY (Admin only)
if ($method === 'PUT' && $action === 'update') {
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Admin access required']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        echo json_encode(['success' => false, 'error' => 'ID is required']);
        exit;
    }
    
    try {
        $fields = [];
        $params = [];
        
        $allowed = ['currency_code', 'currency_name', 'buy_rate', 'sell_rate', 'flag_class', 'symbol', 'color', 'label'];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        $fields[] = "updated_by = ?";
        $params[] = $user_id;
        $params[] = $data['id'];
        
        $sql = "UPDATE currencies SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        logActivity($pdo, $user_id, 'update_currency', "Updated currency ID: " . $data['id']);
        
        echo json_encode(['success' => true, 'affected' => $stmt->rowCount()]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// DELETE CURRENCY (Admin only)
if ($method === 'DELETE' && $action === 'delete') {
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Admin access required']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        echo json_encode(['success' => false, 'error' => 'ID is required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM currencies WHERE id = ?");
        $stmt->execute([$data['id']]);
        
        logActivity($pdo, $user_id, 'delete_currency', "Deleted currency ID: " . $data['id']);
        
        echo json_encode(['success' => true, 'affected' => $stmt->rowCount()]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Default response
echo json_encode(['success' => false, 'error' => 'Invalid action']);
?>