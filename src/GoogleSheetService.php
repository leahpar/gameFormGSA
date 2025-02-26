<?php
namespace App;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetService
{
    private Sheets $service;
    private string $spreadsheetId;
    private string $range = 'A:Z'; // Plage de colonnes suffisamment large

    public function __construct()
    {
        // Initialisation du client Google API
        $client = new Client();
        $client->setApplicationName('Système d\'Inscription');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(__DIR__. '/../'.$_ENV['GOOGLE_CREDENTIALS_PATH']);
        $client->setAccessType('offline');

        // Création du service Sheets
        $this->service = new Sheets($client);
        $this->spreadsheetId = $_ENV['GOOGLE_SHEET_ID'];
    }

    /**
     * Vérifie si un email ou un numéro de téléphone existe déjà
     */
    public function isDuplicate(string $email, string $telephone): bool
    {
        // Récupération des données actuelles
        $response = $this->service->spreadsheets_values->get(
            $this->spreadsheetId,
            $this->range
        );

        $values = $response->getValues();

        // Chercher les correspondances d'email et de téléphone
        // On suppose que l'email est à l'index 2 et le téléphone à l'index 3
        if (in_array($email, array_column($values, 2)) || in_array($telephone, array_column($values, 3))) {
            return true;
        }

        return false;
    }

    /**
     * Enregistre les données utilisateur dans Google Sheets
     */
    public function saveData(array $userData): void
    {
        $valueRange = new ValueRange();
        $valueRange->setValues([
            array_values($userData)
        ]);

        $this->service->spreadsheets_values->append(
            $this->spreadsheetId,
            'A:I', // Plage correspondant aux données (nom, prénom, email, téléphone, code_postal, travaux, annee, date, tirage)
            $valueRange,
            ['valueInputOption' => 'RAW']
        );
    }

    /**
     * Récupère toutes les données pour l'affichage admin
     */
    public function getAllData(): array
    {
        $response = $this->service->spreadsheets_values->get(
            $this->spreadsheetId,
            $this->range
        );

        return $response->getValues() ?? [];
    }

    /**
     * Tire un joueur au hasard parmi ceux qui n'ont pas encore été tirés
     * @return array|null Les données du joueur tiré ou null si aucun joueur disponible
     */
    public function drawRandomPlayer(): ?array
    {
        // Récupérer toutes les données
        $allData = $this->getAllData();
        
        // Stocker l'en-tête séparément
        //$headers = $allData[0];
        
        // Récupérer les index des colonnes importantes
        $drawDateIndex = 8; //array_search('Tirage', $headers, true);
        
        // Filtrer pour obtenir uniquement les joueurs non tirés (après l'en-tête)
        $availablePlayers = [];
        $rowIndexes = []; // Pour stocker les index des lignes correspondantes
        
        for ($i = 1; $i < count($allData); $i++) {
            $player = $allData[$i];
            
            // Vérifier si le joueur a déjà été tiré (colonne Tirage vide)
            $drawDate = $player[$drawDateIndex] ?? "";
            
            if (empty($drawDate)) {
                $availablePlayers[] = $player;
                $rowIndexes[] = $i + 1; // +1 car les index de Google Sheets commencent à 1, et on a déjà l'en-tête
            }
        }
        
        // Si aucun joueur disponible, retourner null
        if (empty($availablePlayers)) {
            return null;
        }
        
        // Tirer un joueur au hasard
        $randomIndex = array_rand($availablePlayers);
        $drawnPlayer = $availablePlayers[$randomIndex];
        $rowIndex = $rowIndexes[$randomIndex];
        
        // Mettre à jour la date de tirage dans Google Sheets
        $dateNow = date('d/m/Y H:i:s');
        
        // Préparer la mise à jour
        $range = 'I' . $rowIndex; // Colonne I (Tirage) pour la ligne correspondante
        $valueRange = new ValueRange();
        $valueRange->setValues([[$dateNow]]);
        
        // Effectuer la mise à jour
        $this->service->spreadsheets_values->update(
            $this->spreadsheetId,
            $range,
            $valueRange,
            ['valueInputOption' => 'RAW']
        );
        
        // Ajouter la date de tirage aux données du joueur retourné
        $drawnPlayer[$drawDateIndex] = $dateNow;
        
        return $drawnPlayer;
    }

}
