<?php 
session_start();
require 'classes/connection.php';

// Initialize search variable
$searchEmail = '';

if (isset($_GET['search_email'])) {
    $searchEmail = $conn->real_escape_string($_GET['search_email']);
    $results = $conn->query("SELECT * FROM student WHERE gmail LIKE '%$searchEmail%'");
} else {
    $results = $conn->query("SELECT * FROM student");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    body { 
        padding: 20px; 
        font-family: Arial, sans-serif;
    }
    .print-btn, .export-btn { margin-bottom: 20px; }
    table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #667eea; color: white; }
    tr:hover { background-color: #f1f1f1; }
    .refresh-btn { background-color: white; color: black; margin-left: 10px; }
</style>
</head>
<body>

<div class="container">
    <h2>Exam Results</h2>

    <button class="btn btn-primary" onclick="window.location.href='form.php'">
        <i class="fas fa-home"></i> Exam
    </button>
    
    <button class="btn btn-primary print-btn" onclick="printReport()">
        <i class="fas fa-print"></i> Print Report
    </button>

    <button class="btn btn-success export-btn" onclick="window.location.href='export_excel.php'">
        <i class="fas fa-file-excel"></i> Export to Excel
    </button>

    <!-- Search Bar for Exam Results -->
    <h3>Search Student Results</h3>
    <form method="GET" action="results.php">
        <input type="text" name="search_email" placeholder="Search by email..." value="<?php echo htmlspecialchars($searchEmail); ?>" required>
        <input type="submit" value="Search" class="btn btn-default">
        <a href="results.php" class="btn btn-secondary refresh-btn">Refresh</a>
    </form>

    <h3>Results</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>LRN</th>
                <th>Strand</th>
                <th>Phone</th>
                <th>Score</th>
                <th>Exam Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['gmail']); ?></td>
                    <td><?php echo htmlspecialchars($row['lrn']); ?></td>
                    <td><?php echo htmlspecialchars($row['strand']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo $row['score'] !== null ? $row['score'] : 'Not taken'; ?></td>
                    <td><?php echo $row['exam_date'] !== null ? date('M d, Y h:i A', strtotime($row['exam_date'])) : 'Not taken'; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function printReport() {
        window.print();
    }
</script>

</body>
</html>
