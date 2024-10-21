<?php
    include_once 'phpfiles/connection.php';

    // Get language choice from the URL, default to English
    $language = isset($_GET['lang']) ? $_GET['lang'] : 'en';

    // Fetch tasks from the database as usual...
    $querySelectAll = "SELECT * FROM tareas";
    $sqlSelectAll = $conn->prepare($querySelectAll);
    $sqlSelectAll->execute();
    $resultado = $sqlSelectAll->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['titleInput'], $_GET['taskDescriptionInput'], $_GET['statusSelect'], $_GET['dueDateInput'])) {
        $task_name = $_GET['titleInput'];
        $task_description = $_GET['taskDescriptionInput'];
        $initialState = $_GET['statusSelect'];
        $dueDate = $_GET['dueDateInput'];
        $isImportant = isset($_GET['isImportant']) ? 1 : 0;

        if (!empty($task_name) && !empty($task_description) && !empty($initialState) && !empty($dueDate)) {
            $stmt = $conn->prepare("INSERT INTO tareas (task_name, task_description, task_status, due_date, is_important) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$task_name, $task_description, $initialState, $dueDate, $isImportant]);
        } else {
            echo "Please fill in all the required fields.";
        }

        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Fernanda Montalvan">
    <meta name="description" content="To-do, gestor de tareas">
    <title>Gestor de Tareas</title>
    <link rel="stylesheet" href="Resources/style.css">
</head>
<body>

    <!-- Language Selection Navbar -->
    <section id="language">
        <nav>
            <ul>
                <li><a href="?lang=en"><img width="48" height="48" src="https://img.icons8.com/doodle/48/usa.png" alt="usa"/></a></li>
                <li><a href="?lang=es"><img width="48" height="48" src="https://img.icons8.com/doodle/48/spain-2.png" alt="spain-2"/></a></li>
                <li><a href="?lang=ca"><img width="50" height="50" src="https://img.icons8.com/stickers/50/catalonia.png" alt="catalonia"/></a></li>
            </ul>
        </nav>
    </section>

    <!-- Language-specific content (header and main) -->
    <?php
        // This will include the appropriate language file based on the selection
        if ($language === 'es') {
            include 'phpfiles/languages/spanish.php';
        } elseif ($language === 'ca') {
            include 'phpfiles/languages/catala.php';
        } else {
            include 'phpfiles/languages/english.php';
        }
    ?>

    <script src="javascript/app.js"></script>
</body>
</html>
