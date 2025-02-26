<?php
// Chargement des dépendances
require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des variables d'environnement
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');

// Import de la classe GoogleSheetService
use App\GoogleSheetService;

// Initialisation des variables
$message = '';
$drawnPlayer = null;
$headers = [];

// Instance du service Google Sheet
$sheetService = new GoogleSheetService();

// Traitement de la demande de tirage
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['draw'])) {
    // Effectuer le tirage
    $drawnPlayer = $sheetService->drawRandomPlayer();
    
    if ($drawnPlayer === null) {
        $message = '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">Aucun joueur disponible pour le tirage.</div>';
    } else {
        // Récupérer les en-têtes pour afficher les noms des champs
        $allData = $sheetService->getAllData();
        $headers = $allData[0] ?? [];
        
        $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Tirage effectué avec succès !</div>';
    }
}

// Nombre de participants non tirés
$allData = $sheetService->getAllData();
$headers = $allData[0] ?? [];
$drawDateIndex = array_search('Tirage', $headers, true);

$availablePlayersCount = 0;
if ($drawDateIndex !== false && count($allData) > 1) {
    for ($i = 1; $i < count($allData); $i++) {
        $drawDate = $allData[$i][$drawDateIndex] ?? "";
        if (empty($drawDate)) {
            $availablePlayersCount++;
        }
    }
}
?>

<?php include 'header.php' ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-4 border-b">
                <h1 class="text-xl font-bold">Tirage au sort</h1>
            </div>
            <div class="p-6">
                <?php echo $message; ?>
                
                <div class="mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-blue-800">
                            <span class="font-bold"><?php echo $availablePlayersCount; ?></span> participant(s) disponible(s) pour le tirage.
                        </p>
                    </div>
                </div>
                
                <form method="post" action="">
                    <button type="submit" name="draw" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 flex justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                        Effectuer un tirage au sort
                    </button>
                </form>
            </div>
        </div>
        
        <?php if ($drawnPlayer !== null): ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-bold">Résultat du tirage</h2>
            </div>
            <div class="p-6">
                <div class="bg-green-50 p-6 rounded-lg border border-green-200 mb-6">
                    <h3 class="text-xl font-bold text-green-800 mb-4">Félicitations au gagnant !</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($drawnPlayer as $index => $value): ?>
                            <?php if (isset($headers[$index])): ?>
                                <div>
                                    <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($headers[$index]); ?> :</span>
                                    <span class="text-gray-900"><?php echo htmlspecialchars($value); ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php' ?>