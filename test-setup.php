<?php
/**
 * Script de test pour setup de la base de données
 */

// Configuration de la base de données (ajuster selon votre config)
$dsn = 'mysql:host=localhost;charset=utf8mb4';
$user = 'root';
$password = ''; // À adapter

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Lire et exécuter le fichier SQL
    $sql_file = __DIR__ . '/databases/requete.sql';
    if (!file_exists($sql_file)) {
        die("Fichier SQL introuvable: $sql_file\n");
    }
    
    $sql_content = file_get_contents($sql_file);
    
    // Exécuter chaque déclaration SQL
    $statements = array_filter(array_map('trim', explode(';', $sql_content)));
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "✓ Exécuté: " . substr($statement, 0, 50) . "...\n";
            } catch (Exception $e) {
                echo "✗ Erreur: " . $e->getMessage() . "\n";
                echo "  Statement: " . substr($statement, 0, 100) . "\n";
            }
        }
    }
    
    echo "\n=== TEST DE DONNÉES ===\n";
    
    // Test: Récupérer les livraisons
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM l_livraisons");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Nombre de livraisons: " . $result['count'] . "\n";
    
    // Test: Récupérer les bénéfices
    $stmt = $pdo->query("SELECT * FROM l_benefices_jour");
    $benefices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Bénéfices par jour:\n";
    foreach ($benefices as $b) {
        echo "  " . $b['jour'] . ": " . $b['ca_total'] . " Ar (bénéfice: " . $b['benefice_total'] . " Ar)\n";
    }
    
    echo "\n✓ Setup réussi!\n";
    
} catch (PDOException $e) {
    die("Erreur PDO: " . $e->getMessage() . "\n");
} catch (Exception $e) {
    die("Erreur: " . $e->getMessage() . "\n");
}
?>
