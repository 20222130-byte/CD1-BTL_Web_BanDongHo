<?php
try {
    $p = new PDO('mysql:host=127.0.0.1;dbname=shop_dongho_db', 'root', '');
    $q = $p->query('SHOW TABLES');
    while ($r = $q->fetch()) {
        echo $r[0] . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
