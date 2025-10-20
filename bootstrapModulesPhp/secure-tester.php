<?php

    /**
     * 🛡️ Fonction universelle de sécurisation parano-friendly
     * 
     * Gère tous les cas de nettoyage, encodage, validation et typage.
     * Auto-détecte le contexte :
     * - Si on est en mode affichage (HTML) → encode
     * - Si on est en traitement serveur → nettoie
     * 
     * Modes disponibles :
     * - 'auto'    → détection automatique (par défaut)
     * - 'input'   → nettoyage XSS + trim
     * - 'output'  → encodage HTML
     * - 'email'   → validation email
     * - 'int'     → conversion en entier
     * - 'float'   → conversion en float
     * - 'bool'    → conversion en booléen
     * - 'raw'     → brut sans traitement
     */
    function secure($value, $mode = 'auto') {
        // 🧩 Traitement récursif si tableau
        if (is_array($value)) {
            return array_map(fn($v) => secure($v, $mode), $value);
        }

        // 🧙‍♂️ Auto-détection du contexte
        if ($mode === 'auto') {
            $mode = (php_sapi_name() === 'cli' || defined('STDIN')) ? 'raw' : 'output';
        }

        switch ($mode) {
            case 'input':
                return trim(strip_tags($value));
            case 'output':
                return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) ?: null;
            case 'int':
                return filter_var($value, FILTER_VALIDATE_INT) ?: 0;
            case 'float':
                return filter_var($value, FILTER_VALIDATE_FLOAT) ?: 0.0;
            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
            case 'raw':
                return $value;
            default:
                return $value;
        }
    }

?>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $value = $_POST['value'] ?? '';
        $results = [
            'input'  => secure($value, 'input'),
            'output' => secure($value, 'output'),
            'email'  => secure($value, 'email'),
            'int'    => secure($value, 'int'),
            'float'  => secure($value, 'float'),
            'bool'   => secure($value, 'bool'),
            'raw'    => secure($value, 'raw'),
        ];
    }

?>

<!Doctype HTML>
<HTML lang="fr">
    <head>
        <meta charset="UTF-8">
        <Title>Testeur de secure()</Title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </Head>
    <Body class="bg-light">

        <div class="container mt-5">
            <h2 class="mb-4">⚙️ Testeur parano de <code>secure()</code></h2>

            <div class="card p-4 mb-4 bg-white shadow-sm">
            <h4 class="mb-3">📘 Guide d'utilisation de <code>secure()</code></h4>
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Mode</th>
                        <th>Description</th>
                        <th>Exemple d'utilisation</th>
                        <th>Résultat attendu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>input</code></td>
                        <td>🧼 Nettoie les entrées (XSS, balises, espaces)</td>
                        <td><code>secure($_POST['name'], 'input')</code></td>
                        <td><code>Jean &lt;script&gt;</code> → <code>Jean</code></td>
                    </tr>
                    <tr>
                        <td><code>output</code></td>
                        <td>🔐 Encode pour affichage HTML</td>
                        <td><code>echo secure($name, 'output')</code></td>
                        <td><code>&lt;b&gt;Jean&lt;/b&gt;</code> → affiché comme texte</td>
                    </tr>
                    <tr>
                        <td><code>email</code></td>
                        <td>📧 Valide un email</td>
                        <td><code>secure($_POST['email'], 'email')</code></td>
                        <td><code>test@example.com</code> → OK, sinon <code>null</code></td>
                    </tr>
                    <tr>
                        <td><code>int</code></td>
                        <td>🔢 Convertit en entier</td>
                        <td><code>secure($_POST['age'], 'int')</code></td>
                        <td><code>"42"</code> → <code>42</code></td>
                    </tr>
                    <tr>
                        <td><code>float</code></td>
                        <td>📐 Convertit en nombre décimal</td>
                        <td><code>secure($_POST['price'], 'float')</code></td>
                        <td><code>"3.14"</code> → <code>3.14</code></td>
                    </tr>
                    <tr>
                        <td><code>bool</code></td>
                        <td>✅ Convertit en booléen</td>
                        <td><code>secure($_POST['accept'], 'bool')</code></td>
                        <td><code>"yes"</code> → <code>true</code></td>
                    </tr>
                    <tr>
                        <td><code>raw</code></td>
                        <td>🧪 Retourne la valeur brute</td>
                        <td><code>secure($debug, 'raw')</code></td>
                        <td>Aucune transformation</td>
                    </tr>
                    <tr>
                        <td><code>auto</code></td>
                        <td>🧙‍♂️ Détecte le contexte (CLI ou HTML)</td>
                        <td><code>secure($value)</code></td>
                        <td>Affiche ou nettoie selon l’environnement</td>
                    </tr>
                </tbody>
            </table>
        </div>


            <form method="POST" action="" class="card p-4 shadow-sm bg-white">
                <div class="mb-3">
                    <label for="value" class="form-label">Valeur à sécuriser</label>
                    <input type="text" name="value" id="value" class="form-control" required value="<?= htmlspecialchars($_POST['value'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary">Tester tous les modes</button>
            </form>

            <?php if (isset($results)): ?>
                <div class="mt-5">
                    <h4>🔍 Résultats par mode :</h4>
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>Mode</th>
                                <th>Résultat</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $mode => $res): ?>
                                <tr>
                                    <td><code><?= $mode ?></code></td>
                                    <td><pre class="m-0"><?= var_export($res, true) ?></pre></td>
                                    <td><?= gettype($res) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </Body>
</HTML>
