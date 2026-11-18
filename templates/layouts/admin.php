<?php
/** @var string $content */
/** @var string $pageTitle */
/** @var string $appName */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Source+Serif+4:opsz,wght@8..60,500;8..60,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin-body">
<header class="admin-header">
    <div class="container header-inner">
        <a class="brand" href="/admin"><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?> · Заявки</a>
        <nav class="nav">
            <a href="/">На сайт</a>
            <?php if (!empty($isAdmin) && str_contains($pageTitle, 'Заявки')): ?>
                <form method="post" action="/admin/logout" class="inline-form">
                    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="btn btn--ghost btn--small">Выйти</button>
                </form>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="admin-main">
    <div class="container">
        <?= $content ?>
    </div>
</main>
</body>
</html>
