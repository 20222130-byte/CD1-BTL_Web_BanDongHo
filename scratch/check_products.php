<?php
try {
    $p = new PDO('mysql:host=127.0.0.1;dbname=shop_dongho_db', 'root', '');
    $q = $p->query('DESCRIBE products');
    while ($r = $q->fetch()) {
        print_r($r);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
