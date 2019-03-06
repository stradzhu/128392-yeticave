<main>

    <?= $categories_template; ?>

    <form class="form form--add-lot container<?= count($errors) ? ' form--invalid' : ''; ?>" action="add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item<?= isset($errors['name']) ? ' form__item--invalid' : ''; ?>">
                <label for="name">Наименование</label>
                <input id="name" type="text" name="name" placeholder="Введите наименование лота" required value="<?= $form['name'] ?? ''; ?>">
                <span class="form__error">Введите наименование лота</span>
            </div>
            <div class="form__item<?= isset($errors['category']) ? ' form__item--invalid' : ''; ?>">
                <label for="category">Категория</label>
                <select id="category" name="category" required>
                    <option<?= isset($form['category']) ? '' : ' selected'; ?> disabled value="">Выберите категорию</option>
                    <?php foreach ($categories as $item): ?>
                        <option<?= ($form['category'] ?? '' == $item['id']) ? ' selected' : ''; ?> value="<?= $item['id']; ?>"><?= $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error">Выберите категорию</span>
            </div>
        </div>
        <div class="form__item form__item--wide<?= isset($errors['message']) ? ' form__item--invalid' : ''; ?>">
            <label for="message">Описание</label>
            <textarea id="message" name="message" placeholder="Напишите описание лота" required><?= $form['message'] ?? ''; ?></textarea>
            <span class="form__error">Напишите описание лота</span>
        </div>
        <div class="form__item form__item--file<?= isset($errors['image']) ? ' form__item--invalid' : (isset($form['image']) ? ' form__item--uploaded' : ''); ?>">
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="<?= $form['image'] ?? ''; ?>" width="113" height="113" alt>
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" name="image" required value="<?= $form['image'] ?? ''; ?>">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small<?= isset($errors['rate']) ? ' form__item--invalid' : ''; ?>">
                <label for="rate">Начальная цена</label>
                <input id="rate" type="number" name="rate" placeholder="0" required value="<?= $form['rate'] ?? ''; ?>">
                <span class="form__error">Введите начальную цену</span>
            </div>
            <div class="form__item form__item--small<?= isset($errors['step']) ? ' form__item--invalid' : ''; ?>">
                <label for="step">Шаг ставки</label>
                <input id="step" type="number" name="step" placeholder="0" required value="<?= $form['step'] ?? ''; ?>">
                <span class="form__error">Введите шаг ставки</span>
            </div>
            <div class="form__item<?= isset($errors['date']) ? ' form__item--invalid' : ''; ?>">
                <label for="date">Дата окончания торгов</label>
                <input class="form__input-date" id="date" type="date" name="date" required
                       min="<?= date('Y-m-d', strtotime('+1 day')); ?>"
                       max="<?= date('Y-m-d', strtotime('+14 day')); ?>"
                       value="<?= $form['date'] ?? ''; ?>">
                <span class="form__error">Введите дату завершения торгов</span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
