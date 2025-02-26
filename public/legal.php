<?php
// Chargement des dépendances
require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des variables d'environnement
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');
?>

<?php include 'header.php' ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h1 class="text-2xl font-bold">Informations légales</h1>
                <a href="index.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Retour au jeu
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-10">
                    <!-- Section CGU -->
                    <section id="cgu">
                        <h2 class="text-xl font-bold mb-4">Conditions Générales d'Utilisation</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-500 italic mb-4">Le contenu des CGU sera rédigé par un juriste.</p>
                            <!-- Contenu à compléter par un juriste -->
                        </div>
                    </section>
                    
                    <hr>
                    
                    <!-- Section Mentions Légales -->
                    <section id="mentions-legales">
                        <h2 class="text-xl font-bold mb-4">Mentions Légales</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-500 italic mb-4">Le contenu des mentions légales sera rédigé par un juriste.</p>
                            <!-- Contenu à compléter par un juriste -->
                        </div>
                    </section>
                    
                    <hr>
                    
                    <!-- Section RGPD -->
                    <section id="rgpd">
                        <h2 class="text-xl font-bold mb-4">Politique de Protection des Données (RGPD)</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-500 italic mb-4">Le contenu de la politique RGPD sera rédigé par un juriste.</p>
                            <!-- Contenu à compléter par un juriste -->
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>