<?php
error_reporting(0);

@include('db.php');

$message = "";
$tasks = [];

if (isset($_GET['deleted'])) {
    $message = "<div class='alert success'>✅ Task deleted successfully</div>";
}

// Fetch all tasks from database
if (isset($conn)) {
    $sql = "SELECT * FROM tasks ORDER BY due_date ASC";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }
    }
} else {
    $message = "<div class='alert error'>⚠️ Database is not connected</div>";
}
?>
<!DOCTYPE html>
<html lang="en" dir="Itr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض المهام - Study Planner</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(to bottom right, #ffffff, #f3edff, #e9ddff);
        }

        .navbar {
            width: 90%;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 20px;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 20px rgba(140, 95, 255, 0.12);
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #7b4dff;
        }

        .nav-links a {
            text-decoration: none;
            color: #5e4b8b;
            margin-left: 20px;
            font-size: 16px;
            font-weight: 600;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #7b4dff;
        }

        .container {
            width: 90%;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(140, 95, 255, 0.1);
        }

        h2 {
            color: #4a148c;
            margin-bottom: 25px;
            text-align: center;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .error {
            background-color: #ffeef0;
            color: #c62828;
        }

        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .task-card {
            background-color: #f9f7ff;
            border-left: 5px solid #7e57c2;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(126, 87, 194, 0.1);
            transition: 0.3s;
        }

        .task-card:hover {
            box-shadow: 0 6px 18px rgba(126, 87, 194, 0.2);
            transform: translateY(-2px);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }

        .task-title {
            font-size: 18px;
            font-weight: bold;
            color: #4a148c;
            margin-bottom: 5px;
        }

        .task-subject {
            font-size: 13px;
            color: #7e57c2;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .priority-high {
            background-color: #ffebee;
            color: #c62828;
        }

        .priority-medium {
            background-color: #fff3e0;
            color: #e65100;
        }

        .priority-low {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 8px;
        }

        .status-pending {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .status-in-progress {
            background-color: #f3e5f5;
            color: #6a1b9a;
        }

        .status-completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .task-info {
            margin: 12px 0;
            font-size: 14px;
            color: #555;
        }

        .task-info-label {
            font-weight: bold;
            color: #7e57c2;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e0d1ff;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            background-color: #7e57c2;
            transition: width 0.3s;
        }

        .task-actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            justify-content: flex-end;
        }

        .btn-action {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-edit {
            background-color: #7e57c2;
            color: white;
        }

        .btn-edit:hover {
            background-color: #5e35b1;
        }

        .btn-delete {
            background-color: #e53935;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c62828;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .add-task-btn {
            text-align: center;
            margin-top: 20px;
        }

        .add-task-btn a {
            background-color: #7e57c2;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }

        .add-task-btn a:hover {
            background-color: #5e35b1;
        }

        .footer {
            text-align: center;
            padding: 25px;
            background-color: #ffffff;
            color: #666;
            margin-top: 40px;
            box-shadow: 0 -4px 15px rgba(140, 95, 255, 0.05);
        }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo">Study Track</div>
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="view_tasks.php">Tasks</a>
            <a href="add_task.php">Add Task</a>
            <a href="about.php">About</a>
        </nav>
    </header>

    <div class="container">
        <h2>📋 My Tasks</h2>

        <?php if ($message) echo $message; ?>

        <?php if (count($tasks) > 0): ?>
            <div class="tasks-grid">
                <?php foreach ($tasks as $task): ?>
                    <div class="task-card">
                        <div class="task-header">
                            <div>
                                <div class="task-title"><?php echo htmlspecialchars($task['task_title']); ?></div>
                                <div class="task-subject"><?php echo htmlspecialchars($task['subject']); ?></div>
                            </div>
                        </div>

                        <div>
                            <span class="priority-badge priority-<?php echo strtolower($task['priority']); ?>">
                                <?php echo $task['priority']; ?>
                            </span>
                            <span class="status-badge status-<?php echo str_replace(' ', '-', strtolower($task['status'])); ?>">
                                <?php echo $task['status']; ?>
                            </span>
                        </div>

                        <div class="task-info">
                            <span class="task-info-label">Category:</span> <?php echo htmlspecialchars($task['category']); ?>
                        </div>

                        <div class="task-info">
                            <span class="task-info-label">Due Date:</span> <?php echo date('Y-m-d', strtotime($task['due_date'])); ?>
                        </div>

                        <div class="task-info">
                            <span class="task-info-label">Progress:</span> <?php echo $task['progress']; ?>%
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?php echo $task['progress']; ?>%;"></div>
                        </div>

                        <?php if (!empty($task['notes'])): ?>
                            <div class="task-info">
                                <span class="task-info-label">Notes:</span> <?php echo htmlspecialchars($task['notes']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="task-actions">
                            <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-action btn-edit">Edit</a>
                            <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>No tasks found. Create your first task!</p>
            </div>
        <?php endif; ?>

        <div class="add-task-btn">
            <a href="add_task.php">+ Add New Task</a>
        </div>
    </div>

    <footer class="footer">
        <p>© 2026 Study Track | To Do List Study Planner</p>
    </footer>

</body>
</html>
