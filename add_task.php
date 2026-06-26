<?php
error_reporting(0); 
@include('db.php'); 

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($conn)) {
        $subject    = $_POST['subject'];
        $task_title = $_POST['task_title'];
        $due_date   = $_POST['due_date'];
        $priority   = $_POST['priority'];
        $status     = $_POST['status'];
        $category   = $_POST['category'];
        $progress   = $_POST['progress'];
        $notes      = $_POST['notes'];

        $sql = "INSERT INTO tasks (subject, task_title, due_date, priority, status, category, progress, notes) 
                VALUES ('$subject', '$task_title', '$due_date', '$priority', '$status', '$category', '$progress', '$notes')";

        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert success'>✅ Save the task successfully</div>";
        } else {
            $message = "<div class='alert error'>❌ Error " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert error'>⚠️ Database is not connected</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task - Study Track</title>

    <!-- نفس ستايل الموقع -->
    <link rel="stylesheet" href="style.css">

    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .main-card { 
            background: #ffffff; 
            width: 90%; 
            max-width: 450px; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 8px 20px rgba(106, 27, 154, 0.1); 
            border-top: 6px solid #7e57c2;
        }

        h2 { text-align: center; color: #4a148c; margin-bottom: 25px; }

        .form-group { margin-bottom: 15px; }

        label { display: block; margin-bottom: 5px; color: #6a1b9a; font-weight: bold; font-size: 14px; }

        input, select, textarea { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #d1c4e9; 
            border-radius: 8px; 
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
        }

        button:hover { background-color: #5e35b1; }

        .alert { padding: 10px; margin-bottom: 15px; border-radius: 8px; text-align: center; font-size: 13px; font-weight: bold; }
        .success { background-color: #e8f5e9; color: #2e7d32; }
        .error { background-color: #ffeef0; color: #c62828; }
    </style>
</head>

<body>

<!-- NAVBAR -->
<header class="navbar">
    <div class="logo">Study Track</div>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="view_tasks.php">Tasks</a>
        <a href="add_task.php">Add Task</a>
        <a href="about.php">About</a>
    </nav>
</header>

<!-- FORM -->
<div class="container">
<div class="main-card">

    <h2>New Task 📝</h2>

    <?php if ($message) echo $message; ?>

    <form method="POST">

        <div class="form-group">
            <label>Course Name</label>
            <input type="text" name="subject" required placeholder="Example: Software Engineering">
        </div>

        <div class="form-group">
            <label>Task Title</label>
            <input type="text" name="task_title" required placeholder="Example: Assignment">
        </div>

        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" required>
        </div>

        <div class="form-group">
            <label>Priority</label>
            <select name="priority">
                <option value="High">High</option>
                <option value="Medium" selected>Medium</option>
                <option value="Low">Low</option>
            </select>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
        </div>

        <div class="form-group">
            <label>Classification</label>
            <select name="category">
                <option value="Assignment">Assignment</option>
                <option value="Lab">Lab</option>
                <option value="Project">Project</option>
                <option value="Midterm">Midterm</option>
                <option value="Final Exam">Final Exam</option>
            </select>
        </div>

        <div class="form-group">
            <label>Achievement %</label>
            <input type="number" name="progress" min="0" max="100" value="0">
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" rows="2" placeholder="Add any notes here.."></textarea>
        </div>

        <button type="submit">Save Task</button>

    </form>
</div>
</div>

</body>
</html>