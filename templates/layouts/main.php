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
    <meta name="description" content="СнабПлюс — оптовые поставки и индивидуальные условия для юридических лиц.">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700&family=Unbounded:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="/"><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></a>
        <nav class="nav" aria-label="Основная навигация">
            <a href="#advantages">Преимущества</a>
            <a href="#pricing">Оптовые цены</a>
            <a class="nav-cta" href="#request">Оставить заявку</a>
        </nav>
    </div>
</header>
<?= $content ?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <p class="brand brand--footer"><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></p>
            <p class="muted">Оптовые поставки расходных материалов и оборудования для бизнеса.</p>
        </div>
        <div>
            <p class="footer-label">Контакты</p>
            <p><a href="tel:+74951234567">+7 (495) 123-45-67</a></p>
            <p><a href="mailto:b2b@snabplus.example">b2b@snabplus.example</a></p>
        </div>
        <div>
            <p class="footer-label">Менеджерам</p>
            <p><a href="/admin/login">Кабинет заявок</a></p>
        </div>
    </div>
</footer>
<script src="/assets/js/app.js" defer></script>
</body>
</html>
