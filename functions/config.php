<?php
function connexionDB() {
    $host = 'sql213.infinityfree.com';
    $dbname = 'if0_36736715_edunote';
    $username = 'if0_36736715';
    $password = 'EdunoteMMI7777';
    

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
?>