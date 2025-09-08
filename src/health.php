<?php
header('Content-Type: application/json');
http_response_code(200);

echo json_encode(array(
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'server' => file_get_contents('http://169.254.169.254/latest/meta-data/instance-id')
));
?>