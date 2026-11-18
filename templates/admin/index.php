<?php
/** @var list<\App\Domain\Application\Application> $applications */
/** @var list<\App\Domain\Application\ApplicationStatus> $statuses */
/** @var \App\Domain\Application\ApplicationFilter $filter */
/** @var array<string, mixed> $query */
/** @var array{type: string, message: string}|null $flash */
/** @var string $csrfToken */

$statusValue = $filter->status?->value ?? '';
$qValue = $filter->query ?? '';
$dateFrom = isset($query['date_from']) ? (string) $query['date_from'] : '';
$dateTo = isset($query['date_to']) ? (string) $query['date_to'] : '';
?>
<section class="admin-page">
    <div class="admin-title-row">
        <div>
            <h1>Заявки</h1>
            <p class="section-lead">Все обращения с формы сотрудничества для юридических лиц.</p>
        </div>
        <p class="count-badge"><?= count($applications) ?> в выборке</p>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert--<?= $flash['type'] === 'success' ? 'success' : 'error' ?>" role="status">
            <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form class="filters" method="get" action="/admin">
        <div class="field">
            <label for="status">Статус</label>
            <select id="status" name="status">
                <option value="">Все</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= $status->value ?>" <?= $statusValue === $status->value ? 'selected' : '' ?>>
                        <?= htmlspecialchars($status->label(), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="q">Поиск</label>
            <input id="q" name="q" type="search" placeholder="Компания, имя, телефон, email"
                   value="<?= htmlspecialchars($qValue, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
            <label for="date_from">С</label>
            <input id="date_from" name="date_from" type="date" value="<?= htmlspecialchars($dateFrom, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
            <label for="date_to">По</label>
            <input id="date_to" name="date_to" type="date" value="<?= htmlspecialchars($dateTo, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="filters-actions">
            <button class="btn btn--primary" type="submit">Применить</button>
            <a class="btn btn--ghost" href="/admin">Сбросить</a>
        </div>
    </form>

    <?php if ($applications === []): ?>
        <div class="empty-state">
            <p>По выбранным фильтрам заявок нет.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Компания</th>
                    <th>Контакт</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Статус</th>
                    <th>Создана</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($applications as $item): ?>
                    <tr>
                        <td data-label="ID">#<?= (int) $item->id ?></td>
                        <td data-label="Компания"><?= htmlspecialchars($item->companyName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Контакт"><?= htmlspecialchars($item->contactName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Телефон">
                            <a href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', $item->phone) ?? $item->phone, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($item->phone, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </td>
                        <td data-label="Email">
                            <a href="mailto:<?= htmlspecialchars($item->email, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($item->email, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </td>
                        <td data-label="Статус">
                            <span class="status status--<?= htmlspecialchars($item->status->value, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($item->status->label(), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td data-label="Создана"><?= htmlspecialchars($item->createdAt->format('d.m.Y H:i'), ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Действие">
                            <form method="post" action="/admin/status" class="status-form">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= (int) $item->id ?>">
                                <?php if ($statusValue !== ''): ?><input type="hidden" name="filter_status" value="<?= htmlspecialchars($statusValue, ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
                                <?php if ($qValue !== ''): ?><input type="hidden" name="q" value="<?= htmlspecialchars($qValue, ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
                                <?php if ($dateFrom !== ''): ?><input type="hidden" name="date_from" value="<?= htmlspecialchars($dateFrom, ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
                                <?php if ($dateTo !== ''): ?><input type="hidden" name="date_to" value="<?= htmlspecialchars($dateTo, ENT_QUOTES, 'UTF-8') ?>"><?php endif; ?>
                                <select name="status" aria-label="Сменить статус заявки #<?= (int) $item->id ?>">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= $status->value ?>" <?= $item->status === $status ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($status->label(), ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn--small btn--ghost" type="submit">OK</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
