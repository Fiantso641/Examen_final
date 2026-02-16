<?php

namespace app\controllers;

use Flight;
use PDO;
use Exception;

class LivraisonController
{
    protected $db;

    public function __construct()
    {
        // Récupération de la connexion PDO via Flight (doit être configuré avant)
        $this->db = Flight::db();
    }

    /**
     * Affiche la liste des livraisons avec infos liées (colis, chauffeur, véhicule, zone)
     */
    public function index()
    {
        $sql = "
            SELECT l.id, l.adresse_destination, l.cout_vehicule, l.salaire_chauffeur,
                   l.chiffre_affaire_total AS chiffre_affaire,
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
            ORDER BY l.date_livraison DESC
        ";

        try {
            $stmt = $this->db->query($sql);
            $livraisons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            Flight::halt(500, "Erreur base de données : " . $e->getMessage());
            return;
        }

        Flight::render('livraisons', ['livraisons' => $livraisons]);
    }

    /**
     * Traite l'ajout d'une nouvelle livraison
     */
    public function store()
    {
        $data = Flight::request()->data;

        // On récupère et nettoie les données attendues
        $colis_id = isset($data->colis_id) ? intval($data->colis_id) : null;
        $chauffeur_id = isset($data->chauffeur_id) ? intval($data->chauffeur_id) : null;
        $vehicule_id = isset($data->vehicule_id) ? intval($data->vehicule_id) : null;
        $zone_id = isset($data->zone_id) ? intval($data->zone_id) : null;
        $adresse_destination = trim($data->adresse_destination ?? '');
        $cout_vehicule = isset($data->cout_vehicule) ? floatval($data->cout_vehicule) : null;
        $salaire_chauffeur = isset($data->salaire_chauffeur) ? floatval($data->salaire_chauffeur) : null;
        $date_livraison = $data->date_livraison ?? null;

        // Validation simple
        if (!$colis_id || !$chauffeur_id || !$vehicule_id || empty($adresse_destination) || $cout_vehicule === null || $salaire_chauffeur === null || empty($date_livraison)) {
            Flight::halt(400, 'Tous les champs obligatoires doivent être remplis');
            return;
        }

        // Récupérer poids_kg et prix_par_kg du colis pour calculer chiffre_affaire_base
        $stmtColis = $this->db->prepare("SELECT poids_kg, prix_par_kg FROM l_colis WHERE id = ?");
        $stmtColis->execute([$colis_id]);
        $colis = $stmtColis->fetch(PDO::FETCH_ASSOC);

        if (!$colis) {
            Flight::halt(400, 'Colis non trouvé');
            return;
        }

        $chiffre_affaire_base = $colis['poids_kg'] * $colis['prix_par_kg'];

        // Récupérer supplément zone si zone_id fourni
        $supplement_zone = 0;
        if ($zone_id) {
            $stmtZone = $this->db->prepare("SELECT supplement_pourcentage FROM zones_livraison WHERE id = ?");
            $stmtZone->execute([$zone_id]);
            $zone = $stmtZone->fetch(PDO::FETCH_ASSOC);
            if ($zone) {
                $supplement_zone = ($chiffre_affaire_base * $zone['supplement_pourcentage']) / 100;
            }
        }

        // Insertion
        $sql = "INSERT INTO l_livraisons
                (colis_id, chauffeur_id, vehicule_id, zone_id, adresse_destination, cout_vehicule, salaire_chauffeur, chiffre_affaire_base, supplement_zone, statut, date_livraison)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'en_attente', ?)";

        $stmt = $this->db->prepare($sql);

