<?php include 'header.php' ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-center mb-6">Formulaire d'inscription</h1>

        <?php if (isset($errorMessage)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="index.php" method="post" class="space-y-4">
            <!-- CSRF Protection -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()); ?>">

            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" id="nom" name="nom" required
                       value="<?= htmlspecialchars($data['nom']??'') ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" id="prenom" name="prenom" required
                       value="<?= htmlspecialchars($data['prenom']??'') ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required
                       value="<?= htmlspecialchars($data['email']??'') ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
            </div>

            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" required
                       value="<?= htmlspecialchars($data['telephone']??'') ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
            </div>
            
            <div>
                <label for="code_postal" class="block text-sm font-medium text-gray-700">Code postal</label>
                <input type="text" id="code_postal" name="code_postal" required pattern="[0-9]{5}"
                       value="<?= htmlspecialchars($data['code_postal']??'') ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
            </div>
            
            <div>
                <label for="travaux" class="block text-sm font-medium text-gray-700">Avez-vous prévu des travaux dans votre habitation ?</label>
                <select id="travaux" name="travaux" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                    <option value="" <?= empty($data['travaux']) ? 'selected' : '' ?>>Sélectionnez</option>
                    <option value="oui" <?= isset($data['travaux']) && $data['travaux'] === 'oui' ? 'selected' : '' ?>>Oui</option>
                    <option value="non" <?= isset($data['travaux']) && $data['travaux'] === 'non' ? 'selected' : '' ?>>Non</option>
                </select>
            </div>
            
            <div>
                <label for="annee" class="block text-sm font-medium text-gray-700">Si oui, en quelle année ?</label>
                <select id="annee" name="annee"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500">
                    <option value="">Sélectionnez</option>
                    <option value="2025" <?= ($data['annee']??0) == 2025 ? 'selected' : '' ?>>2025</option>
                    <option value="2026" <?= ($data['annee']??0) == 2026 ? 'selected' : '' ?>>2026</option>
                    <option value="2027" <?= ($data['annee']??0) == 2027 ? 'selected' : '' ?>>2027 ou plus</option>
                </select>
            </div>
            
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="accept_terms" name="accept_terms" type="checkbox" required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="accept_terms" class="font-medium text-gray-700">
                        J'accepte le <a href="reglement" class="text-blue-600 hover:underline">règlement du jeu</a>.
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    S'inscrire
                </button>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php' ?>

