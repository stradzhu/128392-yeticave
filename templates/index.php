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
            <?php foreach ($products as $item): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img width="350" height="260" alt="<?= $item['name']; ?>"
                             src="<?= (file_exists($item['image'])) ? $item['image'] : 'img/logo.png'; ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $item['category']; ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="pages/lot.html">
                                <?= htmlspecialchars($item['name']); ?>
                            </a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost">
                                    <?= price_format($item['price']); ?><b class="rub">р</b>
                                </span>
                            </div>
                            <div class="lot__timer timer">
                                <?= time_lot_close($item['time']); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
