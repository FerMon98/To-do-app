<?php
include_once 'connection.php';

// Create the event if it doesn't exist
$queryArchive = "
    CREATE EVENT IF NOT EXISTS auto_delete_tasks
    ON SCHEDULE EVERY 7 DAY
    DO
    DELETE FROM tareas WHERE task_status = 'archived' AND due_date <= NOW() - INTERVAL 7 DAY;
";

// Execute the query to create the event
$sqlArchive = $conn->prepare($queryArchive);
$sqlArchive->execute();

// Close connection
$sqlArchive = null;
?>
