<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../index.php');
    exit();
}

require_once __DIR__ . '/../classes/connection.php';

$pageTitle  = 'Manage Questions';
$activePage = 'questions';
include __DIR__ . '/_layout.php';
?>

    <div class="page-header">
        <h1><i class="fas fa-question-circle"></i> Manage Questions</h1>
        <div class="page-actions">
            <button id="add-question" class="btn-primary">
                <i class="fas fa-plus"></i> Add Question
            </button>
            <button onclick="window.location.href='<?= $base ?>/export_questions.php'" class="btn-export">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
    </div>

    <div class="card">
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
                $sql    = "SELECT * FROM admin_quiz ORDER BY created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['question']) . "</td>";
                        echo "<td>";
                        echo "1) " . htmlspecialchars($row['option1']) . "<br>";
                        echo "2) " . htmlspecialchars($row['option2']) . "<br>";
                        echo "3) " . htmlspecialchars($row['option3']) . "<br>";
                        echo "4) " . htmlspecialchars($row['option4']);
                        echo "</td>";
                        echo "<td><strong>" . htmlspecialchars($row['answer']) . "</strong></td>";
                        echo "<td>
                            <form action='{$base}/edit-question.php' method='GET' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' class='edit-btn'>Edit</button>
                            </form>
                            <button class='delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No questions added yet.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

</div><!-- /.main-content -->

<div id="overlay"></div>

<!-- Add Question Modal -->
<section id="add-question-form" class="card modal-form">
    <h2><i class="fas fa-plus-circle"></i> Add Question</h2>
    <form action="<?= $base ?>/add-question.php" method="POST">
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

<script>
const APP_BASE = <?= json_encode($base) ?>;
</script>
<script src="<?= $base ?>/js/delete-question.js"></script>
<script>
document.getElementById("add-question").addEventListener("click", function () {
    document.getElementById("add-question-form").style.display = "block";
    document.getElementById("overlay").style.display = "block";
});
document.getElementById("cancel-question").addEventListener("click", function () {
    document.getElementById("add-question-form").style.display = "none";
    document.getElementById("overlay").style.display = "none";
});
</script>

</body>
</html>
