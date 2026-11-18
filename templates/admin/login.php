<?php
/** @var string $csrfToken */
/** @var string|null $error */
?>
<section class="login-panel">
    <h1>Вход в кабинет</h1>
    <p class="section-lead">Просмотр и обработка заявок с сайта для юридических лиц.</p>

    <?php if ($error): ?>
        <div class="alert alert--error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="/admin/login" class="request-form">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <div class="field">
            <label for="login">Логин</label>
            <input id="login" name="login" type="text" required autocomplete="username">
        </div>
        <div class="field">
            <label for="password">Пароль</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
        </div>
        <button class="btn btn--primary btn--block" type="submit">Войти</button>
    </form>
</section>
