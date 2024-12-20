<?php
$message = '';

$host = 'mysql';
$db = 'app';
$user = 'user';
$pass = 'secret';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET NAMES 'utf8mb4'");
} catch (\PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $service = $_POST['service'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $info = $_POST['info'] ?? '';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date = ?");
    $stmt->execute([$appointment_date]);
    $existingRecords = $stmt->fetchColumn();

    if ($existingRecords > 0) {
        $message = 'Ошибка: на эту дату уже есть запись. Пожалуйста, выберите другую дату.';
    }
    else
    {
        $stmt = $pdo->prepare("INSERT INTO appointments (name, email, phone, service, appointment_date, info) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $service, $appointment_date, $info])) {
            $success = true;
            header('Location: ' . $_SERVER['REQUEST_URI'] . '?success=1');
            exit();
        }
        else 
        {
            $message = 'Ошибка при добавлении данных в базу.';
        }
    }

    
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запись</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="appointment.css">
</head>
<body>
<header>
    <a href="index.html">
        <img src="logo.png" alt="Логотип" class="logo">
    </a>
    <nav>
        <ul>
            <li><a href="services.html">Услуги</a></li>
            <li><a href="appointment.php">Запись</a></li>
            <li><a href="records.php">Таблица записей</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="image-container">
        <img src="cone.png" alt="Изображение" class="side-image left-image">
        <section class="form-section">
            <h1>Запись</h1>
            <form method="POST" id="appointmentForm">
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" placeholder="Введите ваше имя" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Введите ваш email" required>

                <label for="phone">Телефон:</label>
                <input type="tel" id="phone" name="phone" placeholder="Введите ваш телефон (опционально)">


                <label for="service">Выберите услугу:</label>
                <select id="service" name="service" required>
                    <option value="" disabled selected>Выберите услугу...</option>
                    <option value="vitamin_harmony">Подписка "Витаминная гармония"</option>
                    <option value="deep_harmony">Обряд "Глубокая гармония"</option>
                    <option value="express_weight_loss">Экспресс-похудение "Сила лугов"</option>
                    <option value="aura_cleaning">Чистка ауры картофелем</option>
                    <option value="nettle_rejuvenation">Крапивное омоложение</option>
                    <option value="second_breath">Терапия "Второе дыхание"</option>
                    <option value="evil_eye_removal">Снятие сглаза осиновым веником</option>
                    <option value="oak_bark_treatment">Лечение корой дуба</option>
                </select>

                <label for="appointment_date">Дата:</label>
                <input type="text" id="appointment_date" name="appointment_date" placeholder="Введите дату в формате дд:мм:гггг" required>

                <label for="info">Дополнительная информация:</label>
                <textarea id="info" name="info" placeholder="Укажите желаемое время и другие детали"></textarea>

                <button type="submit">Записаться</button>
            </form>
            <?php if ($message): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </section>
        <img src="cone.png" alt="Изображение" class="side-image right-image">
    </div>
</main>

<div class="modal" id="modalSuccess" tabindex="-1" aria-labelledby="modalSuccessLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSuccessLabel">Успех</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Данные формы успешно отправлены!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>

<footer>
    <img src="grass.png" alt="Трава" class="footer-grass">
</footer>

</html>