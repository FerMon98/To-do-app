<header>
    <h1>A treballar!</h1>
    <p><strong>Recorda revisar les teves tasques diàries i actualitzar-les a mesura que vagis fent progressos.</strong></p>
</header>

<main>
    <section id="addTaskSection">
        <div id="taskToday">
            <h2>Tasques per avui:</h2>
            <ul id="todaysTasksUl">
                    <?php 
                    $tasksDueToday = 0; // Counter for tasks due today

                    // Loop through tasks to display those due today
                    foreach ($resultado as $fila) : 
                        if ($fila['due_date'] === date('Y-m-d')) :
                            $tasksDueToday++; // Increment the counter for each task due today
                    ?>
                            <li id="task-<?php echo $fila['task_id']; ?>">
                                <div>
                                    <h3><?php echo $fila['task_name']; ?></h3>
                                    <span class="closeDueToday" data-task-id="task-<?php echo $fila['task_id']; ?>"> x </span>
                                </div>

                                <!-- Check if the task is important and display the label -->
                                <?php if ($fila['is_important'] === 1) : ?>
                                    <span class="important">¡Importante!</span>
                                <?php endif; ?>

                                <p class="description"><?php echo $fila['task_description']; ?></p>
                                <p><strong>Estat: </strong>
                                    <?php 
                                        if ($fila['task_status'] === 'pending') {
                                            echo 'Pendent.'; 
                                        } elseif ($fila['task_status'] === 'inProgress') {
                                            echo 'En procés.'; 
                                        } elseif ($fila['task_status'] === 'completed') {
                                            echo 'Ja has completat aquesta tasca.'; 
                                        } elseif ($fila['task_status'] === 'archived') {
                                            echo 'Aquesta tasca ja ha estat arxivada.';
                                        }
                                    ?>
                                </p>
                            </li>
                    <?php 
                        endif; // End if due today
                    endforeach; // End foreach

                    // If no tasks were due today, display the "No tasks due" message
                    if ($tasksDueToday === 0) : ?>
                        <li>No hi ha tasques per avui.</li>
                    <?php endif; ?>
                </ul>
        </div>

        <details>
            <summary>Afegir una Nova Tasca:</summary>
            <form id="taskForm">
                <input type="text" id="titleInput" placeholder="Títol">
                <input type="text" id="taskDescriptionInput" placeholder="Descripció">
                <select name="estateSelect" id="estateSelect">
                    <option value="pending" selected>Pendent</option>
                    <option value="inProcess">En proceso</option>
                    <option value="completed">Completada</option>
                </select>
                <label for="dueDateInput">Data de venciment:</label>
                <input type="date" id="dueDateInput" name="dueDateInput" min="<?php echo date('Y-m-d'); ?>">

                <div>
                    <label for="isImportant">¿Vols marcar aquesta tasca com a Important??</label>
                    <input type="checkbox" id="isImportant" name="isImportant">
                </div>
                
                <button type="submit">Afegir Tasca</button>
            </form>
        </details>
        
    </section>

    <hr>

    <section id="displayTasksSection">
        <div class="carousel-content">
            <div>
                <h2>Tasques Pendents:</h2>
                <ul id="pendingTasksUl">
                    <?php foreach ($resultado as $pendingFiles) : ?>
                        <?php if ($pendingFiles['task_status'] === 'pending') : ?>
                            <li>
                                <h3><?php echo $pendingFiles['task_name']; ?></h3>
                                
                                <!-- Check if the task is important and display the label -->
                                <?php if ($pendingFiles['is_important'] === 1) : ?>
                                    <span class="important">¡Importante!</span>
                                <?php endif; ?>
                                
                                <!-- Task Description -->
                                <p  class="description" style="background-color: rgba(255, 255, 255, 0.60); color: black; border-radius: 15px; padding: 0.5rem">
                                    <?php echo $pendingFiles['task_description']; ?>
                                </p>

                                <!-- Task Due Date -->
                                <p><strong>Data de venciment:</strong> <?php echo $pendingFiles['due_date']; ?></p>

                                <!-- Task Status -->
                                <p><strong>Estat:</strong> <?php echo ucfirst($pendingFiles['task_status']); ?></p>

                                <div class="modify-task">
                                    <!-- Button to trigger modal for editing this task -->
                                    <button type="submit" data-task-id="<?php echo $pendingFiles['task_id']; ?>" class="edit-btn">Editar</button>

                                    <!-- Archive Task Button -->
                                    <form action="./phpfiles/update.php" method="post" class="archive-form">
                                        <input type="hidden" name="id" value="<?php echo $pendingFiles['task_id']; ?>">
                                        <input type="hidden" name="action" value="archive">
                                        <button type="submit" class="archive-btn">Arxivar</button>
                                    </form>
                                </div>
                                
                                <!-- Hidden modal for editing the task -->
                                <div class="modal" data-modal-id="<?php echo $pendingFiles['task_id']; ?>">
                                    <div class="modal-content">
                                        <span class="close" data-modal-id="<?php echo $pendingFiles['task_id']; ?>">&times;</span>

                                        <form action="./phpfiles/update.php" method="post">
                                            <input type="hidden" name="id" value="<?php echo $pendingFiles['task_id']; ?>">

                                            <!-- Task Name Input -->
                                            <label for="task_name">Títol:</label>
                                            <input type="text" name="task_name" value="<?php echo $pendingFiles['task_name']; ?>" required>

                                            <!-- Task Description Input -->
                                            <label for="task_description">Descripció:</label>
                                            <textarea name="task_description" required><?php echo $pendingFiles['task_description']; ?></textarea>

                                            <!-- Due Date Input -->
                                            <label for="due_date">Data de venciment:</label>
                                            <input type="date" name="due_date" value="<?php echo $pendingFiles['due_date']; ?>" min="<?php echo date('Y-m-d'); ?>"required>

                                            <!-- Status Select -->
                                            <label for="statusSelect">Estat:</label>
                                            <select name="statusSelect" required>
                                                <option value="pending" <?php if ($pendingFiles['task_status'] == 'pending') echo 'selected'; ?>>Pendent</option>
                                                <option value="inProgress" <?php if ($pendingFiles['task_status'] == 'inProgress') echo 'selected'; ?>>En procés</option>
                                                <option value="completed" <?php if ($pendingFiles['task_status'] == 'completed') echo 'selected'; ?>>Completada</option>
                                            </select>

                                            <!-- Is Important? -->
                                            <div>
                                                <label for="isImportant">¿Quiéres marcar esta tarea cómo importante?</label>
                                                <input type="checkbox" id="isImportant" name="isImportant">
                                            </div>

                                            <!-- Modal buttons -->
                                            <div class="modal-buttons">
                                                <button type="submit" name="action" value="update" class="submit">Actualitzar</button>
                                                <button type="button" class="cancel-btn" data-modal-id="<?php echo $pendingFiles['task_id']; ?>">Cancel·lar</button>
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
                <h2>En Proceso:</h2>
                <ul id="inProcessTasks">
                    <?php foreach ($resultado as $inProgressTasks) : ?>
                        <?php if ($inProgressTasks['task_status'] === 'inProgress') : ?>
                            <li>
                                <h3><?php echo $inProgressTasks['task_name']; ?></h3>
                                
                                <!-- Check if the task is important and display the label -->
                                <?php if ($inProgressTasks['is_important'] === 1) : ?>
                                    <span class="important">¡Importante!</span>
                                <?php endif; ?>
                                
                                <!-- Task Description -->
                                <p  class="description" style="background-color: rgba(255, 255, 255, 0.60); color: black; border-radius: 15px; padding: 0.5rem">
                                    <?php echo $inProgressTasks['task_description']; ?>
                                </p>

                                <!-- Task Due Date -->
                                <p><strong>Data de venciment:</strong> <?php echo $inProgressTasks['due_date']; ?></p>

                                <!-- Task Status -->
                                <p><strong>Estat:</strong> <?php echo ucfirst($inProgressTasks['task_status']); ?></p>

                                <div class="modify-task">
                                    <!-- Button to trigger modal for editing this task -->
                                    <button type="submit" data-task-id="<?php echo $inProgressTasks['task_id']; ?>" class="edit-btn">Editar</button>

                                    <!-- Archive Task Button -->
                                    <form action="./phpfiles/update.php" method="post" class="archive-form">
                                        <input type="hidden" name="id" value="<?php echo $inProgressTasks['task_id']; ?>">
                                        <input type="hidden" name="action" value="archive">
                                        <button type="submit" class="archive-btn">Arxivar</button>
                                    </form>
                                </div>
                                
                                <!-- Hidden modal for editing the task -->
                                <div class="modal" data-modal-id="<?php echo $inProgressTasks['task_id']; ?>">
                                    <div class="modal-content">
                                        <span class="close" data-modal-id="<?php echo $inProgressTasks['task_id']; ?>">&times;</span>

                                        <form action="./phpfiles/update.php" method="post">
                                            <input type="hidden" name="id" value="<?php echo $inProgressTasks['task_id']; ?>">

                                            <!-- Task Name Input -->
                                            <label for="task_name">Títol:</label>
                                            <input type="text" name="task_name" value="<?php echo $inProgressTasks['task_name']; ?>" required>

                                            <!-- Task Description Input -->
                                            <label for="task_description">Descripció:</label>
                                            <textarea name="task_description" required><?php echo $inProgressTasks['task_description']; ?></textarea>

                                            <!-- Due Date Input -->
                                            <label for="due_date">Data de venciment:</label>
                                            <input type="date" name="due_date" value="<?php echo $inProgressTasks['due_date']; ?>" min="<?php echo date('Y-m-d'); ?>"required>

                                            <!-- Status Select -->
                                            <label for="statusSelect">Estat:</label>
                                            <select name="statusSelect" required>
                                                <option value="pending" <?php if ($inProgressTasks['task_status'] == 'pending') echo 'selected'; ?>>Pendent</option>
                                                <option value="inProgress" <?php if ($inProgressTasks['task_status'] == 'inProgress') echo 'selected'; ?>>En procés</option>
                                                <option value="completed" <?php if ($inProgressTasks['task_status'] == 'completed') echo 'selected'; ?>>Completada</option>
                                            </select>

                                            <!-- Is Important? -->
                                            <div>
                                                <label for="isImportant">¿Quiéres marcar esta tarea cómo importante?</label>
                                                <input type="checkbox" id="isImportant" name="isImportant">
                                            </div>

                                            <!-- Modal buttons -->
                                            <div class="modal-buttons">
                                                <button type="submit" name="action" value="update" class="submit">Actualitzar</button>
                                                <button type="button" class="cancel-btn" data-modal-id="<?php echo $inProgressTasks['task_id']; ?>">Cancel·lar</button>
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
                <h2>Tasques Completades:</h2>
                <ul id="completedTasksUl">
                <?php foreach ($resultado as $completedTasks) : ?>
                        <?php if ($completedTasks['task_status'] === 'completed') : ?>
                            <li>
                                <h3><?php echo $completedTasks['task_name']; ?></h3>
                                
                                <!-- Check if the task is important and display the label -->
                                <?php if ($completedTasks['is_important'] === 1) : ?>
                                    <span class="important">¡Importante!</span>
                                <?php endif; ?>
                                
                                <!-- Task Description -->
                                <p  class="description" style="background-color: rgba(255, 255, 255, 0.60); color: black; border-radius: 15px; padding: 0.5rem">
                                    <?php echo $completedTasks['task_description']; ?>
                                </p>

                                <!-- Task Due Date -->
                                <p><strong>Data de venciment:</strong> <?php echo $completedTasks['due_date']; ?></p>

                                <!-- Task Status -->
                                <p><strong>Estat:</strong> <?php echo ucfirst($completedTasks['task_status']); ?></p>

                                <div class="modify-task">
                                    <!-- Button to trigger modal for editing this task -->
                                    <button type="submit" data-task-id="<?php echo $completedTasks['task_id']; ?>" class="edit-btn">Editar</button>

                                    <!-- Archive Task Button -->
                                    <form action="./phpfiles/update.php" method="post" class="archive-form">
                                        <input type="hidden" name="id" value="<?php echo $completedTasks['task_id']; ?>">
                                        <input type="hidden" name="action" value="archive">
                                        <button type="submit" class="archive-btn">Arxivar</button>
                                    </form>
                                </div>
                                
                                <!-- Hidden modal for editing the task -->
                                <div class="modal" data-modal-id="<?php echo $completedTasks['task_id']; ?>">
                                    <div class="modal-content">
                                        <span class="close" data-modal-id="<?php echo $completedTasks['task_id']; ?>">&times;</span>

                                        <form action="./phpfiles/update.php" method="post">
                                            <input type="hidden" name="id" value="<?php echo $completedTasks['task_id']; ?>">

                                            <!-- Task Name Input -->
                                            <label for="task_name">Títol:</label>
                                            <input type="text" name="task_name" value="<?php echo $completedTasks['task_name']; ?>" required>

                                            <!-- Task Description Input -->
                                            <label for="task_description">Descripció:</label>
                                            <textarea name="task_description" required><?php echo $completedTasks['task_description']; ?></textarea>

                                            <!-- Due Date Input -->
                                            <label for="due_date">Data de venciment:</label>
                                            <input type="date" name="due_date" value="<?php echo $completedTasks['due_date']; ?>" min="<?php echo date('Y-m-d'); ?>"required>

                                            <!-- Status Select -->
                                            <label for="statusSelect">Estat:</label>
                                            <select name="statusSelect" required>
                                                <option value="pending" <?php if ($completedTasks['task_status'] == 'pending') echo 'selected'; ?>>Pendent</option>
                                                <option value="inProgress" <?php if ($completedTasks['task_status'] == 'inProgress') echo 'selected'; ?>>En procés</option>
                                                <option value="completed" <?php if ($completedTasks['task_status'] == 'completed') echo 'selected'; ?>>Completada</option>
                                            </select>

                                            <!-- Is Important? -->
                                            <div>
                                                <label for="isImportant">¿Quiéres marcar esta tarea cómo importante?</label>
                                                <input type="checkbox" id="isImportant" name="isImportant">
                                            </div>

                                            <!-- Modal buttons -->
                                            <div class="modal-buttons">
                                                <button type="submit" name="action" value="update" class="submit">Actualitzar</button>
                                                <button type="button" class="cancel-btn" data-modal-id="<?php echo $completedTasks['task_id']; ?>">Cancel·lar</button>
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

    <section id="archived">
        <div>
            <h2>Tasques Arxivadas:</h2>
            <ul id="archiveTasksUl">
                <?php foreach ($resultado as $archivedFiles) : ?>
                    <?php if ($archivedFiles['task_status'] === 'archived') : ?>

                        <li>
                            <h3><?php echo $archivedFiles['task_name']; ?></h3>
                            <p style="background-color: rgba(255, 255, 255, 0.60); color: black; border-radius: 15px; padding: 0.5rem">
                                <?php echo $archivedFiles['task_description']; ?>
                            </p>
                            <p><strong>Data de venciment: </strong> <?php echo $archivedFiles['due_date']; ?></p>
                            <p><strong>Días para eliminar tarea: <?php 
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
                                include './phpfiles/delete.php';

                                // Display days remaining if the task hasn't been modified and is due within the next 7 days
                                echo $daysRemainingText; 
                            ?></strong></p> 
                            
                            <p><strong>Estat:</strong> Arxivada</p>

                            <div class="modify-task">
                                <!-- Button to trigger modal for this task -->
                                <button type="submit" data-task-id="<?php echo $archivedFiles['task_id']; ?>" class="edit-btn">Editar</button>

                                <!-- Archive Task Button -->
                                <form action="phpfiles/update.php" method="post" class="archive-form">
                                <input type="hidden" name="id" value="<?php echo $archivedFiles['task_id']; ?>">
                                <input type="hidden" name="action" value="archive">
                                <button type="submit" class="archive-btn">Arxivar</button>
                                </form>
                            </div>
                            
                            <!-- Hidden modal for each task (using a data attribute) -->
                            <div class="modal" data-modal-id="<?php echo $archivedFiles['task_id']; ?>">
                                <div class="modal-content">
                                    <span class="close" data-modal-id="<?php echo $archivedFiles['task_id']; ?>">&times;</span>

                                    <form action="phpfiles/update.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $archivedFiles['task_id']; ?>">

                                        <label for="task_name">Títol:</label>
                                        <input type="text" name="task_name" value="<?php echo $archivedFiles['task_name']; ?>" required>

                                        <label for="task_description">Descripció:</label>
                                        <textarea name="task_description"><?php echo $archivedFiles['task_description']; ?></textarea>

                                        <label for="due_date">Data de venciment:</label>
                                        <input type="date" name="due_date" value="<?php echo $archivedFiles['due_date']; ?>" min="<?php echo date('Y-m-d'); ?>" required>

                                        <label for="statusSelect">Estat:</label>
                                        <select name="statusSelect" required>
                                            <option value="pending" <?php if ($archivedFiles['task_status'] == 'pending') echo 'selected'; ?>>Pendent</option>
                                            <option value="inProgress" <?php if ($archivedFiles['task_status'] == 'inProgress') echo 'selected'; ?>>En procés</option>
                                            <option value="completed" <?php if ($archivedFiles['task_status'] == 'completed') echo 'selected'; ?>>Completada</option>
                                        </select>

                                        <!-- Is Important? -->
                                        <div>
                                            <label for="isImportant">¿Quiéres marcar esta tarea cómo importante?</label>
                                            <input type="checkbox" id="isImportant" name="isImportant">
                                        </div>

                                        <div class="modal-buttons">
                                            <button type="submit" name="action" value="update" class="submit">Enviar</button>
                                            <button type="button" class="cancel-btn" data-modal-id="<?php echo $archivedFiles['task_id']; ?>">Cancel·lar</button>
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
