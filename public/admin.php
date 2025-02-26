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

// Instance du service Google Sheet
$sheetService = new GoogleSheetService();

// Traitement de la demande de tirage
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['draw'])) {
    // Effectuer le tirage
    $drawnPlayer = $sheetService->drawRandomPlayer();
    
    if ($drawnPlayer === null) {
        $message = '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">Aucun joueur disponible pour le tirage.</div>';
    }
}

$usersData = $sheetService->getAllData();
$headers = $usersData[0];
array_shift($usersData);
?>

<?php include 'header.php' ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold">Administration</h1>
        </div>
        <div class="flex space-x-3">
            <a href="https://docs.google.com/spreadsheets/d/<?= $_ENV['GOOGLE_SHEET_ID'] ?>" target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                Google sheet
            </a>
            <a href="/qrcode"
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                QR Code
            </a>
            <form method="post" action="">
                <button type="submit" name="draw"
                        onclick="return confirm('Effectuer un tirage au sort ?');"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                    Tirer au sort un joueur
                </button>
            </form>
        </div>
    </div>
    
    <?php echo $message; ?>
    
    <?php if ($drawnPlayer !== null): ?>
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-bold">Résultat du tirage</h2>
        </div>
        <div class="p-6">
            <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                <h3 class="text-xl font-bold text-green-800 mb-4">Joueur tiré au sort :</h3>
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
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h1 class="text-xl font-bold">Liste des utilisateurs inscrits</h1>
            <a href="/admin" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-1 px-3 rounded focus:outline-none focus:shadow-outline transition duration-150 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Actualiser
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <?= htmlspecialchars($header); ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($usersData)): ?>
                    <tr>
                        <td colspan="<?= count($headers); ?>" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucun utilisateur inscrit pour le moment.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usersData as $user): ?>
                        <tr>
                            <?php foreach ($user as $index => $value): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($value); ?>
                                </td>
                            <?php endforeach; ?>

                            <?php
                            // Ajouter des cellules vides si le nombre de colonnes est inférieur au nombre d'en-têtes
                            for ($i = count($user); $i < count($headers); $i++): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>

