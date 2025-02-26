<?php
// Chargement des dépendances
require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des variables d'environnement
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');

// Import de la classe GoogleSheetService
use App\GoogleSheetService;

// Récupération des données utilisateurs
$sheetService = new GoogleSheetService();
$usersData = $sheetService->getAllData();

// Extraction des en-têtes (première ligne)
$headers = $usersData[0];

// Suppression des en-têtes du tableau de données
array_shift($usersData);
?>

<?php include 'header.php' ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h1 class="text-xl font-bold">Liste des utilisateurs inscrits</h1>
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

