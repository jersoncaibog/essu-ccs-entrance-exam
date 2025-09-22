<?php
session_start();
include "classes/connection.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["student-login"])) {
        $first_name = $_POST["student-first-name"];
        $middle_name = $_POST["student-middle-name"];
        $last_name = $_POST["student-last-name"];
        $suffix = $_POST["student-suffix"];
        $strand = $_POST["student-strand"];
        $phone = $_POST["student-phone"];
        $lrn = $_POST["student-lrn"];
        $gender = $_POST["student-gender"];
        $address = $_POST["student-address"];

        if (!empty($first_name) && !empty($last_name) && !empty($strand) && !empty($phone) && !empty($lrn) && !empty($gender) && !empty($address)) {
            // Check if student has already taken the exam
            $check_exam_stmt = $conn->prepare("SELECT score FROM student WHERE lrn = ?");
            $check_exam_stmt->bind_param("s", $lrn);
            $check_exam_stmt->execute();
            $exam_result = $check_exam_stmt->get_result();
            
            if ($exam_result->num_rows > 0) {
                $student_data = $exam_result->fetch_assoc();
                if ($student_data['score'] !== NULL) {
                    echo "<script>alert('You have already taken the exam. You cannot take it again.'); window.location.href = 'index.php';</script>";
                    exit();
                }
            }

            // First check if student exists
            $check_stmt = $conn->prepare("SELECT id FROM student WHERE lrn = ?");
            $check_stmt->bind_param("s", $lrn);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Student exists, update their information
                $stmt = $conn->prepare("UPDATE student SET first_name = ?, middle_name = ?, last_name = ?, suffix = ?, strand = ?, phone = ?, gender = ?, address = ? WHERE lrn = ?");
                $stmt->bind_param("sssssssss", $first_name, $middle_name, $last_name, $suffix, $strand, $phone, $gender, $address, $lrn);
            } else {
                // Student doesn't exist, insert new record
                $stmt = $conn->prepare("INSERT INTO student (first_name, middle_name, last_name, suffix, strand, phone, lrn, gender, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $first_name, $middle_name, $last_name, $suffix, $strand, $phone, $lrn, $gender, $address);
            }

            if ($stmt->execute()) {
                $_SESSION["studentLoggedIn"] = true;
                $_SESSION["studentFirstName"] = $first_name;
                $_SESSION["studentMiddleName"] = $middle_name;
                $_SESSION["studentLastName"] = $last_name;
                $_SESSION["studentSuffix"] = $suffix;
                $_SESSION["studentStrand"] = $strand;
                $_SESSION["studentPhone"] = $phone;
                $_SESSION["studentLrn"] = $lrn;
                $_SESSION["studentGender"] = $gender;
                $_SESSION["studentAddress"] = $address;

                // Redirect with URL parameters
                $redirectUrl = 'form.php?first_name=' . urlencode($first_name) . 
                              '&middle_name=' . urlencode($middle_name) . 
                              '&last_name=' . urlencode($last_name) . 
                              '&suffix=' . urlencode($suffix) . 
                              '&strand=' . urlencode($strand) . 
                              '&phone=' . urlencode($phone) . 
                              '&lrn=' . urlencode($lrn) . 
                              '&gender=' . urlencode($gender) . 
                              '&address=' . urlencode($address);
                header("Location: " . $redirectUrl);
                exit();
            } else {
                echo "<script>alert('Error saving student details.');</script>";
            }

            $stmt->close();
            $check_stmt->close();
        } else {
            echo "<script>alert('Please fill in all required fields.');</script>";
        }
    }

    if (isset($_POST["admin-login"])) {
        $username = $_POST["admin-username"];
        $password = $_POST["admin-password"];

        // Use prepared statement for admin login
        $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["adminLoggedIn"] = true;
            echo "<script>alert('Admin login successful! Redirecting...'); window.location.href = 'admin-dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid admin credentials!');</script>";
        }
        $stmt->close();
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrance Exam Login</title>
    <link rel="stylesheet" href="assets/form.css">
</head>
<body>
    <div class="container" id="student-container">
        <div class="logo-container">
            <img src="images/IT.png" alt="Logo" class="logo">
            <h2>Student Entrance Examination</h2>
        </div>
        <form id="student-form" method="POST">
            <div class="name-group">
                <div class="input-group">
                    <label for="student-first-name">First Name</label>
                    <input type="text" name="student-first-name" id="student-first-name" required>
                </div>
                <div class="input-group">
                    <label for="student-middle-name">Middle Name</label>
                    <input type="text" name="student-middle-name" id="student-middle-name">
                </div>
                <div class="input-group">
                    <label for="student-last-name">Last Name</label>
                    <input type="text" name="student-last-name" id="student-last-name" required>
                </div>
                <div class="input-group">
                    <label for="student-suffix">Suffix (Jr., Sr., etc.)</label>
                    <input type="text" name="student-suffix" id="student-suffix">
                </div>
            </div>
            <div class="input-group">
                <label for="student-strand">Strand</label>
                <select name="student-strand" id="student-strand" required>
                    <option value=""></option>
                    <option value="STEM">STEM</option>
                    <option value="HUMSS">HUMSS</option>
                    <option value="Automotive">Automotive</option>
                    <option value="ICT">ICT</option>
                    <option value="GAS">GAS</option>
                    <option value="ABM">ABM</option>
                    <option value="TVL">TVL</option>
                    <option value="SPORTS">SPORTS</option>
                    <option value="ARTS & DESIGN">ARTS & DESIGN</option>
                </select>
            </div>
            <div class="input-group">
                <label for="student-gender">Gender</label>
                <select name="student-gender" id="student-gender" required>
                    <option value=""></option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="input-group">
                <label for="student-lrn">LRN (12 digits)</label>
                <input type="text" name="student-lrn" id="student-lrn" pattern="^\d{12}$" title="Please enter a valid 12-digit LRN" required>
            </div>
            <div class="input-group">
                <label for="student-phone">Phone Number (11 digits)</label>
                <input type="text" name="student-phone" id="student-phone" pattern="^\d{11}$" title="Please enter a valid 11-digit phone number" required>
            </div>
            <div class="input-group">
                <label for="student-address">Address</label>
                <input type="text" name="student-address" id="student-address" required>
            </div>
            <button id="start-exam-btn" type="submit" name="student-login">Start Exam</button>
        </form>
    </div>
    
    <div class="admin-login-container">
        <button id="admin-login-btn">Login as Admin</button>
    </div>

    <!-- Admin Login Pop-up -->
    <div id="admin-popup" class="popup">
        <div class="popup-content">
            <span class="close-btn" id="close-admin-popup">&times;</span>
            <div class="logo-container">
                <img src="images/ESSU.png" alt="Logo" class="logo">
                <h2>Admin Login</h2>
            </div>
            <form id="admin-form" method="POST">
                <div class="input-group">
                    <label for="admin-username">Username</label>
                    <input type="text" name="admin-username" id="admin-username" required>
                </div>
                <div class="input-group">
                    <label for="admin-password">Password</label>
                    <input type="password" name="admin-password" id="admin-password" required>
                </div>
                <button type="submit" name="admin-login">Login</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
