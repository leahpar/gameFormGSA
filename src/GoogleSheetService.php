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
            'A:E', // Plage correspondant aux données (nom, prénom, email, téléphone, date)
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
}
