<main>

    <?= $categories_template; ?>

    <form class="form container<?= count($errors) ? ' form--invalid' : ''; ?>" action="sign-up.php" method="post" enctype="multipart/form-data">
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item<?= isset($errors['email']) ? ' form__item--invalid' : ''; ?>">
            <label for="email">E-mail*</label>
            <input id="email" type="email" name="email" placeholder="Введите e-mail" required value="<?= $form['email'] ?? ''; ?>">
            <?php if (isset($errors['email'])): ?>
                <span class="form__error"><?= $errors['email']; ?></span>
            <?php endif; ?>
        </div>
        <div class="form__item<?= isset($errors['password']) ? ' form__item--invalid' : ''; ?>">
            <label for="password">Пароль*</label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" required value="<?= $form['password'] ?? ''; ?>">
            <?php if (isset($errors['password'])): ?>
                <span class="form__error"><?= $errors['password']; ?></span>
            <?php endif; ?>
        </div>
        <div class="form__item<?= isset($errors['name']) ? ' form__item--invalid' : ''; ?>">
            <label for="name">Имя*</label>
            <input id="name" type="text" name="name" placeholder="Введите имя" required value="<?= $form['name'] ?? ''; ?>">
            <?php if (isset($errors['name'])): ?>
                <span class="form__error"><?= $errors['name']; ?></span>
            <?php endif; ?>
        </div>
        <div class="form__item<?= isset($errors['message']) ? ' form__item--invalid' : ''; ?>">
            <label for="message">Контактные данные*</label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться" required><?= $form['message'] ?? ''; ?></textarea>
            <?php if (isset($errors['message'])): ?>
                <span class="form__error"><?= $errors['message']; ?></span>
            <?php endif; ?>
        </div>
        <div class="form__item form__item--file form__item--last<?= isset($errors['image']) ? ' form__item--invalid' : ''; ?>">
            <label>Аватар</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" name="image" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
            <?php if (isset($errors['image'])): ?>
                <span class="form__error"><?= $errors['image']; ?></span>
            <?php endif; ?>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="/login.php">Уже есть аккаунт</a>
    </form>
</main>