        try {
            $result = $stmt->execute([
                $colis_id,
                $chauffeur_id,
                $vehicule_id,
                $zone_id ?: null,
                $adresse_destination,
                $cout_vehicule,
                $salaire_chauffeur,
                $chiffre_affaire_base,
                $supplement_zone,
                $date_livraison
            ]);
            
            if ($result) {
                Flight::redirect('/livraisons?success=1');
            } else {
                Flight::halt(500, "Erreur lors de l'insertion : executefalse");
            }
        } catch (Exception $e) {
            Flight::halt(500, "Erreur lors de l'ajout : " . $e->getMessage());
        }
    }

    /**
     * Affiche la page des bénéfices selon le type (jour, mois, année)
     */
    public function beneficesPage()
    {
        $type = Flight::request()->query->type ?? 'jour';
        $label = ucfirst($type);

        switch ($type) {
            case 'mois':
                $view = 'l_benefices_mois';
                $periodField = 'mois';
                break;
            case 'annee':
                $view = 'l_benefices_annee';
                $periodField = 'annee';
                break;
            case 'jour':
            default:
                $view = 'l_benefices_jour';
                $periodField = 'jour';
                break;
        }

        $sql = "SELECT $periodField AS periode,
                       nb_livraisons,
                       ca_total,
                       cout_total,
                       benefice_total
                FROM $view";        try {
            $stmt = $this->db->query($sql);
            $benefices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            Flight::halt(500, "Erreur lors de la récupération des bénéfices : " . $e->getMessage());
            return;
        }

        Flight::render('benefices', [
            'benefices' => $benefices,
            'type' => $type,
            'label' => $label
        ]);
    }

    /**
     * Affiche la liste des zones de livraison
     */
    public function zones()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM zones_livraison ORDER BY nom_zone");
            $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            Flight::halt(500, "Erreur lors de la récupération des zones : " . $e->getMessage());
            return;
        }

        Flight::render('zones', ['zones' => $zones]);
    }

    /**
     * Ajoute une nouvelle zone de livraison
     */
    public function addZone()
    {
        $nom_zone = trim(Flight::request()->data->nom_zone ?? '');
        $supplement_pourcentage = floatval(Flight::request()->data->supplement_pourcentage ?? 0);

        if (empty($nom_zone)) {
            Flight::halt(400, "Le nom de la zone est requis");
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO zones_livraison (nom_zone, supplement_pourcentage) VALUES (?, ?)");

        try {
            $stmt->execute([$nom_zone, $supplement_pourcentage]);
            Flight::redirect('/zones?success=1');
        } catch (Exception $e) {
            Flight::halt(500, "Erreur lors de l'ajout de la zone : " . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de modification d'une zone
     */
    public function editZone($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM zones_livraison WHERE id = ?");
        $stmt->execute([$id]);
        $zone = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$zone) {
            Flight::halt(404, "Zone introuvable");
            return;
        }

        Flight::render('edit_zone', ['zone' => $zone]);
    }

    /**
     * Met à jour une zone de livraison
     */
    public function updateZone($id)
    {
        $nom_zone = trim(Flight::request()->data->nom_zone ?? '');
        $supplement_pourcentage = floatval(Flight::request()->data->supplement_pourcentage ?? 0);

        if (empty($nom_zone)) {
            Flight::halt(400, "Le nom de la zone est requis");
            return;
        }

        $stmt = $this->db->prepare("UPDATE zones_livraison SET nom_zone = ?, supplement_pourcentage = ? WHERE id = ?");

        try {
            $stmt->execute([$nom_zone, $supplement_pourcentage, $id]);
            Flight::redirect('/zones?success=1');
        } catch (Exception $e) {
            Flight::halt(500, "Erreur lors de la mise à jour de la zone : " . $e->getMessage());
        }
    }

    /**
     * Supprime une zone de livraison
     */
    public function deleteZone($id)
    {
        $stmt = $this->db->prepare("DELETE FROM zones_livraison WHERE id = ?");

        try {
            $stmt->execute([$id]);
            Flight::redirect('/zones?success=1');
        } catch (Exception $e) {
            Flight::halt(500, "Erreur lors de la suppression de la zone : " . $e->getMessage());
        }
    }
}
