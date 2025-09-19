<?php
session_start();
include "classes/connection.php"; // Database connection
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/admin-dashboard1.css">
    <link rel="stylesheet" href="assets/admin-dashboard-modal.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="sidebar">
        <div class="logo">
            <img src="images/IT.png" alt="Logo">
            <h2>Admin Panel</h2>
        </div>
        <ul>
            <li><a href="#questions"><i class="fas fa-question-circle"></i> Manage Questions</a></li>
            <li><a href="#records"><i class="fas fa-users"></i> Student Records</a></li>
            <li><a href="#export"><i class="fas fa-file-export"></i> Export Data</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1 style="margin-left: 18px;">Welcome, Admin!</h1>

        <hr>

        <section id="questions" class="card">
            <h2><i class="fas fa-question-circle"></i> Entrance Exam Questions</h2>
            <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Options</th>
                        <th>Answer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'classes/connection.php';
                        
                    // FETCHING QUESTIONS FROM DB
                    $sql = "SELECT * FROM admin_quiz ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['question']) . "</td>";
                            echo "<td>";
                            echo "1) " . htmlspecialchars($row['option1']) . "<br>";
                            echo "2) " . htmlspecialchars($row['option2']) . "<br>";
                            echo "3) " . htmlspecialchars($row['option3']) . "<br>";
                            echo "4) " . htmlspecialchars($row['option4']) . "<br>";
                            echo "</td>";
                            echo "<td><strong>" . ucfirst(str_replace("option", "Option ", $row['answer'])) . "</strong></td>";
                            echo "<td>
                            <form action='edit-question.php' method='GET' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' class='edit-btn'>Edit</button>
                            </form>
                            <button class='delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan= '4'>No questions added yet.</td></tr>";
                    }
                    $conn->close();

                    ?>
                </tbody>
            </table>
            <button id="add-question">+ Add Question</button>
        </section>

        <section id="records" class="card">
            <h2><i class="fas fa-users"></i> Student Records</h2>
            
            <!-- Add filter form -->
            <div class="filter-section">
                <form method="GET" action="admin-dashboard.php#records" class="filter-form">
                    <select name="strand_filter" id="strand_filter">
                        <option value="">All Strands</option>
                        <option value="STEM" <?php echo (isset($_GET['strand_filter']) && $_GET['strand_filter'] === 'STEM') ? 'selected' : ''; ?>>STEM</option>
                        <option value="HUMSS" <?php echo (isset($_GET['strand_filter']) && $_GET['strand_filter'] === 'HUMSS') ? 'selected' : ''; ?>>HUMSS</option>
                        <option value="GAS" <?php echo (isset($_GET['strand_filter']) && $_GET['strand_filter'] === 'GAS') ? 'selected' : ''; ?>>GAS</option>
                        <option value="ABM" <?php echo (isset($_GET['strand_filter']) && $_GET['strand_filter'] === 'ABM') ? 'selected' : ''; ?>>ABM</option>
                        <option value="TVL" <?php echo (isset($_GET['strand_filter']) && $_GET['strand_filter'] === 'TVL') ? 'selected' : ''; ?>>TVL</option>
                        <option value="SPORTS" <?php echo (isset($_GET['strand_filter']) && $_GET['strand_filter'] === 'SPORTS') ? 'selected' : ''; ?>>SPORTS</option>
                        <option value="ARTS & DESIGN" <?php echo (isset($_GET['strand_filter']) && $_GET['strand_filter'] === 'ARTS & DESIGN') ? 'selected' : ''; ?>>ARTS & DESIGN</option>
                    </select>
                    <button type="submit" class="filter-btn">Filter</button>
                    <?php if(isset($_GET['strand_filter']) && !empty($_GET['strand_filter'])): ?>
                        <a href="admin-dashboard.php#records" class="clear-filter">Clear Filter</a>
                    <?php endif; ?>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>LRN</th>
                        <th>Strand</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Score</th>
                        <th>Exam Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'classes/connection.php';
                        
                    // FETCHING STUDENT RECORDS FROM DB with strand filter
                    $sql = "SELECT * FROM student";
                    if(isset($_GET['strand_filter']) && !empty($_GET['strand_filter'])) {
                        $strand = $conn->real_escape_string($_GET['strand_filter']);
                        $sql .= " WHERE strand = '$strand'";
                    }
                    $sql .= " ORDER BY exam_date DESC";
                    
                    $result = $conn->query($sql);

                    if($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            // Concatenate name fields
                            $fullName = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']);
                            echo "<td>" . htmlspecialchars($fullName) . "</td>";
                            echo "<td>" . htmlspecialchars($row['lrn']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['strand']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td>" . ($row['score'] !== null ? $row['score'] : 'Not taken') . "</td>";
                            echo "<td>" . ($row['exam_date'] !== null ? date('M d, Y h:i A', strtotime($row['exam_date'])) : 'Not taken') . "</td>";
                            echo "<td>
                            <form action='edit-student.php' method='GET' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' class='edit-btn'>Edit</button>
                            </form>
                            <button class='delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No student records found.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>

        <section id="export" class="card">
            <h2><i class="fas fa-file-export"></i> Export Data</h2>
            <div class="export-buttons">
                <button id="export-questions" onclick="window.location.href='export_questions.php'">
                    <i class="fas fa-file-excel"></i> Export Questions
                </button>
                <button id="export-students" onclick="window.location.href='export_students.php'">
                    <i class="fas fa-file-excel"></i> Export Student Records
                </button>
            </div>
        </section>
    </div>
                    
    <div id="overlay"></div>

    <!-- modal form for adding questions -->
    <section id="add-question-form" class="card">
        <h2><i class="fas fa-plus-circle"></i> Add Question</h2>
        <form action="add-question.php" method="POST">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" required>

            <label for="option1">Option 1:</label>
            <input type="text" id="option1" name="option1" required>

            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" required>

            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" required>

            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" required>

            <label for="answer">Correct Answer:</label>
            <select id="answer" name="answer" required>
                <option value="option1">Option 1</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
                <option value="option4">Option 4</option>
            </select>

            <div class="form-buttons">
                <button type="submit">Submit</button>
                <button type="button" id="cancel-question">Cancel</button>
            </div>
        </form>
    </section>

    <script src="js/delete-question.js"></script>

    <script>
    document.getElementById("add-question").addEventListener("click", function() {
        document.getElementById("add-question-form").style.display = "block";
        document.getElementById("overlay").style.display = "block";
    });

    document.getElementById("cancel-question").addEventListener("click", function() {
        document.getElementById("add-question-form").style.display = "none";
        document.getElementById("overlay").style.display = "none";
    });
    </script>




</body>
</html>
