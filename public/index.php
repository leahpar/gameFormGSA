<?php
// Chargement des dépendances
require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des variables d'environnement
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');

// Import des classes nécessaires
use App\GoogleSheetService;
use App\FormValidator;

// Fonction pour générer un token CSRF
function generateCsrfToken() {
    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Gestion de la soumission du formulaire
$errorMessage = null;
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF
    session_start();
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errorMessage = "Erreur de validation du formulaire. Veuillez réessayer.";
    } else {
        // Nettoyage et validation des données
        $validator = new FormValidator();

        $data = [
            'nom'       => $validator->sanitizeInput($_POST['nom']       ?? ''),
            'prenom'    => $validator->sanitizeInput($_POST['prenom']    ?? ''),
            'email'     => $validator->sanitizeEmail($_POST['email']     ?? ''),
            'telephone' => $validator->sanitizeTel  ($_POST['telephone'] ?? ''),
            'date'      => date('Y-m-d H:i:s') // Date d'inscription
        ];

        // Validation des données
        if ($validator->validateData($data)) {
            $errorMessage = "Tous les champs sont obligatoires et doivent être valides.";
        } else {
            try {
                // Connexion au service Google Sheets
                $sheetService = new GoogleSheetService();

                // Vérification des doublons
                if ($sheetService->isDuplicate($data['email'], $data['telephone'])) {
                    $errorMessage = "Cet email ou numéro de téléphone sont déjà utilisés.";
                } else {

                    // Enregistrement dans Google Sheets
                    $sheetService->saveData($data);

                    // Redirection vers la page de confirmation
                    header('Location: validation.php');
                    exit;
                }
            } catch (Exception $e) {
                $errorMessage = "Une erreur est survenue lors de l'enregistrement. Veuillez réessayer ultérieurement.";
                // En environnement de développement, on pourrait afficher plus de détails:
                 $errorMessage .= " Détails: " . $e->getMessage();
            }
        }
    }

    // Génération d'un nouveau token CSRF après soumission
    generateCsrfToken();
}

// Affichage du formulaire
require_once __DIR__ . '/formulaire.php';
