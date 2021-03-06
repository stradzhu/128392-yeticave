<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
            снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $item): ?>
                <li class="promo__item promo__item--<?= $item['icon']; ?>">
                    <a class="promo__link" href="pages/all-lots.html"><?= $item['name']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach ($lots as $item): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img width="350" height="260" alt="<?= htmlspecialchars($item['title']); ?>"
                             src="<?= (file_exists($item['image_path'])) ? $item['image_path'] : 'img/logo.png'; ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $item['category']; ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="lot.php?id=<?=$item['id']?>">
                                <?= htmlspecialchars($item['title']); ?>
                            </a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <?php if ($item['current_price'] > $item['start_price']): ?>
                                    <span class="lot__amount">Текущая цена</span>
                                <?php else: ?>
                                    <span class="lot__amount">Стартовая цена</span>
                                <?php endif; ?>
                                <span class="lot__cost">
                                    <?= price_format($item['current_price']); ?><b class="rub">р</b>
                                </span>
                            </div>
                            <div class="lot__timer timer">
                                <?= time_lot_close($item['date_end']); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
