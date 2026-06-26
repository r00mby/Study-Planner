<?php
error_reporting(0);

@include('db.php');

$message = "";
$task = [];
$task_id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch task details if ID is provided
if ($task_id && isset($conn)) {
    $sql = "SELECT * FROM tasks WHERE id = '$task_id'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $task = mysqli_fetch_assoc($result);
    } else {
        $message = "<div class='alert error'>❌ Task not found</div>";
    }
} else {
    $message = "<div class='alert error'>⚠️ Invalid task ID or database not connected</div>";
}

// Handle form submission for updating task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($conn)) {
    $subject    = $_POST['subject'];
    $task_title = $_POST['task_title'];
    $due_date   = $_POST['due_date'];
    $priority   = $_POST['priority'];
    $status     = $_POST['status'];
    $category   = $_POST['category'];
    $progress   = $_POST['progress'];
    $notes      = $_POST['notes'];

    $sql = "UPDATE tasks SET 
            subject = '$subject', 
            task_title = '$task_title', 
            due_date = '$due_date', 
            priority = '$priority', 
            status = '$status', 
            category = '$category', 
            progress = '$progress', 
            notes = '$notes' 
            WHERE id = '$task_id'";

    if (mysqli_query($conn, $sql)) {
        $message = "<div class='alert success'>✅ Task updated successfully</div>";
        // Refresh task data
        $sql = "SELECT * FROM tasks WHERE id = '$task_id'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $task = mysqli_fetch_assoc($result);
        }
    } else {
        $message = "<div class='alert error'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المهمة - Study Planner</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(to bottom right, #ffffff, #f3edff, #e9ddff);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        .main-card {
            background: #ffffff;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(106, 27, 154, 0.1);
            border-top: 6px solid #7e57c2;
            margin: 30px auto;
            flex: 1;
        }

        h2 {
            text-align: center;
            color: #4a148c;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #6a1b9a;
            font-weight: bold;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1c4e9;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #7e57c2;
            box-shadow: 0 0 5px rgba(126, 87, 194, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #7e57c2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #5e35b1;
        }

        .btn-back {
            background-color: #999;
            margin-top: 10px;
        }

        .btn-back:hover {
            background-color: #777;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 13px;
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

    <div class="main-card">
        <h2>Edit Task ✏️</h2>

        <?php if ($message) echo $message; ?>

        <?php if (!empty($task)): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Course Name</label>
                    <input type="text" name="subject" required value="<?php echo htmlspecialchars($task['subject']); ?>">
                </div>

                <div class="form-group">
                    <label>Task Title</label>
                    <input type="text" name="task_title" required value="<?php echo htmlspecialchars($task['task_title']); ?>">
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" required value="<?php echo $task['due_date']; ?>">
                </div>

                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority">
                        <option value="High" <?php echo ($task['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
                        <option value="Medium" <?php echo ($task['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="Low" <?php echo ($task['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Pending" <?php echo ($task['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo ($task['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo ($task['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Classification</label>
                    <select name="category">
                        <option value="Assignment" <?php echo ($task['category'] == 'Assignment') ? 'selected' : ''; ?>>Assignment</option>
                        <option value="Lab" <?php echo ($task['category'] == 'Lab') ? 'selected' : ''; ?>>Lab</option>
                        <option value="Project" <?php echo ($task['category'] == 'Project') ? 'selected' : ''; ?>>Project</option>
                        <option value="Midterm" <?php echo ($task['category'] == 'Midterm') ? 'selected' : ''; ?>>Midterm</option>
                        <option value="final exam" <?php echo ($task['category'] == 'final exam') ? 'selected' : ''; ?>>Final Exam</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Achievement %</label>
                    <input type="number" name="progress" min="0" max="100" value="<?php echo $task['progress']; ?>">
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="2"><?php echo htmlspecialchars($task['notes']); ?></textarea>
                </div>

                <button type="submit">Update Task</button>
            </form>

            <form action="view_tasks.php" method="GET">
                <button type="submit" class="btn-back">Back to Tasks</button>
            </form>
        <?php else: ?>
            <p style="text-align: center; color: #999;">Unable to load task. Please try again.</p>
            <form action="view_tasks.php" method="GET">
                <button type="submit" class="btn-back">Back to Tasks</button>
            </form>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>© 2026 Study Track | To Do List Study Planner</p>
    </footer>

</body>
</html>
