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
    <style>
        body {
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color:rgb(243, 245, 247);
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 500px;
            border: 1px solid rgb(191, 191, 191);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 1rem;
        }
        h2 {
            color:rgb(42, 42, 42);
            margin: 0;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 50px;
        }
        input, select {
            padding: 0.75rem;
            border: 1px solid gray;
            border-radius: 4px;
            font-size: 1rem;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
        }
        button {
            background-color: #1a73e8;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        button:hover {
            background-color: #1557b0;
        }
        #start-exam-btn {
            margin-top: 1rem;
        }
        .admin-login-container {
            width: 400px;
            padding: 2rem;
        }
        #admin-login-btn {
            background-color: rgb(228, 228, 228);
            color: black;
            width: 100%;
            display: block;
        }
        #admin-login-btn:hover {
            width: 100%;
            background-color: rgb(203, 203, 203);
        }
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        .close-btn:hover {
            color: #333;
        }
        .name-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .address-group {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .input-group label {
            font-weight: 500;
            color: #333;
        }
        .name-group .input-group {
            margin-bottom: 0;
        }
        .address-group .input-group {
            margin-bottom: 0;
        }
    </style>
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
