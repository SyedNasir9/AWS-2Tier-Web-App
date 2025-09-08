<?php
header('Content-Type: application/json');

$response = array(
    'timestamp' => date('Y-m-d H:i:s'),
    'server_id' => file_get_contents('http://169.254.169.254/latest/meta-data/instance-id'),
    'availability_zone' => file_get_contents('http://169.254.169.254/latest/meta-data/placement/availability-zone'),
    'local_ip' => file_get_contents('http://169.254.169.254/latest/meta-data/local-ipv4'),
    'status' => 'healthy',
    'load' => sys_getloadavg()[0]
);

echo json_encode($response, JSON_PRETTY_PRINT);
?>