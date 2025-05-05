<?php
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'match' => true,
    'test' => 'Funcionando'
]);
exit;