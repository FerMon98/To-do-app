<?php
    include_once 'phpfiles/connection.php';


    //Sentencia SQL a ejecutar
    $querySelectAll = "SELECT * FROM tareas";


    //Preparar la ejecucion
    $sqlSelectAll = $conn->prepare($querySelectAll);

    //Ejecucion de la peticion a la Base de Datos
    $sqlSelectAll -> execute();

    //Guardar el resultado como array asociativo.
    $resultado = $sqlSelectAll -> fetchAll() ;

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['titleInput'], $_GET['taskDescriptionInput'], $_GET['statusSelect'], $_GET['dueDateInput'])) {

        // Get form data from GET only if the keys are set
        $task_name = $_GET['titleInput'] ?? null;
        $task_description = $_GET['taskDescriptionInput'] ?? null;
        $initialState = $_GET['statusSelect'] ?? null;
        $dueDate = $_GET['dueDateInput'] ?? null;
        $isImportant = isset($_GET['isImportant']) ? 1 : 0;

        // Ensure required fields are not empty before proceeding
        if (!empty($task_name) && !empty($task_description) && !empty($initialState) && !empty($dueDate)) {

            // Insert data into database
            $stmt = $conn->prepare("INSERT INTO tareas (task_name, task_description, task_status, due_date, is_important) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(array($task_name, $task_description, $initialState, $dueDate, $isImportant));

        } else {
            // Handle the case where some fields are empty
            echo "Please fill in all the required fields.";
        }

        // Redirect to index.php after the update
        header('Location: index.php');
        exit();
    }

    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta author="Fernanda Montalvan">
    <meta name="description" content="To-do, gestor de tareas">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' class='bi bi-newspaper' viewBox='0 0 16 16'%3E%3Cpath d='M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z'/%3E%3Cpath d='M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z'/%3E%3C/svg%3E">
    <title>Gestor de Tareas</title>
    <link rel="stylesheet" href="Resources/style.css">
</head>
<body>
    <header>
        <h1>Let's Be Productive Today!</h1>
        <p><strong>Remember to check your daily tasks and update them as you make your progress through them.</strong></p>
    </header>

    <main>
        <section id="addTaskSection">
            <div id="taskToday">
                <h2>Tasks due today:</h2>
                <ul id="todaysTasksUl">
                    <?php foreach ($resultado as $fila) : ?>

                        <?php if ($fila['due_date'] === date('Y-m-d')) : ?>

                            <li>
                                <h3><?php echo $fila['task_name']; ?></h3>
                                <!-- Check if the task is important and display the label -->
                                <?php if ($fila['is_important'] === 1) : ?>
                                    <span class="important">Important</span>
                                <?php endif; ?>
                                <p class="description"><?php echo $fila['task_description']; ?></p>
                                <p><strong>Status: </strong>
                                    <?php 
                                        if ($fila['task_status'] === 'pending') {
                                            echo 'Pending.'; 
                                        } elseif ($fila['task_status'] === 'inProgress') {
                                            echo 'In Progress.'; 
                                        } elseif ($fila['task_status'] === 'completed') {
                                            echo 'You have already completed this task.'; 
                                        } elseif ($fila['task_status'] === 'archived') {
                                             echo 'This task is archived.';
                                        }
                                    ?>
                                </p>

                                
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <details>
                <summary>Add a New Task:</summary>
                <form id="taskForm" method="get">
                    <input type="text" name="titleInput" id="titleInput" placeholder="Title" required>
                    <input type="text" name="taskDescriptionInput" id="taskDescriptionInput" placeholder="description">
                    <select name="statusSelect" id="statusSelect" required>
                        <option value="pending" selected>Pending</option>
                        <option value="inProgress">In progress</option>
                        <option value="completed">Completed</option>
                    </select>
                    <input type="date" id="dueDateInput" name="dueDateInput" min="<?php echo date('Y-m-d'); ?>" required>

                    <div>
                        <label for="isImportant">Do you want to mark this tasks as Important?</label>
                        <input type="checkbox" id="isImportant" name="isImportant">
                    </div>
                    
                    <button type="submit">Add Task</button>
                </form>
            </details>
        </section>

        <hr> 

        <section id="displayTasksSection">
            
            <div class="carousel-content">
                <div>
                    <h2>Pending Tasks:</h2>
                    <ul id="pendingTasksUl">
                        <?php foreach ($resultado as $pendingFiles) : ?>
                            <?php if ($pendingFiles['task_status'] === 'pending') : ?>
                                <li>
                                    <h3><?php echo $pendingFiles['task_name']; ?></h3>
                                    
                                    <!-- Check if the task is important and display the label -->
                                    <?php if ($pendingFiles['is_important'] === 1) : ?>
                                        <span class="important">Important</span>
                                    <?php endif; ?>
                                    
                                    <!-- Task Description -->
                                    <p  class="description" style="background-color: rgba(255, 255, 255, 0.60); color: black; border-radius: 15px; padding: 0.5rem">
                                        <?php echo $pendingFiles['task_description']; ?>
                                    </p>

                                    <!-- Task Due Date -->
                                    <p><strong>Due date:</strong> <?php echo $pendingFiles['due_date']; ?></p>

                                    <!-- Task Status -->
                                    <p><strong>Status:</strong> <?php echo ucfirst($pendingFiles['task_status']); ?></p>

                                    <div class="modify-task">
                                        <!-- Button to trigger modal for editing this task -->
                                        <button type="submit" data-task-id="<?php echo $pendingFiles['task_id']; ?>" class="edit-btn">Edit</button>

                                        <!-- Archive Task Button -->
                                        <form action="phpfiles/update.php" method="post" class="archive-form">
                                            <input type="hidden" name="id" value="<?php echo $pendingFiles['task_id']; ?>">
                                            <input type="hidden" name="action" value="archive">
                                            <button type="submit" class="archive-btn">Archive</button>
                                        </form>
                                    </div>
                                    
                                    <!-- Hidden modal for editing the task -->
                                    <div class="modal" data-modal-id="<?php echo $pendingFiles['task_id']; ?>">
                                        <div class="modal-content">
                                            <span class="close" data-modal-id="<?php echo $pendingFiles['task_id']; ?>">&times;</span>

                                            <form action="phpfiles/update.php" method="post">
                                                <input type="hidden" name="id" value="<?php echo $pendingFiles['task_id']; ?>">

                                                <!-- Task Name Input -->
                                                <label for="task_name">Task Name:</label>
                                                <input type="text" name="task_name" value="<?php echo $pendingFiles['task_name']; ?>" required>

                                                <!-- Task Description Input -->
                                                <label for="task_description">Task Description:</label>
                                                <textarea name="task_description" required><?php echo $pendingFiles['task_description']; ?></textarea>

                                                <!-- Due Date Input -->
                                                <label for="due_date">Due Date:</label>
                                                <input type="date" name="due_date" value="<?php echo $pendingFiles['due_date']; ?>" min="<?php echo date('Y-m-d'); ?>"required>

                                                <!-- Status Select -->
                                                <label for="statusSelect">Status:</label>
                                                <select name="statusSelect" required>
                                                    <option value="pending" <?php if ($pendingFiles['task_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                                    <option value="inProgress" <?php if ($pendingFiles['task_status'] == 'inProgress') echo 'selected'; ?>>In progress</option>
                                                    <option value="completed" <?php if ($pendingFiles['task_status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                                </select>

                                                <!-- Is Important? -->
                                                <div>
                                                    <label for="isImportant">Do you want to mark this tasks as Important?</label>
                                                    <input type="checkbox" id="isImportant" name="isImportant">
                                                </div>

                                                <!-- Modal buttons -->
                                                <div class="modal-buttons">
                                                    <button type="submit" name="action" value="update" class="submit">Submit</button>
                                                    <button type="button" class="cancel-btn" data-modal-id="<?php echo $pendingFiles['task_id']; ?>">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
        
                <div>
                    <h2>In Progress:</h2>
                    <ul id="inProgressTasks">
                        <?php foreach ($resultado as $inProgressTasks) : ?>
                            <?php if ($inProgressTasks['task_status'] === 'inProgress') : ?>
                                <li>
                                <h3><?php echo $inProgressTasks['task_name']; ?></h3>
                                        
                                        <!-- Check if the task is important and display the label -->
                                        <?php if ($inProgressTasks['is_important'] === 1) : ?>
                                            <span class="important">Important</span>
                                        <?php endif; ?>
                                        
                                        <!-- Task Description -->
                                        <p  class="description" style="background-color: rgba(255, 255, 255, 0.60); color: black; border-radius: 15px; padding: 0.5rem">
                                            <?php echo $inProgressTasks['task_description']; ?>
                                        </p>

                                        <!-- Task Due Date -->
                                        <p><strong>Due date:</strong> <?php echo $inProgressTasks['due_date']; ?></p>

                                        <!-- Task Status -->
                                        <p><strong>Status:</strong> <?php echo ucfirst($inProgressTasks['task_status']); ?></p>

                                        <div class="modify-task">
                                            <!-- Button to trigger modal for editing this task -->
                                            <button type="submit" data-task-id="<?php echo $inProgressTasks['task_id']; ?>" class="edit-btn">Edit</button>

                                            <!-- Archive Task Button -->
                                            <form action="phpfiles/update.php" method="post" class="archive-form">
                                                <input type="hidden" name="id" value="<?php echo $inProgressTasks['task_id']; ?>">
                                                <input type="hidden" name="action" value="archive">
                                                <button type="submit" class="archive-btn">Archive</button>
                                            </form>
                                        </div>
                                        
                                        <!-- Hidden modal for editing the task -->
                                        <div class="modal" data-modal-id="<?php echo $inProgressTasks['task_id']; ?>">
                                            <div class="modal-content">
                                                <span class="close" data-modal-id="<?php echo $inProgressTasks['task_id']; ?>">&times;</span>

                                                <form action="phpfiles/update.php" method="post">
                                                    <input type="hidden" name="id" value="<?php echo $inProgressTasks['task_id']; ?>">

                                                    <!-- Task Name Input -->
                                                    <label for="task_name">Task Name:</label>
                                                    <input type="text" name="task_name" value="<?php echo $inProgressTasks['task_name']; ?>" required>

                                                    <!-- Task Description Input -->
                                                    <label for="task_description">Task Description:</label>
                                                    <textarea name="task_description" required><?php echo $inProgressTasks['task_description']; ?></textarea>

                                                    <!-- Due Date Input -->
                                                    <label for="due_date">Due Date:</label>
                                                    <input type="date" name="due_date" value="<?php echo $pendinProgressTasksingFiles['due_date']; ?>" min="<?php echo date('Y-m-d'); ?>" required>

                                                    <!-- Status Select -->
                                                    <label for="statusSelect">Status:</label>
                                                    <select name="statusSelect" required>
                                                        <option value="pending" <?php if ($inProgressTasks['task_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                                        <option value="inProgress" <?php if ($inProgressTasks['task_status'] == 'inProgress') echo 'selected'; ?>>In progress</option>
                                                        <option value="completed" <?php if ($inProgressTasks['task_status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                                    </select>

                                                    <!-- Is Important? -->
                                                    <div>
                                                        <label for="isImportant">Do you want to mark this tasks as Important?</label>
                                                        <input type="checkbox" id="isImportant" name="isImportant">
                                                    </div>

                                                    <!-- Modal buttons -->
                                                    <div class="modal-buttons">
                                                        <button type="submit" name="action" value="update" class="submit">Submit</button>
                                                        <button type="button" class="cancel-btn" data-modal-id="<?php echo $inProgressTasks['task_id']; ?>">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php /*Waiting for 3rd party */ ?>
        
                <div>
                    <h2>Completed Tasks:</h2>
                    <ul id="completedTasksUl">
                        <?php foreach ($resultado as $completedTasks) : ?>
                            <?php if ($completedTasks['task_status'] === 'completed') : ?>
                                <li>
                                <h3><?php echo $completedTasks['task_name']; ?></h3>
                                        
                                        <!-- Check if the task is important and display the label -->
                                        <?php if ($completedTasks['is_important'] === 1) : ?>
                                            <span class="important">Important</span>
                                        <?php endif; ?>
                                        
                                        <!-- Task Description -->
                                        <p class="description" style="background-color: rgba(255, 255, 255, 0.60); color: black; border-radius: 15px; padding: 0.5rem">
                                            <?php echo $completedTasks['task_description']; ?>
                                        </p>

                                        <!-- Task Due Date -->
                                        <p><strong>Due date:</strong> <?php echo $completedTasks['due_date']; ?></p>

                                        <!-- Task Status -->
                                        <p><strong>Status:</strong> <?php echo ucfirst($completedTasks['task_status']); ?></p>

                                        <div class="modify-task">
                                            <!-- Button to trigger modal for editing this task -->
                                            <button type="submit" data-task-id="<?php echo $completedTasks['task_id']; ?>" class="edit-btn">Edit</button>

                                            <!-- Archive Task Button -->
                                            <form action="phpfiles/update.php" method="post" class="archive-form">
                                                <input type="hidden" name="id" value="<?php echo $completedTasks['task_id']; ?>">
                                                <input type="hidden" name="action" value="archive">
                                                <button type="submit" class="archive-btn">Archive</button>
                                            </form>
                                        </div>
                                        
                                        <!-- Hidden modal for editing the task -->
                                        <div class="modal" data-modal-id="<?php echo $completedTasks['task_id']; ?>">
                                            <div class="modal-content">
                                                <span class="close" data-modal-id="<?php echo $completedTasks['task_id']; ?>">&times;</span>

                                                <form action="phpfiles/update.php" method="post">
                                                    <input type="hidden" name="id" value="<?php echo $completedTasks['task_id']; ?>">

                                                    <!-- Task Name Input -->
                                                    <label for="task_name">Task Name:</label>
                                                    <input type="text" name="task_name" value="<?php echo $completedTasks['task_name']; ?>" required>

                                                    <!-- Task Description Input -->
                                                    <label for="task_description">Task Description:</label>
                                                    <textarea name="task_description" required><?php echo $completedTasks['task_description']; ?></textarea>

                                                    <!-- Due Date Input -->
                                                    <label for="due_date">Due Date:</label>
                                                    <input type="date" name="due_date" value="<?php echo $completedTasks['due_date']; ?>" min="<?php echo date('Y-m-d'); ?>" required>

                                                    <!-- Status Select -->
                                                    <label for="statusSelect">Status:</label>
                                                    <select name="statusSelect" required>
                                                        <option value="pending" <?php if ($completedTasks['task_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                                        <option value="inProgress" <?php if ($completedTasks['task_status'] == 'inProgress') echo 'selected'; ?>>In progress</option>
                                                        <option value="completed" <?php if ($completedTasks['task_status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                                    </select>

                                                    <!-- Is Important? -->
                                                    <div>
                                                        <label for="isImportant">Do you want to mark this tasks as Important?</label>
                                                        <input type="checkbox" id="isImportant" name="isImportant">
                                                    </div>

                                                    <!-- Modal buttons -->
                                                    <div class="modal-buttons">
                                                        <button type="submit" name="action" value="update" class="submit">Submit</button>
                                                        <button type="button" class="cancel-btn" data-modal-id="<?php echo $completedTasks['task_id']; ?>">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        
        </section>
        
        <hr>
        
        <section id="archived">
            <div>
                <h2>Archived Tasks:</h2>
                <p>Do remember tasks in the Archived section get deleted 7 days after it's due date.</p><br>
                <ul id="archiveTasksUl">
                    <?php foreach ($resultado as $archivedFiles) : ?>

                        <?php if ($archivedFiles['task_status'] === 'archived') : ?>

                            <li>
                                    <h3><?php echo $archivedFiles['task_name']; ?></h3>
                                    <p style="background-color: rgba(255, 255, 255, 0.60); color: black; border-radius: 15px; padding: 0.5rem">
                                        <?php echo $archivedFiles['task_description']; ?>
                                    </p>
                                    <p><strong>Due date:</strong> <?php echo $archivedFiles['due_date']; ?></p>
                                    <p><strong>Days remaining: <?php 
                                    // Handling undefined modifyTaskStatus
                                    $modifyTaskStatus = $_GET['modifyTaskStatus'] ?? null;

                                    // Get the current date
                                    $currentDate = new DateTime();
                                    $dueDate = new DateTime($archivedFiles['due_date']);

                                    // Calculate the difference in days
                                    $interval = $currentDate->diff($dueDate);
                                    $daysRemaining = $interval->days;

                                    $daysRemainingText = $daysRemaining;

                                    // Adding task deletion logic here
                                    include 'phpfiles/delete.php';

                                    // Display days remaining if the task hasn't been modified and is due within the next 7 days
                                    echo $daysRemainingText; ?> </strong></p>
                                    <p><strong>Status:</strong> <?php echo ucfirst($archivedFiles['task_status']); ?></p>

                                    <div class="modify-task">
                                        <!-- Button to trigger modal for this task -->
                                        <button type="submit" data-task-id="<?php echo $archivedFiles['task_id']; ?>" class="edit-btn">Edit</button>

                                        <!-- Archive Task Button -->
                                        <form action="phpfiles/update.php" method="post" class="archive-form">
                                        <input type="hidden" name="id" value="<?php echo $archivedFiles['task_id']; ?>">
                                        <input type="hidden" name="action" value="archive">
                                        <button type="submit" class="archive-btn">Archive</button>
                                        </form>
                                    </div>
                                    
                                    <!-- Hidden modal for each task (using a data attribute) -->
                                    <div class="modal" data-modal-id="<?php echo $archivedFiles['task_id']; ?>">
                                        <div class="modal-content">
                                            <span class="close" data-modal-id="<?php echo $archivedFiles['task_id']; ?>">&times;</span>

                                            <form action="phpfiles/update.php" method="post">
                                                <input type="hidden" name="id" value="<?php echo $archivedFiles['task_id']; ?>">

                                                <label for="task_name">Task Name:</label>
                                                <input type="text" name="task_name" value="<?php echo $archivedFiles['task_name']; ?>" required>

                                                <label for="task_description">Task Description:</label>
                                                <textarea name="task_description"><?php echo $archivedFiles['task_description']; ?></textarea>

                                                <label for="due_date">Due Date:</label>
                                                <input type="date" name="due_date" value="<?php echo $archivedFiles['due_date']; ?>" min="<?php echo date('Y-m-d'); ?>" required>

                                                <label for="statusSelect">Status:</label>
                                                <select name="statusSelect" required>
                                                    <option value="pending" <?php if ($archivedFiles['task_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                                    <option value="inProgress" <?php if ($archivedFiles['task_status'] == 'inProgress') echo 'selected'; ?>>In progress</option>
                                                    <option value="completed" <?php if ($archivedFiles['task_status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                                </select>

                                                <!-- Is Important? -->
                                                <div>
                                                    <label for="isImportant">Do you want to mark this tasks as Important?</label>
                                                    <input type="checkbox" id="isImportant" name="isImportant">
                                                </div>

                                                <div class="modal-buttons">
                                                    <button type="submit" name="action" value="update" class="submit">Submit</button>
                                                    <button type="button" class="cancel-btn" data-modal-id="<?php echo $archivedFiles['task_id']; ?>">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </main>

    <footer></footer>

    <script src="Resources/app.js"></script>
</body>
</html>