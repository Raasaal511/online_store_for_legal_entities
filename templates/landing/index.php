<?php
/** @var string $csrfToken */
/** @var string|null $flashSuccess */
/** @var array<string, string> $old */
/** @var array<string, string> $errors */
/** @var string $appName */
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
                <?php if ($flashSuccess): ?>
                    <div class="alert alert--success" role="status"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if (!empty($errors['_form'])): ?>
                    <div class="alert alert--error" role="alert"><?= htmlspecialchars($errors['_form'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form class="request-form" method="post" action="/request" novalidate data-validate>
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
                        <?php if (isset($errors['company_name'])): ?>
                            <p class="field-error"><?= htmlspecialchars($errors['company_name'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="field <?= isset($errors['contact_name']) ? 'field--error' : '' ?>">
                        <label for="contact_name">Имя</label>
                        <input id="contact_name" name="contact_name" type="text" required maxlength="150"
                               value="<?= htmlspecialchars($old['contact_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                               autocomplete="name">
                        <?php if (isset($errors['contact_name'])): ?>
                            <p class="field-error"><?= htmlspecialchars($errors['contact_name'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="field-row">
                        <div class="field <?= isset($errors['phone']) ? 'field--error' : '' ?>">
                            <label for="phone">Телефон</label>
                            <input id="phone" name="phone" type="tel" required
                                   value="<?= htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   autocomplete="tel" placeholder="+7 (___) ___-__-__">
                            <?php if (isset($errors['phone'])): ?>
                                <p class="field-error"><?= htmlspecialchars($errors['phone'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="field <?= isset($errors['email']) ? 'field--error' : '' ?>">
                            <label for="email">Электронная почта</label>
                            <input id="email" name="email" type="email" required maxlength="190"
                                   value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   autocomplete="email">
                            <?php if (isset($errors['email'])): ?>
                                <p class="field-error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button class="btn btn--primary btn--block" type="submit">Отправить заявку</button>
                    <p class="form-hint">Нажимая кнопку, вы соглашаетесь на обработку данных для связи по заявке.</p>
                </form>
            </div>
        </div>
    </section>
</main>
