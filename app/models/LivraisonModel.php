<?php
namespace app\models;

use Flight;
use PDO;
use Exception;

class LivraisonModel
{
    /**
     * Récupère toutes les livraisons avec infos jointes, ordonnées par id croissant
     * @return array
     * @throws Exception
     */
public static function getAll(): array
{
    $db = Flight::db();

    $sql = "
        SELECT l.id, l.adresse_destination, l.cout_vehicule, l.salaire_chauffeur,
               (l.chiffre_affaire_base + l.supplement_zone) AS chiffre_affaire,
               l.statut, l.date_livraison,
               c.reference AS colis_reference,
               CONCAT(ch.prenom, ' ', ch.nom) AS chauffeur,
               CONCAT(v.marque, ' ', v.modele, ' (', v.immatriculation, ')') AS vehicule,
               z.nom_zone
        FROM l_livraisons l
        JOIN l_colis c ON l.colis_id = c.id
        JOIN l_chauffeurs ch ON l.chauffeur_id = ch.id
        JOIN l_vehicules v ON l.vehicule_id = v.id
        LEFT JOIN zones_livraison z ON l.zone_id = z.id
        ORDER BY l.id ASC
    ";

    try {
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Erreur lors de la récupération des livraisons : " . $e->getMessage());
    }
}


    /**
     * Crée une nouvelle livraison avec calcul du supplément zone
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function create(array $data): bool
    {
        $db = Flight::db();

        // Validation basique
        if (empty($data['colis_id']) || empty($data['chauffeur_id']) || empty($data['vehicule_id']) ||
            empty($data['adresse_destination']) || !isset($data['cout_vehicule']) || !isset($data['salaire_chauffeur']) || empty($data['date_livraison'])) {
            throw new Exception("Champs obligatoires manquants");
        }

        // Récupérer poids_kg et prix_par_kg du colis
        $stmt = $db->prepare("SELECT poids_kg, prix_par_kg FROM l_colis WHERE id = ?");
        $stmt->execute([$data['colis_id']]);
        $colis = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$colis) {
            throw new Exception("Colis non trouvé");
        }

        // Récupérer supplément zone (si zone_id fourni)
        $supplement_pourcentage = 0;
        if (!empty($data['zone_id'])) {
            $stmt = $db->prepare("SELECT supplement_pourcentage FROM zones_livraison WHERE id = ?");
            $stmt->execute([$data['zone_id']]);
            $zone = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($zone) {
                $supplement_pourcentage = $zone['supplement_pourcentage'];
            }
        }

        // Calcul chiffre d'affaire base et supplément zone
        $ca_base = $colis['poids_kg'] * $colis['prix_par_kg'];
        $supplement_zone = $ca_base * ($supplement_pourcentage / 100);

        $sql = "INSERT INTO l_livraisons 
                (colis_id, chauffeur_id, vehicule_id, zone_id, adresse_destination,
                 cout_vehicule, salaire_chauffeur, chiffre_affaire_base, supplement_zone,
                 statut, date_livraison)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            $data['colis_id'],
            $data['chauffeur_id'],
            $data['vehicule_id'],
            $data['zone_id'] ?? null,
            $data['adresse_destination'],
            $data['cout_vehicule'],
            $data['salaire_chauffeur'],
            $ca_base,
            $supplement_zone,
            $data['statut'] ?? 'en_attente',
            $data['date_livraison']
        ]);
    }

    /**
     * Met à jour une livraison, recalcul du supplément si besoin
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function update(int $id, array $data): bool
    {
        $db = Flight::db();

        // Récupérer livraison existante pour chiffre_affaire_base
        $stmt = $db->prepare("SELECT chiffre_affaire_base FROM l_livraisons WHERE id = ?");
        $stmt->execute([$id]);
        $livraison = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$livraison) {
            throw new Exception("Livraison non trouvée");
        }

        // Si zone_id changé, recalculer supplement_zone
        if (isset($data['zone_id'])) {
            $supplement_pourcentage = 0;
            if (!empty($data['zone_id'])) {
                $stmt = $db->prepare("SELECT supplement_pourcentage FROM zones_livraison WHERE id = ?");
                $stmt->execute([$data['zone_id']]);
                $zone = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($zone) {
                    $supplement_pourcentage = $zone['supplement_pourcentage'];
                }
            }
            $data['supplement_zone'] = $livraison['chiffre_affaire_base'] * ($supplement_pourcentage / 100);
        }

        // Construire requête UPDATE dynamique
        $fields = [];
        $values = [];
        foreach ($data as $col => $val) {
            $fields[] = "$col = ?";
            $values[] = $val;
        }
        $values[] = $id;

        $sql = "UPDATE l_livraisons SET " . implode(", ", $fields) . " WHERE id = ?";

        $stmt = $db->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Supprime une livraison
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $db = Flight::db();

        $stmt = $db->prepare("DELETE FROM l_livraisons WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Récupère bénéfices selon le type (jour, mois, année)
     * @param string $type
     * @return array
     * @throws Exception
     */
    public static function getBenefices(string $type = 'jour'): array
    {
        $db = Flight::db();

        switch ($type) {
            case 'mois':
                $sql = "SELECT * FROM l_benefices_mois ORDER BY mois DESC";
                break;
            case 'annee':
                $sql = "SELECT * FROM l_benefices_annee ORDER BY annee DESC";
                break;
            case 'jour':
            default:
                $sql = "SELECT * FROM l_benefices_jour ORDER BY jour DESC";
                break;
        }

        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des bénéfices : " . $e->getMessage());
        }
    }

    /**
     * Liste des zones
     * @return array
     * @throws Exception
     */
    public static function getZones(): array
    {
        $db = Flight::db();

        try {
            $stmt = $db->query("SELECT * FROM zones_livraison ORDER BY nom_zone");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des zones : " . $e->getMessage());
        }
    }

    /**
     * Récupérer livraison par ID avec infos jointes
     * @param int $id
     * @return array|false
     */
    public static function getById(int $id)
    {
        $db = Flight::db();

        $sql = "
            SELECT l.*, 
                   c.poids_kg, c.prix_par_kg, c.reference AS colis_reference,
                   CONCAT(ch.prenom, ' ', ch.nom) AS chauffeur,
                   CONCAT(v.marque, ' ', v.modele, ' (', v.immatriculation, ')') AS vehicule,
                   z.nom_zone, z.supplement_pourcentage
            FROM l_livraisons l
            JOIN l_colis c ON c.id = l.colis_id
            JOIN l_chauffeurs ch ON ch.id = l.chauffeur_id
            JOIN l_vehicules v ON v.id = l.vehicule_id
            LEFT JOIN zones_livraison z ON z.id = l.zone_id
            WHERE l.id = ?
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
