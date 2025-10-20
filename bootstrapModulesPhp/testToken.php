<?php

    // 🔗 Inclusion du module CSRF

    /**
     * 🔐 CSRF Protection — Sécurisation des formulaires
     * 
     * Ce module protège les formulaires contre les attaques de type Cross-Site Request Forgery.
     * Il génère un token unique, le vérifie à la soumission, et le détruit après usage.
     * Un token consommé est un token à oublier immédiatement 💥
     */

    /**
     * 🚪 Démarre la session si elle n’est pas déjà active
     * - Nécessaire pour stocker les tokens CSRF
     */
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    /** ⏳ Durée de vie du token en secondes */
    if (!defined('CSRF_TOKEN_LIFETIME')){
        define('CSRF_TOKEN_LIFETIME', 600);
    }

    /**
     * 🧬 Génère un token CSRF unique et sécurisé pour un formulaire spécifique
     * - Stocke le token brut, son hash, et l'heure de création dans $_SESSION['csrf_tokens'][$formId]
     * - Retourne le token brut à insérer dans le formulaire identifié par $formId
     */
    function generateCsrfToken(string $formId) {
    $rawToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_tokens'][$formId] = [
            'raw' => $rawToken,
            'hash' => password_hash($rawToken, PASSWORD_ARGON2ID),
            'time' => time()
        ];
        return $rawToken;
    }


    /**
     * 🔁 Récupère le token CSRF actuel pour un formulaire spécifique
     * - Si aucun token ou expiré → en génère un nouveau
     * - Sinon, retourne le token brut stocké pour $formId
     */
    function getCsrfToken(string $formId) {
        if (!isset($_SESSION['csrf_tokens'][$formId]['raw'], $_SESSION['csrf_tokens'][$formId]['time']) ||
            (time() - $_SESSION['csrf_tokens'][$formId]['time']) > CSRF_TOKEN_LIFETIME) {
            return generateCsrfToken($formId);
        }
        return $_SESSION['csrf_tokens'][$formId]['raw'];
    }


    /**
     * ✅ Vérifie la validité du token CSRF pour un formulaire spécifique
     * - Retourne 'valid', 'expired' ou 'invalid'
     */
    function validateCsrfToken(string $formId, string $token) {
        if (!isset($_SESSION['csrf_tokens'][$formId]['hash'], $_SESSION['csrf_tokens'][$formId]['time'])) {
            return 'invalid';
        }
        $expired = (time() - $_SESSION['csrf_tokens'][$formId]['time']) > CSRF_TOKEN_LIFETIME;
        if ($expired) {
            return 'expired';
        }
        return password_verify($token, $_SESSION['csrf_tokens'][$formId]['hash']) ? 'valid' : 'invalid';
    }


    /**
     * 💥 Supprime le token CSRF d’un formulaire spécifique après utilisation
     * - Empêche toute réutilisation (replay)
     */
    function destroyCsrfToken(string $formId) {
        unset($_SESSION['csrf_tokens'][$formId]);
    }

    /**
     * 🛡️ Vérifie le token CSRF d’un formulaire spécifique et interrompt le traitement en cas d’échec
     * - Gère les cas de token expiré ⏳ ou falsifié 🚫
     * - Détruit le token après vérification
     * - Affiche un message clair et stoppe le script si le token est invalide
     */
    function checkCsrfOrDie(string $formId, string $token) {
        $status = validateCsrfToken($formId, $token);
        if ($status === 'expired') {
            destroyCsrfToken($formId);
            die("⏳ Le token CSRF pour « $formId » a expiré. Veuillez recharger la page et réessayer.");
        }
        if ($status === 'invalid') {
            destroyCsrfToken($formId);
            die("🚫 Le token CSRF pour « $formId » est invalide ou falsifié. Requête bloquée.");
        }
        // ✅ Token valide → on continue
    }

    /**
     * 📛 Enregistre une tentative CSRF échouée dans le fichier de logs
     * - Utilise logAction() avec l’action "csrf_failure"
     * - Inclut l’adresse IP et éventuellement l’ID utilisateur
     */
    function logCsrfFailure(string $ipAddress, $userId = 'system'): bool {
        $details = "Tentative CSRF échouée depuis l’IP : $ipAddress";
        return logAction('csrf_failure', $details, $userId);
    }

?>
<?php

    // ⚙️ Traitement
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formId = $_POST['form_id'] ?? 'default_form';

        $status = validateCsrfToken($formId, $_POST['csrf_token'] ?? '');
        if ($status === 'expired') {
            destroyCsrfToken($formId);
            $message = '<div class="alert alert-warning">⏳ Le token CSRF a expiré. Rechargez la page.</div>';
        } elseif ($status === 'invalid') {
            logCsrfFailure($_SERVER['REMOTE_ADDR']);
            destroyCsrfToken($formId);
            $message = '<div class="alert alert-danger">🚫 Token CSRF falsifié. Requête bloquée.</div>';
        } else {
            destroyCsrfToken($formId);
            $message = '<div class="alert alert-success">✅ Formulaire traité avec succès !</div>';
        }
    }

?>
<!Doctype HTML>
<HTML lang="fr">
    <Head>
        <meta charset="UTF-8">
        <Title>Formulaire sécurisé</Title>
        <!-- 🎨 Bootstrap 5 CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </Head>
    <Body class="bg-light"> 
    <div class="container mt-5">
        <script>
            let startTime = Date.now();

            function updateCsrfTimer() {
                const now = Date.now();
                const elapsedSeconds = Math.floor((now - startTime) / 1000);
                document.getElementById('csrf-timer').textContent = elapsedSeconds + 's';
            }

            // Met à jour toutes les secondes
            setInterval(updateCsrfTimer, 1000);
        </script>
        <div class="d-flex justify-content-center my-4">
            <div class="alert alert-info text-center">
                ⏱️ Temps écoulé depuis le chargement : <strong id="csrf-timer">0s</strong>
            </div>
        </div>
        <!-- 🧼 Zone d’alerte bien intégrée -->
        <?php if (!empty($message)){ echo $message; } ?>
        <div class="container-sm mb-5 p-4 bg-light border rounded-3 shadow-sm">
            <h2 class="mb-4">🔐 Formulaire avec protection CSRF 1</h2>
            <form method="POST" action="" class="card p-4 shadow-sm bg-white">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <!-- 🔐 Champ caché contenant le token CSRF -->
                <input type="hidden" name="form_id" value="form_profile">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken('form_profile')) ?>">
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
        <div class="container-sm mb-5 p-4 bg-light border rounded-3 shadow-sm">
            <h2 class="mb-4">🔐 Formulaire avec protection CSRF 2</h2>
            <form method="POST" action="" class="card p-4 shadow-sm bg-white">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <!-- 🔐 Champ caché contenant le token CSRF -->
                <input type="hidden" name="form_id" value="form_profile2">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken('form_profile2')) ?>">
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>
    <!-- 🧩 Bootstrap JS (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </Body>
</HTML>
