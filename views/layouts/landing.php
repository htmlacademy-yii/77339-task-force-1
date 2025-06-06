<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\View;

/* @var $this View */
/* @var $content string */

$this->registerCssFile('@web/css/normalize.css');
$this->registerCssFile('@web/css/landing.css');

$this->registerJsFile('@web/js/landing.js', ['depends' => [JqueryAsset::class]]);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="landing">
    <?php $this->beginBody() ?>

    <div class="table-layout">
        <header class=" page-header--index">
            <div class="main-container page-header__container page-header__container--index">
                <div class="page-header__logo--index">
                    <a>
                        <svg class="logo-image--index" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1634 646.35">
                            <title>taskforce_logo2-01</title>
                            <g>
                                <g>
                                    <path d="M829.09,330.74c-8.27,0-21.8-1.92-33-12.62L737,260a35.91,35.91,0,0,1-9.5-16.28L710.87,182a23.69,23.69,0,0,0-6.78-11.22c-23-21.3-34.59-36.85-34.59-46.23,0-7.37,2.63-13.55,7.59-17.85,6.77-5.87,16.15-7,22.12-6.1,9.52,1.36,19.72,5.93,26.6,9.52,5.31,2.77,11.7,10.65,14.08,15,19.43,35.76,29.43,40.7,32,41.37a2,2,0,0,0,1.86-.18C787.33,157.54,870.85,71.47,901.89,39l.21-.2c6.1-5.67,21.29-12.5,35.06,2.25,10,10.72,3.22,25.31-1.76,31.54l-.28.32-51.61,54q-.6.65-1.35,1.38c8.22-.75,16.16,1.07,21.62,7.38a19.57,19.57,0,0,1,5,15.22,24.89,24.89,0,0,1-2.44,8.22c6.91-1,13.78.44,18.66,6.78,7.28,9.46,5.17,20,.31,28.53a32,32,0,0,1,8,.07c9.16,1.35,15.26,7,17.16,15.91,1.44,6.73-1.14,15.49-4.29,20.31s-7.49,8.87-11.61,12.64c-1.09,1-2.18,2-3.23,3l-76.19,72.72c-7.68,7.33-15.67,11.21-23.77,11.56C830.61,330.73,829.88,330.74,829.09,330.74ZM695.39,110.29c-3.83,0-8.53,1.13-11.74,3.91-2.79,2.42-4.15,5.79-4.15,10.3s8.22,17.4,31.39,38.9a33.62,33.62,0,0,1,9.63,15.95l16.62,61.73A26,26,0,0,0,744,252.84l59,58.1c9.08,8.65,20.17,10.09,27.87,9.77,7-.3,13.25-4.95,17.28-8.8l76.19-72.73c1.11-1.05,2.24-2.09,3.38-3.14,3.83-3.51,7.45-6.83,10-10.73,2.25-3.45,3.6-9.41,2.88-12.77-1-4.85-3.76-7.35-8.84-8.09-5.41-.8-12.94,1-18.74,4.36l-5.74-8.15c.19-.15,19.17-16.36,9.75-28.61-6.81-8.85-24.92,3.87-25.1,4L886,168c3.29-2.48,12-10.59,12.8-18.13a9.48,9.48,0,0,0-2.58-7.6c-7.33-8.45-23.81-1.49-27.38.16l-.64.56c-2.82,2.48-5.37,2.72-7.56.72l-3.31-3,2.61-3.63c2.51-3.5,7.48-8.33,11.87-12.6,1.75-1.7,3.4-3.3,4.47-4.42l51.43-53.84c1-1.39,8.41-11.6,2.13-18.32-10-10.76-19.4-3.13-20.84-1.83-6,6.28-112.61,117.51-129.78,128.62a12.09,12.09,0,0,1-9.77,1.47c-10.17-2.59-23.07-18.16-38.34-46.28-1.92-3.52-7.07-9.47-9.92-11-5.07-2.64-14.85-7.26-23.4-8.48A16.38,16.38,0,0,0,695.39,110.29Zm173,32.38h0Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M718.46,34.54l29,31c4.41,4.71,11.47-2.38,7.08-7.08l-29-31c-4.41-4.71-11.47,2.38-7.08,7.08Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M782,12V52c0,6.43,10,6.45,10,0V12c0-6.43-10-6.45-10,0Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M822.54,66.54l31-30c4.63-4.49-2.45-11.55-7.08-7.08l-31,30c-4.63,4.49,2.45,11.55,7.08,7.08Z" transform="translate(-5.5 -7.17)"/>
                                </g>
                                <rect y="272.33" width="605" height="18"/>
                                <rect x="1027" y="272.33" width="607" height="18"/>
                                <g>
                                    <path d="M122.47,650.47v-32.6H148.4V490.13H122.47v44.94H92.82V457.53H249.26v77.54H220V490.13H193.31V617.87h25.93v32.6Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M412.66,619.54v30.93H355V639.86q-15.51,12.55-32.76,12.55A45.22,45.22,0,0,1,291.38,641q-13-11.38-13-30.52t13.65-32.32q13.64-13,35.23-13,12,0,27.67,6.79v-8.18q0-10.26-6.21-16.85t-16.62-6.59q-12.9,0-22.08,10.54l-30.9-6.24q13.41-31.77,58.19-31.76,17.37,0,28.78,4.36t17,11.31A42,42,0,0,1,391,543.74q2.3,8.25,2.29,26.84v49ZM355,600.81q-13.91-9.7-22.21-9.71a18.78,18.78,0,0,0-12,4.3q-5.4,4.3-5.4,12.49a19.25,19.25,0,0,0,4.34,12.62,13.84,13.84,0,0,0,11.17,5.27q10.67,0,24.07-14Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M452.36,650.47V604.28h28a18.9,18.9,0,0,0,7.82,15.05q7.44,5.61,18.36,5.62,9.42,0,14.7-3.68t5.27-9.36a9.1,9.1,0,0,0-4.84-8.19q-4.83-2.91-19-5.56-29.14-5.29-42.05-16t-12.91-28.49a37,37,0,0,1,12.16-28.07q12.15-11.43,32.26-11.44a69.92,69.92,0,0,1,30.39,6.73V516.2H550v39H522.58q-3.72-14-26.3-14-16.62,0-16.62,10.26a6.44,6.44,0,0,0,3.41,5.9q3.4,2,17.43,4.78,22.2,4.3,33.25,8.6a37,37,0,0,1,17.68,14.22q6.63,9.92,6.63,23.79,0,19.69-12.59,32.25T509.8,653.52q-21.46,0-29.4-9.78v6.73Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M597.15,650.47V619.54H616.5V488.46H597.15V457.53h57.56V579.38L696,541.86H670.59V516.2h72.58v27.19h-12L696,576.9l32,42.64h16.63v30.93H707.81l-53.1-71.09v41.68h14.52v29.41Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M781.14,650.47v-32.6h24.69V490.13H781.14v-32.6H935.6V517h-33V490.13H850.74v44.66h37.34v32.59H850.74v50.49h26.55v32.6Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M1006.82,653.52q-26.55,0-44.85-18.8t-18.3-51.52q0-32.73,18.3-51.53t44.85-18.79q26.67,0,44.91,18.79T1070,583.2q0,32.31-18,51.32T1006.82,653.52Zm-.13-33.29q10.66,0,17.31-9.15t6.64-28.72q0-17.19-6.46-26.83t-17.49-9.64q-23.7,0-23.69,38.28,0,15.68,5.89,25.87T1006.69,620.23Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M1192.3,619.54v30.93h-84.49V619.54H1130V547.13h-20.85V516.2h56v34.12a48.51,48.51,0,0,1,14.7-25.45,38.35,38.35,0,0,1,27.11-10.75q2.6,0,6.2.14v40.5q-14.52,0-23.7,3.61t-15.13,15.81q-6,12.21-6,28.85v16.51Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M1369.34,516.2v54.51h-27.29q-9.32-22.47-29.16-22.47-12.66,0-20.16,9.37t-7.51,25q0,19,7.94,28.09t19.48,9.08a29.23,29.23,0,0,0,17.37-5.89,42.89,42.89,0,0,0,13.15-15.6L1372.94,613q-16.64,40.57-58.31,40.57-31.39,0-50.06-19.14T1245.9,583.2q0-32,18.3-50.56t42.61-18.52q19.47,0,36.35,13.18V516.2Z" transform="translate(-5.5 -7.17)"/>
                                    <path d="M1506.43,610l29.78,10.68q-18.74,32.86-58.56,32.87-31.76,0-49.56-18.73t-17.81-49.24q0-31.06,18.37-51.87t46.77-20.8q20.6,0,35.6,11.65a60.5,60.5,0,0,1,20.85,29q5.82,17.34,5.83,38.7h-89.45q.87,18.31,10.36,25.94t20,7.63Q1492.41,625.78,1506.43,610Zm-7.32-41.61a36.09,36.09,0,0,0-7.86-19.15q-6.52-7.9-17.82-7.9-21.09,0-24.56,27.05Z" transform="translate(-5.5 -7.17)"/>
                                </g>
                            </g>
                        </svg>
                    </a>
                    <p>Работа там, где ты!</p>
                </div>
                <div class="header__account--index">
                    <a href="<?= Url::to('#') ?>" class="header__account-enter open-modal" data-for="enter-form">
                        <span>Вход</span></a>
                    или
                    <a href="<?= Url::to('/signup') ?>" class="header__account-registration">
                        Регистрация
                    </a>
                </div>
            </div>
        </header>
        <main>
            <div class="landing-container">
                <div class="landing-top">
                    <h1>Работа для всех.<br>
                        Найди исполнителя на любую задачу.</h1>
                    <p>Сломался кран на кухне? Надо отправить документы? Нет времени самому гулять с собакой?
                        У нас вы быстро найдёте исполнителя для любой жизненной ситуации?<br>
                        Быстро, безопасно и с гарантией. Просто, как раз, два, три. </p>
                    <button class="button" onclick="window.location.href='<?= Url::to('/signup') ?>'">
                        Создать аккаунт
                    </button>
                </div>
                <div class="landing-center">
                    <div class="landing-instruction">
                        <div class="landing-instruction-step">
                            <div class="instruction-circle circle-request"></div>
                            <div class="instruction-description">
                                <h3>Публикация заявки</h3>
                                <p>Создайте новую заявку.</p>
                                <p>Опишите в ней все детали
                                    и  стоимость работы.</p>
                            </div>
                        </div>
                        <div class="landing-instruction-step">
                            <div class="instruction-circle  circle-choice"></div>
                            <div class="instruction-description">
                                <h3>Выбор исполнителя</h3>
                                <p>Получайте отклики от мастеров.</p>
                                <p>Выберите подходящего<br>
                                    вам исполнителя.</p>
                            </div>
                        </div>
                        <div class="landing-instruction-step">
                            <div class="instruction-circle  circle-discussion"></div>
                            <div class="instruction-description">
                                <h3>Обсуждение деталей</h3>
                                <p>Обсудите все детали работы<br>
                                    в нашем внутреннем чате.</p>
                            </div>
                        </div>
                        <div class="landing-instruction-step">
                            <div class="instruction-circle circle-payment"></div>
                            <div class="instruction-description">
                                <h3>Оплата&nbsp;работы</h3>
                                <p>По завершении работы оплатите
                                    услугу и закройте задание</p>
                            </div>
                        </div>
                    </div>
                    <div class="landing-notice">
                        <div class="landing-notice-card card-executor">
                            <h3>Исполнителям</h3>
                            <ul class="notice-card-list">
                                <li>
                                    Большой выбор заданий
                                </li>
                                <li>
                                    Работайте где  удобно
                                </li>
                                <li>
                                    Свободный график
                                </li>
                                <li>
                                    Удалённая работа
                                </li>
                                <li>
                                    Гарантия оплаты
                                </li>
                            </ul>
                        </div>
                        <div class="landing-notice-card card-customer">
                            <h3>Заказчикам</h3>
                            <ul class="notice-card-list">
                                <li>
                                    Исполнители на любую задачу
                                </li>
                                <li>
                                    Достоверные отзывы
                                </li>
                                <li>
                                    Оплата по факту работы
                                </li>
                                <li>
                                    Экономия времени и денег
                                </li>
                                <li>
                                    Выгодные цены
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="page-footer">
            <div class="main-container page-footer__container">
                <div class="page-footer__info">
                    <p class="page-footer__info-copyright">
                        © 2021, ООО «ТаскФорс»
                        Все права защищены
                    </p>
                    <p class="page-footer__info-use">
                        «TaskForce» — это сервис для поиска исполнителей на разовые задачи.
                        mail@taskforce.com
                    </p>
                </div>
                <div class="page-footer__links">
                    <ul class="links__list">
                        <li class="links__item">
                            <a href="">Задания</a>
                        </li>
                        <li class="links__item">
                            <a href="">Мой профиль</a>
                        </li>
                        <li class="links__item">
                            <a href="">Исполнители</a>
                        </li>
                        <li class="links__item">
                            <a href="">Регистрация</a>
                        </li>
                        <li class="links__item">
                            <a href="">Создать задание</a>
                        </li>
                        <li class="links__item">
                            <a href="">Справка</a>
                        </li>
                    </ul>
                </div>
                <div class="page-footer__copyright">
                    <a href="https://htmlacademy.ru">
                        <img class="copyright-logo"
                             src="./img/academy-logo.png"
                             width="185" height="63"
                             alt="Логотип HTML Academy">
                    </a>
                </div>
            </div>
        </footer>
        <?= $content ?>
    </div>

    <div class="overlay"></div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
