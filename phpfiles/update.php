<?php 
    include_once 'connection.php';

    // Get form data
    $id = $_POST['id'];
    $task_status = $_POST['statusSelect'];
    $action = $_POST['action'];

    // Server-side validation: check if status is empty
    if ($task_status === "") {
        echo "<script>alert('Please select a status before submitting.'); window.history.back();</script>";
        exit();
    }

    // Check if the user clicked the "Archive Task" button
    if ($action === 'archive') {
        // Update task status to "archived"
        $queryArchive = "UPDATE tareas SET task_status = 'archived' WHERE task_id = :id";
        $sqlArchive = $conn->prepare($queryArchive);
        $sqlArchive->bindParam(':id', $id);
        $sqlArchive->execute();
        
        // Close connection
        $sqlArchive = null;
        $conn = null;

        // Redirect to the index page (modify the path if necessary)
        header('Location: /Gestor_de_tareas/index.php#displayTasksSection');
        exit();

    } else if ($action === 'update') {
        // Get updated task details from the form
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];
        $due_date = $_POST['due_date'];
        $task_status = $_POST['statusSelect'];
        $isImportant = isset($_POST['isImportant']) ? 1 : 0;;
        $id = $_POST['id'];
    
        // Update task details including task status
        $queryUpdate = "UPDATE tareas SET task_name = :task_name, task_description = :task_description, due_date = :due_date, task_status = :task_status, is_important = :isImportant WHERE task_id = :id";
        $sqlUpdate = $conn->prepare($queryUpdate);
        $sqlUpdate->bindParam(':task_name', $task_name);
        $sqlUpdate->bindParam(':task_description', $task_description);
        $sqlUpdate->bindParam(':due_date', $due_date);
        $sqlUpdate->bindParam(':task_status', $task_status);
        $sqlUpdate->bindParam(':id', $id);
        $sqlUpdate->bindParam(':isImportant', $isImportant);
        $sqlUpdate->execute();
    
        // Close connection
        $sqlUpdate = null;
        $conn = null;
    
        // Redirect to the index page (modify the path if necessary)
        header('Location: /Gestor_de_tareas/index.php#displayTasksSection');
        exit();
    }    
?>
