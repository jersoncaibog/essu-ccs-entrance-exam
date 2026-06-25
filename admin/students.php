<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../index.php');
    exit();
}

require_once __DIR__ . '/../classes/connection.php';

$pageTitle  = 'Student Records';
$activePage = 'students';
include __DIR__ . '/_layout.php';
?>

    <div class="page-header">
        <h1><i class="fas fa-users"></i> Student Records</h1>
        <div class="page-actions">
            <button onclick="window.location.href='<?= $base ?>/export_students.php'" class="btn-export">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
    </div>

    <div class="card">
        <!-- Strand filter -->
        <div class="filter-section">
            <form method="GET" action="" class="filter-form">
                <select name="strand_filter" id="strand_filter">
                    <option value="">All Strands</option>
                    <?php
                    $strands = ['STEM', 'HUMSS', 'GAS', 'ABM', 'TVL', 'SPORTS', 'ARTS & DESIGN'];
                    foreach ($strands as $s) {
                        $sel = (isset($_GET['strand_filter']) && $_GET['strand_filter'] === $s) ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($s) . "\" $sel>" . htmlspecialchars($s) . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="filter-btn">Filter</button>
                <?php if (!empty($_GET['strand_filter'])): ?>
                    <a href="<?= $base ?>/admin/students" class="clear-filter">Clear Filter</a>
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
                    <th>Status</th>
                    <th>Exam Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM student";
                if (!empty($_GET['strand_filter'])) {
                    $stmt = $conn->prepare("SELECT * FROM student WHERE strand = ? ORDER BY exam_date DESC");
                    $stmt->bind_param("s", $_GET['strand_filter']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $result = $conn->query("SELECT * FROM student ORDER BY exam_date DESC");
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fullName = trim(implode(' ', array_filter([
                            $row['first_name'],
                            $row['middle_name'],
                            $row['last_name'],
                            $row['suffix'],
                        ])));
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($fullName) . "</td>";
                        echo "<td>" . htmlspecialchars($row['lrn']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['strand']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                        if ($row['score'] !== null) {
                            $passed = $row['score'] >= 12;
                            echo "<td>" . $row['score'] . "/20</td>";
                            echo "<td>" . ($passed ? '<span class="badge-pass">PASSED</span>' : '<span class="badge-fail">FAILED</span>') . "</td>";
                        } else {
                            echo "<td><span class='text-muted'>Not taken</span></td>";
                            echo "<td><span class='text-muted'>—</span></td>";
                        }
                        echo "<td>" . ($row['exam_date'] !== null ? date('M d, Y h:i A', strtotime($row['exam_date'])) : '<span class="text-muted">—</span>') . "</td>";
                        echo "<td>
                            <button class='delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No student records found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

</div><!-- /.main-content -->

<script>
const APP_BASE = <?= json_encode($base) ?>;
</script>
<script src="<?= $base ?>/js/delete-student.js"></script>

</body>
</html>
