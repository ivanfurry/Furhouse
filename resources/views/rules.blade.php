@extends('layouts.app')

@section('title', 'Правила и безопасность')

@section('content')
<!-- Hero -->
<section class="rules-hero">
    <div class="container">
        <span class="rules-hero-label">ДОКУМЕНТЫ</span>
        <h1 class="rules-hero-title">Правила и безопасность</h1>
        <p class="rules-hero-subtitle">
            FurHouse — спокойное место для творчества и общения. Здесь собраны правила, которые помогают сообществу оставаться дружелюбным и безопасным.
        </p>
        <div class="rules-hero-meta">
            <span><i class="bi bi-calendar"></i> Обновлено: 16.11.2026</span>
            <span><i class="bi bi-18-square"></i> 18+</span>
            <span><i class="bi bi-shield-check"></i> Модерация 24/7</span>
        </div>
    </div>
</section>

<div class="container">
    <!-- Navigation -->
    <nav class="rules-nav">
        <a href="#code" class="rules-nav-link">Кодекс</a>
        <a href="#marketplace" class="rules-nav-link">Маркетплейс</a>
        <a href="#events" class="rules-nav-link">Встречи</a>
        <a href="#forum" class="rules-nav-link">Форум</a>
        <a href="#age" class="rules-nav-link">18+</a>
        <a href="#punishment" class="rules-nav-link">Наказания</a>
        <a href="#report" class="rules-nav-link">Жалобы</a>
    </nav>

    <!-- Age Gate -->
    <div class="rules-age-gate">
        <div class="rules-age-gate-icon">
            <i class="bi bi-18-square-fill"></i>
        </div>
        <h3>Только для совершеннолетних</h3>
        <p>
            FurHouse предназначен для пользователей старше 18 лет. Регистрация несовершеннолетних запрещена.
            Контент 18+ на платформе недопустим и немедленно удаляется.
        </p>
    </div>

    <!-- Code of Conduct -->
    <section class="rules-section" id="code">
        <div class="rules-section-header">
            <div class="rules-section-icon">
                <i class="bi bi-heart"></i>
            </div>
            <div>
                <h2 class="rules-section-title">Кодекс уважения</h2>
                <p class="rules-section-subtitle">Основа нашего сообщества — доброжелательность</p>
            </div>
        </div>

        <ul class="rules-list">
            <li class="rules-list-item">
                <span class="rule-num">1</span>
                <div class="rule-text">
                    <strong>Уважайте других участников</strong>
                    <span>Обращайтесь к людям так, как хотели бы, чтобы обращались к вам. Никаких оскорблений, дискриминации, травли и переходов на личности.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">2</span>
                <div class="rule-text">
                    <strong>Не выдавайте себя за других</strong>
                    <span>Запрещено создавать аккаунты от имени других людей или организаций без их ведома.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">3</span>
                <div class="rule-text">
                    <strong>Соблюдайте чужие границы</strong>
                    <span>Не публикуйте личные данные других пользователей без их согласия. Это включает фото, контакты, адреса и переписку.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">4</span>
                <div class="rule-text">
                    <strong>Кредитуйте авторов</strong>
                    <span>Если вы используете чужую работу — указывайте автора. Плагиат и кража контента строго запрещены.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">5</span>
                <div class="rule-text">
                    <strong>Будьте честны в сделках</strong>
                    <span>Не вводите других участников в заблуждение относительно качества, сроков или стоимости товаров и услуг.</span>
                </div>
            </li>
        </ul>
    </section>

    <!-- Marketplace -->
    <section class="rules-section" id="marketplace">
        <div class="rules-section-header">
            <div class="rules-section-icon rules-section-icon--success">
                <i class="bi bi-bag-check"></i>
            </div>
            <div>
                <h2 class="rules-section-title">Маркетплейс и услуги</h2>
                <p class="rules-section-subtitle">Правила для продавцов и покупателей</p>
            </div>
        </div>

        <div class="rules-allowed">
            <div class="rules-allowed-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Оригинальные авторские работы</span>
            </div>
            <div class="rules-allowed-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Честное описание состояния товара</span>
            </div>
            <div class="rules-allowed-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Реалистичные сроки выполнения</span>
            </div>
            <div class="rules-allowed-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Прозрачные условия оплаты</span>
            </div>
        </div>

        <div class="rules-prohibited">
            <div class="rules-prohibited-item">
                <i class="bi bi-x-circle-fill"></i>
                <span>Перепродажа чужих работ без разрешения</span>
            </div>
            <div class="rules-prohibited-item">
                <i class="bi bi-x-circle-fill"></i>
                <span>Использование AI-генерации без указания</span>
            </div>
            <div class="rules-prohibited-item">
                <i class="bi bi-x-circle-fill"></i>
                <span>Поддельные и контрафактные товары</span>
            </div>
            <div class="rules-prohibited-item">
                <i class="bi bi-x-circle-fill"></i>
                <span>Обман покупателей и предоплата без гарантий</span>
            </div>
        </div>

        <div class="rules-callout rules-callout--info">
            <i class="bi bi-info-circle-fill"></i>
            <div class="rules-callout-content">
                <strong>Безопасные сделки</strong>
                <p>Рекомендуем использовать встроенную систему споров FurHouse при любых разногласиях. Модераторы вмешиваются в конфликт в течение 24 часов.</p>
            </div>
        </div>
    </section>

    <!-- Events -->
    <section class="rules-section" id="events">
        <div class="rules-section-header">
            <div class="rules-section-icon rules-section-icon--warning">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div>
                <h2 class="rules-section-title">Встречи и события</h2>
                <p class="rules-section-subtitle">Правила для организаторов и участников</p>
            </div>
        </div>

        <ul class="rules-list">
            <li class="rules-list-item">
                <span class="rule-num">1</span>
                <div class="rule-text">
                    <strong>Открытые и безопасные места</strong>
                    <span>Встречи организуются в публичных местах с хорошей освещённостью и проходимостью.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">2</span>
                <div class="rule-text">
                    <strong>Прозрачные условия</strong>
                    <span>Организатор обязан указать стоимость, длительность и формат встречи заранее.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">3</span>
                <div class="rule-text">
                    <strong>Уважение к участникам</strong>
                    <span>На встречах запрещён любой контент 18+, алкоголь и агрессивное поведение.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">4</span>
                <div class="rule-text">
                    <strong>Отмена и перенос</strong>
                    <span>Организатор обязан предупредить об отмене минимум за 24 часа. Систематические отмены ведут к блокировке.</span>
                </div>
            </li>
        </ul>
    </section>

    <!-- Forum -->
    <section class="rules-section" id="forum">
        <div class="rules-section-header">
            <div class="rules-section-icon">
                <i class="bi bi-chat-square-text"></i>
            </div>
            <div>
                <h2 class="rules-section-title">Правила форума</h2>
                <p class="rules-section-subtitle">Как общаться в мини-форуме FurHouse</p>
            </div>
        </div>

        <ul class="rules-list">
            <li class="rules-list-item">
                <span class="rule-num">1</span>
                <div class="rule-text">
                    <strong>Выбирайте правильную доску</strong>
                    <span>Каждый тред должен соответствовать тематике доски: /art/, /craft/, /meet/, /play/, /start/.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">2</span>
                <div class="rule-text">
                    <strong>Никакого спама и рекламы</strong>
                    <span>Повторяющиеся посты, реклама сторонних ресурсов и накрутка ответов запрещены.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">3</span>
                <div class="rule-text">
                    <strong>Без провокаций и флейма</strong>
                    <span>Целенаправленное разжигание конфликтов ведёт к блокировке аккаунта.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">4</span>
                <div class="rule-text">
                    <strong>Помогайте новичкам</strong>
                    <span>В доске /start/ приветствуются подробные и дружелюбные ответы.</span>
                </div>
            </li>
        </ul>
    </section>

    <!-- Punishment -->
    <section class="rules-section" id="punishment">
        <div class="rules-section-header">
            <div class="rules-section-icon rules-section-icon--danger">
                <i class="bi bi-shield-x"></i>
            </div>
            <div>
                <h2 class="rules-section-title">Наказания</h2>
                <p class="rules-section-subtitle">Что происходит при нарушении правил</p>
            </div>
        </div>

        <table class="rules-punishment-table">
            <thead>
                <tr>
                    <th>Нарушение</th>
                    <th>Первый раз</th>
                    <th>Повторно</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Некорректное общение</td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--warn">Предупреждение</span></td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--restrict">Запрет постов 7 дней</span></td>
                </tr>
                <tr>
                    <td>Спам и реклама</td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--restrict">Запрет постов 14 дней</span></td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--ban">Блокировка</span></td>
                </tr>
                <tr>
                    <td>Плагиат чужих работ</td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--restrict">Удаление + запрет товаров 30 дней</span></td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--ban">Блокировка</span></td>
                </tr>
                <tr>
                    <td>Обман в сделке</td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--restrict">Возврат средств + ограничения</span></td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--ban">Блокировка</span></td>
                </tr>
                <tr>
                    <td>Контент 18+</td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--ban">Мгновенная блокировка</span></td>
                    <td><span class="rules-punishment-badge rules-punishment-badge--ban">Пожизненный бан</span></td>
                </tr>
            </tbody>
        </table>

        <div class="rules-callout rules-callout--warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div class="rules-callout-content">
                <strong>Апелляция</strong>
                <p>Если вы не согласны с решением модератора, создайте обращение через раздел "Мои обращения". Администратор рассмотрит его в течение 48 часов.</p>
            </div>
        </div>
    </section>

    <!-- Report -->
    <section class="rules-section" id="report">
        <div class="rules-section-header">
            <div class="rules-section-icon rules-section-icon--success">
                <i class="bi bi-flag"></i>
            </div>
            <div>
                <h2 class="rules-section-title">Как сообщить о нарушении</h2>
                <p class="rules-section-subtitle">Помогите нам сохранить сообщество безопасным</p>
            </div>
        </div>

        <ul class="rules-list">
            <li class="rules-list-item">
                <span class="rule-num">1</span>
                <div class="rule-text">
                    <strong>Нажмите "Сообщить" на карточке</strong>
                    <span>На каждой странице товара, услуги, встречи или треда есть кнопка жалобы.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">2</span>
                <div class="rule-text">
                    <strong>Или напишите модератору</strong>
                    <span>Перейдите в раздел "Команда" и нажмите "Сообщение" у любого модератора.</span>
                </div>
            </li>
            <li class="rules-list-item">
                <span class="rule-num">3</span>
                <div class="rule-text">
                    <strong>Опишите ситуацию</strong>
                    <span>Укажите ссылку на нарушение и кратко опишите, что произошло. Чем подробнее — тем быстрее решим.</span>
                </div>
            </li>
        </ul>

        <div class="rules-callout rules-callout--danger">
            <i class="bi bi-bell-fill"></i>
            <div class="rules-callout-content">
                <strong>Срочные ситуации</strong>
                <p>Если угроза жизни или здоровью — немедленно звоните в правоохранительные органы. Модерация FurHouse не заменяет полицию.</p>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <div class="rules-contact">
        <h3>Остались вопросы?</h3>
        <p>Команда FurHouse всегда на связи и готова помочь.</p>
        <div class="rules-contact-actions">
            @auth
                <a href="{{ route('support.create') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-chat-dots me-2"></i>Создать обращение
                </a>
            @else
                <a href="{{ route('login') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Войти
                </a>
            @endauth
            <a href="{{ route('team') }}" class="fh-btn fh-btn-ghost">
                <i class="bi bi-people me-2"></i>Команда
            </a>
        </div>
    </div>

    <div class="rules-last-updated">
        Последнее обновление правил: 16.11.2026 · Версия 2.4.1
    </div>
</div>
@endsection