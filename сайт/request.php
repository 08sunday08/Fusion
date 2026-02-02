<?php
    // Подключение к базе данных
$host = '127.0.1.27';        // Хост, где находится БД
$db = 'fusion';     // Название вашей базы данных
$user = 'root';       // Имя пользователя БД
$pass = '';   // Пароль пользователя БД
$charset = 'utf8mb4';      // Кодировка

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Создание таблицы
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            contact_person TEXT NOT NULL,
            email VARCHAR(255) NOT NULL,
            contact_tg VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
} catch (PDOException $e) {
    die("Ошибка создания таблицы: " . $e->getMessage());
}

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = htmlspecialchars($_POST['project-idea']);    // Имя проекта
    $description = htmlspecialchars($_POST['idea-description']);  // Описание проекта
    $contact_person = htmlspecialchars($_POST['contact-person']);  // Контактное лицо
    $email = htmlspecialchars($_POST['contact-email']);  // Email пользователя
    $contact_tg = htmlspecialchars($_POST['contact-tg']);  // Контактный телефон
    
    
    // Валидация обязательных полей
    if (empty($name) || empty($email)) {
        die("Все поля обязательны для заполнения!");
    }
    
   // Вставка данных
    $stmt = $pdo->prepare("
        INSERT INTO users (name, description, contact_person, email, contact_tg ) 
        VALUES (:name, :description, :contact_person, :email, :contact_tg )
    ");
    
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'contact_person' => $contact_person,
        'email' => $email,
        'contact_tg' => $contact_tg,
    ]);

    header("Location: https://github.com/08sunday08/Fusion");
    exit;
}
