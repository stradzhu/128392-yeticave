<main>

    <?= $categories_template; ?>

    <section class="lot-item container">
        <h2><?= htmlspecialchars($lot['title']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img width="730" height="548"
                         src="<?= (file_exists($lot['image_path'])) ? $lot['image_path'] : 'img/logo.png'; ?>"
                         alt="<?= htmlspecialchars($lot['title']); ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['category']; ?></span></p>
                <p class="lot-item__description">
                    <?= htmlspecialchars($lot['description']); ?>
                </p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?= time_lot_close($lot['date_end']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost">
                                <?= price_format($lot['price']); ?>
                            </span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= price_format($lot['bet_min']); ?> р</span>
                        </div>
                    </div>
                    <? /* Если пользователь авторизован (count($user)) и он не является автором этого объявления ($user['id'] !== $lot['user_id_author']) и
                          ставок нету (!isset($bets[0]) или ставки есть, но последняя не от текущего пользователя ($bets[0]['user_id'] !== $user['id'])
                          то тогда показать форму для добавления ставки */?>
                    <?php if (count($user) && ($user['id'] !== $lot['user_id_author']) && (!isset($bets[0]) || (isset($bets[0]) && $bets[0]['user_id'] !== $user['id']))): ?>
                        <form class="lot-item__form" action="lot.php?id=<?= $lot['id']; ?>" method="post">
                            <p class="lot-item__form-item form__item<?= isset($errors['cost']) ? ' form__item--invalid' : ''; ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder="<?= price_format($lot['bet_min']); ?>" required value="<?= $form['cost'] ?? ''; ?>">
                                <?php if (isset($errors['cost'])): ?>
                                    <span class="form__error"><?= $errors['cost']; ?></span>
                                <?php endif; ?>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php if (count($bets)): ?>
                    <div class="history">
                        <h3>История ставок (<span><?= count($bets); ?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($bets as $item): ?>
                                <tr class="history__item">
                                    <td class="history__name"><?= htmlspecialchars($item['name']); ?></td>
                                    <td class="history__price"><?= price_format($item['price']); ?> р</td>
                                    <td class="history__time"><?= bets_add_friendly($item['date_add']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
