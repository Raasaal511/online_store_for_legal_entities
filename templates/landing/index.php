<?php
/** @var string $csrfToken */
/** @var string|null $flashSuccess */
/** @var array<string, string> $old */
/** @var array<string, string> $errors */
/** @var string $appName */

use App\Validation\PhoneRules;

$phoneSplit = PhoneRules::split((string) ($old['phone'] ?? ''));
$phoneCode = $old['phone_code'] ?? $phoneSplit['code'];
$phoneNumber = $old['phone_number'] ?? $phoneSplit['number'];
if ($phoneCode === '+7' && $phoneNumber !== '') {
    $digits = preg_replace('/\D+/', '', $phoneNumber) ?? '';
    if (strlen($digits) === 10) {
        $phoneNumber = sprintf(
            '(%s) %s-%s-%s',
            substr($digits, 0, 3),
            substr($digits, 3, 3),
            substr($digits, 6, 2),
            substr($digits, 8, 2)
        );
    }
}
$countries = PhoneRules::countries();
$fieldError = static function (array $errors, string $key): string {
    return isset($errors[$key]) ? (string) $errors[$key] : '';
};
?>
<main>
    <section class="hero">
        <div class="hero-backdrop" aria-hidden="true"></div>
        <div class="container hero-content">
            <p class="eyebrow"><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></p>
            <h1>Сотрудничество для юридических лиц</h1>
            <p class="lead">
                Организуем регулярные поставки для компаний: фиксируем оптовые условия,
                закрепляем персонального менеджера и выстраиваем закупки под ваш объём и график.
            </p>
            <div class="hero-actions">
                <a class="btn btn--primary" href="#request">Оставить заявку</a>
                <a class="btn btn--ghost" href="#pricing">Смотреть условия</a>
            </div>
        </div>
    </section>

    <section id="advantages" class="section">
        <div class="container">
            <h2>Преимущества работы с нами</h2>
            <p class="section-lead">Прозрачные процессы и предсказуемые сроки — без лишних согласований на каждом заказе.</p>
            <div class="features">
                <article class="feature">
                    <h3>Персональный менеджер</h3>
                    <p>Один контакт по ассортименту, срокам отгрузки и документам. Отвечаем в рабочие часы в течение дня.</p>
                </article>
                <article class="feature">
                    <h3>Склад и логистика</h3>
                    <p>Отгрузка со своего склада, комплектация смешанных партий и доставка по согласованному графику.</p>
                </article>
                <article class="feature">
                    <h3>Документы для юрлиц</h3>
                    <p>Счёт, УПД, договор и закрывающие документы в электронном виде. Работаем по безналичному расчёту.</p>
                </article>
                <article class="feature">
                    <h3>Стабильное качество</h3>
                    <p>Проверенные поставщики и контроль партий. Помогаем подобрать аналоги без потери характеристик.</p>
                </article>
            </div>
        </div>
    </section>

    <section id="pricing" class="section section--alt">
        <div class="container">
            <h2>Оптовые цены и индивидуальные скидки</h2>
            <p class="section-lead">
                Базовая шкала зависит от объёма закупки. Для постоянных контрагентов и крупных тендеров
                рассчитываем персональные условия после короткого брифа.
            </p>
            <div class="pricing-table-wrap" role="region" aria-label="Шкала оптовых скидок">
                <table class="pricing-table">
                    <thead>
                    <tr>
                        <th scope="col">Объём заказа</th>
                        <th scope="col">Условия</th>
                        <th scope="col">Скидка от розницы</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>от 50 000 ₽</td>
                        <td>Стартовый оптовый прайс</td>
                        <td>5%</td>
                    </tr>
                    <tr>
                        <td>от 150 000 ₽</td>
                        <td>Расширенный ассортимент и резерв на складе</td>
                        <td>10%</td>
                    </tr>
                    <tr>
                        <td>от 400 000 ₽</td>
                        <td>Индивидуальный договор и приоритетная отгрузка</td>
                        <td>до 18%</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <p class="pricing-note">
                Точный процент и отсрочка платежа зависят от категории товаров, частоты поставок и истории оплат.
                Оставьте заявку — подготовим коммерческое предложение под ваш ИНН.
            </p>
        </div>
    </section>

    <section id="request" class="section">
        <div class="container request-grid">
            <div>
                <h2>Оставить заявку</h2>
                <p class="section-lead">
                    Заполните форму — менеджер свяжется с вами, уточнит ассортимент и пришлёт условия сотрудничества.
                </p>
            </div>
            <div class="form-panel">
                <div id="form-alert"
                     class="alert <?= $flashSuccess ? 'alert--success' : (!empty($errors['_form']) ? 'alert--error' : 'alert--hidden') ?>"
                     role="status"
                     <?= ($flashSuccess || !empty($errors['_form'])) ? '' : 'hidden' ?>>
                    <?= htmlspecialchars($flashSuccess ?? ($errors['_form'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </div>

                <form class="request-form" method="post" action="/request" novalidate data-validate data-ajax>
                    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="hp" aria-hidden="true">
                        <label for="website">Сайт</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="field <?= isset($errors['company_name']) ? 'field--error' : '' ?>">
                        <label for="company_name">Название компании</label>
                        <input id="company_name" name="company_name" type="text" required maxlength="255"
                               value="<?= htmlspecialchars($old['company_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               autocomplete="organization">
                        <p class="field-error" aria-live="polite"><?= htmlspecialchars($fieldError($errors, 'company_name'), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <div class="field <?= isset($errors['contact_name']) ? 'field--error' : '' ?>">
                        <label for="contact_name">Имя</label>
                        <input id="contact_name" name="contact_name" type="text" required maxlength="150"
                               value="<?= htmlspecialchars($old['contact_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               autocomplete="name">
                        <p class="field-error" aria-live="polite"><?= htmlspecialchars($fieldError($errors, 'contact_name'), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <div class="field <?= isset($errors['phone']) ? 'field--error' : '' ?>">
                        <label for="phone_number">Телефон</label>
                        <div class="phone-input">
                            <select id="phone_code" name="phone_code" aria-label="Код страны">
                                <?php foreach ($countries as $code => $meta): ?>
                                    <option value="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>"
                                        title="<?= htmlspecialchars($meta['label'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?= $phoneCode === $code ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input id="phone_number" name="phone_number" type="tel" required
                                   value="<?= htmlspecialchars((string) $phoneNumber, ENT_QUOTES, 'UTF-8') ?>"
                                   autocomplete="tel-national"
                                   placeholder="<?= htmlspecialchars($countries[$phoneCode]['placeholder'] ?? '(999) 123-45-67', ENT_QUOTES, 'UTF-8') ?>"
                                   inputmode="tel">
                        </div>
                        <input type="hidden" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <p class="field-error" aria-live="polite"><?= htmlspecialchars($fieldError($errors, 'phone'), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <div class="field <?= isset($errors['email']) ? 'field--error' : '' ?>">
                        <label for="email">Электронная почта</label>
                        <input id="email" name="email" type="email" required maxlength="190"
                               value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               autocomplete="email">
                        <p class="field-error" aria-live="polite"><?= htmlspecialchars($fieldError($errors, 'email'), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <button class="btn btn--primary btn--block" type="submit">Отправить заявку</button>
                    <p class="form-hint">Нажимая кнопку, вы соглашаетесь на обработку данных для связи по заявке.</p>
                </form>
            </div>
        </div>
    </section>
</main>
