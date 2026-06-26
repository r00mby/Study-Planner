<?php
error_reporting(0);

@include('db.php');

$task_id = isset($_GET['id']) ? $_GET['id'] : null;
$message = "";

// Delete task from database
if ($task_id && isset($conn)) {
    $sql = "DELETE FROM tasks WHERE id = '$task_id'";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect to view_tasks.php with success message
        header("Location: view_tasks.php?deleted=1");
        exit();
    } else {
        $message = "<div class='alert error'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
} else {
    $message = "<div class='alert error'>⚠️ Invalid task ID or database not connected</div>";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حذف المهمة - Study Planner</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(to bottom right, #ffffff, #f3edff, #e9ddff);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .main-card {
            background: #ffffff;
            width: 90%;
            max-width: 400px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(106, 27, 154, 0.1);
            border-top: 6px solid #e53935;
            text-align: center;
        }

        h2 {
            color: #4a148c;
            margin-bottom: 20px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .error {
            background-color: #ffeef0;
            color: #c62828;
        }

        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        a, button {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-back {
            background-color: #7e57c2;
            color: white;
        }

        .btn-back:hover {
            background-color: #5e35b1;
        }
    </style>
</head>
<body>

    <div class="main-card">
        <h2>Delete Task ❌</h2>

        <?php if ($message) echo $message; ?>

        <p style="color: #666; margin-bottom: 20px;">Redirecting to tasks list...</p>

        <div class="button-group">
            <a href="view_tasks.php" class="btn-back">Back to Tasks</a>
        </div>
    </div>

    <script>
        // Auto redirect after 2 seconds
        setTimeout(function() {
            window.location.href = 'view_tasks.php';
        }, 2000);
    </script>

</body>
</html>
