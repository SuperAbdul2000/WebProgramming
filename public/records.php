<?php
$message = '';

$host = 'mysql';
$db = 'app';
$user = 'user';
$pass = 'secret';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
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

$services = [
    "vitamin_harmony" => "Подписка \"Витаминная гармония\"",
    "deep_harmony" => "Обряд \"Глубокая гармония\"",
    "express_weight_loss" => "Экспресс-похудение \"Сила лугов\"",
    "aura_cleaning" => "Чистка ауры картофелем",
    "nettle_rejuvenation" => "Крапивное омоложение",
    "second_breath" => "Терапия \"Второе дыхание\"",
    "evil_eye_removal" => "Снятие сглаза осиновым веником",
    "oak_bark_treatment" => "Лечение корой дуба"
];


$stmt = $pdo->prepare("SELECT name, email, phone, service, info, appointment_date FROM appointments");
$stmt->execute();
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблица записей</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="records.css">
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
    <div class="container">
        <h1>Таблица записей</h1>
        <?php if ($appointments): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Услуга</th>
                        <th>Доп. информация</th>
                        <th>Дата записи</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <?php 
                            $date = DateTime::createFromFormat('Y-m-d', $appointment['appointment_date']);
                            $formatedDate = $date ? $date->format('d:m:Y') : $appointment['appointment_date'];
                            $serviceName = isset($services[$appointment['service']]) ? $services[$appointment['service']] : $appointment['service'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['name']) ?></td>
                            <td><?= htmlspecialchars($appointment['email']) ?></td>
                            <td><?= htmlspecialchars($appointment['phone']) ?></td>
                            <td><?= $serviceName ?></td>
                            <td><?= htmlspecialchars($appointment['info']) ?></td>
                            <td><?= $formatedDate ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Записей не найдено.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <img src="grass.png" alt="Трава" class="footer-grass">
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>

</body>
</html>
