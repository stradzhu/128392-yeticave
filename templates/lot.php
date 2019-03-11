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

                    <?php if (count($user) && ($user['id'] !== $lot['user_id_author'])): ?>
                        <form class="lot-item__form" action="lot.php?id=<?= $lot['id']; ?>" method="post">
                            <p class="lot-item__form-item form__item<?= isset($errors['cost']) ? ' form__item--invalid' : ''; ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder="<?= price_format($lot['bet_min']); ?>" required value="<?= $form['cost'] ?? ''; ?>">
                                <input type="hidden" name="id" value="<?= $lot['id']; ?>">
                                <?php if (isset($errors['cost'])): ?>
                                    <span class="form__error"><?= $errors['cost']; ?></span>
                                <?php endif; ?>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php if (count($user)): ?>
                    <div class="history">
                        <h3>История ставок (<span>10</span>)</h3>
                        <table class="history__list">
                            <tr class="history__item">
                                <td class="history__name">Иван</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">5 минут назад</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Константин</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">20 минут назад</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Евгений</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">Час назад</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Игорь</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">19.03.17 в 08:21</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Енакентий</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">19.03.17 в 13:20</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Семён</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">19.03.17 в 12:20</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Илья</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">19.03.17 в 10:20</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Енакентий</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">19.03.17 в 13:20</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Семён</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">19.03.17 в 12:20</td>
                            </tr>
                            <tr class="history__item">
                                <td class="history__name">Илья</td>
                                <td class="history__price">10 999 р</td>
                                <td class="history__time">19.03.17 в 10:20</td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
