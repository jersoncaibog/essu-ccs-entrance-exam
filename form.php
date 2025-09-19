<?php
include 'classes/connection.php';

$sql = "SELECT * FROM admin_quiz";
$result = $conn->query($sql);

$questions = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            'id' => $row['id'], 
            'question' => $row['question'],
            'options' => [
                $row['option1'],
                $row['option2'],
                $row['option3'],
                $row['option4']
            ],
            'correctAnswer' => array_search($row['answer'], [
                $row['option1'],
                $row['option2'],
                $row['option3'],
                $row['option4']
            ]) 
        ];
    }

    shuffle($questions);
    $questions = array_slice($questions, 0, 2);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Entrance Examination</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color:rgb(243, 245, 247);
            margin: 0;
            padding-block: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid rgb(191, 191, 191);
            max-width: 1000px;
            width: 50%;
            margin: 0 auto;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            position: relative;
        }
        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
        }
        .modal-header h2 {
            color: #2c3e50;
            margin: 0;
        }
        .instructions {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .instructions ul {
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 10px 0;
            color: #34495e;
        }
        .start-exam-btn {
            background-color: #27ae60;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        .start-exam-btn:hover {
            background-color: #219a52;
            transform: translateY(-2px);
        }
        .timer {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #667eea;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 1000;
            font-size: 14px;
        }
        .result-container {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        #pdf-content {
            text-align: center;
        }
        #pdf-content h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
        }
        .student-details {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .student-details p {
            margin: 12px 0;
            font-size: 16px;
            color: #34495e;
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .student-details p:last-child {
            border-bottom: none;
        }
        .student-details strong {
            color: #2c3e50;
            min-width: 120px;
            text-align: left;
        }
        #score-value {
            font-size: 24px;
            font-weight: bold;
            color: #27ae60;
        }
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }
        #save-results, #back-to-login {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 180px;
        }
        #save-results {
            background-color: #27ae60;
            color: white;
        }
        #save-results:hover {
            background-color: #219a52;
            transform: translateY(-2px);
        }
        #back-to-login {
            background-color: #3498db;
            color: white;
        }
        #back-to-login:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .exam-info {
            margin-bottom: 0;
        }
        @media print {
            .result-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }
            .student-details {
                background: none;
                padding: 0;
            }
            #save-results, #back-to-login {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Instructions Modal -->
    <div class="modal" id="instructionsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Examination Instructions</h2>
            </div>
            <div class="instructions">
                <ul>
                    <li>This examination consists of 20 multiple-choice questions.</li>
                    <li>You have 10 minutes to complete the entire examination.</li>
                    <li>Each question has only one correct answer.</li>
                    <li>All questions carry equal marks (1 point each).</li>
                    <li>Once submitted, you cannot return to the examination.</li>
                </ul>
            </div>
            <button class="start-exam-btn" id="startExamBtn">Start Examination</button>
        </div>
    </div>
    
    <div class="container">
        <header>
            <h1 class="exam-title">IT Department Entrance Examination</h1>
            <div class="exam-info">
                <p><strong>Date: </strong><span id="exam-date"></span></p>
                <p><strong>Total Questions: </strong>20</p>
                <p><strong>Time Left: </strong><span id="time-left">10:00</span></p>
            </div>
        </header>
    
        <div class="exam-container">
            <form id="exam-form">
                <div class="questions-container" id="questions-container">
                    <!-- questions to be inserted here thru js -->
                </div>

                <div class="pagination">
                    <button type="button" id="prev-btn" disabled>Previous</button>
                    <span id="page-indicator">Question 1 of 20</span>
                    <button type="button" id="next-btn">Next</button>
                </div>
                
                <button type="button" id="submit-exam">Submit Examination</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            document.getElementById('exam-date').textContent = today.toLocaleDateString();

            // Show instructions modal on page load
            const modal = document.getElementById('instructionsModal');
            modal.style.display = 'flex';

            // Hide exam container initially
            document.querySelector('.container').style.display = 'none';

            // Start exam button click handler
            document.getElementById('startExamBtn').addEventListener('click', function() {
                modal.style.display = 'none';
                document.querySelector('.container').style.display = 'block';
                startTimer();
            });

            const questions = <?php echo json_encode($questions); ?>;
            console.log("Test questions:", questions);

            const questionsContainer = document.getElementById('questions-container');
            const nextBtn = document.getElementById('next-btn');
            const prevBtn = document.getElementById('prev-btn');
            const pageIndicator = document.getElementById('page-indicator');
            const submitBtn = document.getElementById('submit-exam');

            let currentQuestion = 0;
            let answeredQuestions = new Set();
            let storedAnswers = new Array(questions.length).fill(null);
            let timerInterval;

            function startTimer() {
                let timeLeft = 10 * 60; // 10 minutes in seconds
                const timeLeftElement = document.getElementById('time-left');
                
                timerInterval = setInterval(function() {
                    timeLeft--;
                    
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    
                    const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    timeLeftElement.textContent = timeString;
                    
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        submitExam();
                    }
                }, 1000);
            }

            function updateSubmitButtonState() {
                submitBtn.disabled = answeredQuestions.size !== questions.length;
            }

            function renderQuestion(index) {
                questionsContainer.innerHTML = '';

                const q = questions[index];
                const questionDiv = document.createElement('div');
                questionDiv.classList.add('question');

                questionDiv.innerHTML = `
                    <p>${index + 1}. ${q.question}</p>
                    <input type="hidden" name="question_id_${index}" value="${q.id}">
                    <label><input type="radio" name="q${index}" value="0" ${storedAnswers[index] === 0 ? 'checked' : ''}> ${q.options[0]}</label><br>
                    <label><input type="radio" name="q${index}" value="1" ${storedAnswers[index] === 1 ? 'checked' : ''}> ${q.options[1]}</label><br>
                    <label><input type="radio" name="q${index}" value="2" ${storedAnswers[index] === 2 ? 'checked' : ''}> ${q.options[2]}</label><br>
                    <label><input type="radio" name="q${index}" value="3" ${storedAnswers[index] === 3 ? 'checked' : ''}> ${q.options[3]}</label><br>
                `;
                questionsContainer.appendChild(questionDiv);
                pageIndicator.textContent = `Question ${index + 1} of ${questions.length}`;

                prevBtn.disabled = index === 0;
                nextBtn.disabled = index === questions.length - 1;
            }

            // Add event listener for radio button changes
            document.addEventListener('change', function(e) {
                if (e.target.type === 'radio') {
                    const questionIndex = parseInt(e.target.name.substring(1));
                    const selectedValue = parseInt(e.target.value);
                    storedAnswers[questionIndex] = selectedValue;
                    answeredQuestions.add(questionIndex);
                    updateSubmitButtonState();
                }
            });

            nextBtn.addEventListener('click', function () {
                const selectedAnswer = storedAnswers[currentQuestion];
                
                if (selectedAnswer === null) {
                    alert('Please select an answer before proceeding.');
                    return;
                }

                console.log('Current stored answers:', storedAnswers);
                console.log('Current question:', currentQuestion + 1);
                console.log('Selected answer for current question:', selectedAnswer);
                
                currentQuestion++;
                renderQuestion(currentQuestion);
            });

            prevBtn.addEventListener('click', function () {
                if (currentQuestion > 0) {
                    currentQuestion--;
                    renderQuestion(currentQuestion);
                }
            });

            // Initially disable submit button
            submitBtn.disabled = true;
            renderQuestion(currentQuestion); // Initial render

            // Submit exam
            document.getElementById('submit-exam').addEventListener('click', function() {
                submitExam();
            });
            
            function submitExam() {
                clearInterval(timerInterval);
                
                // Get URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const firstName = urlParams.get('first_name') || 'Anonymous';
                const middleName = urlParams.get('middle_name') || '';
                const lastName = urlParams.get('last_name') || '';
                const suffix = urlParams.get('suffix') || '';
                const studentStrand = urlParams.get('strand') || 'N/A';
                const studentPhone = urlParams.get('phone') || 'N/A';
                const studentLrn = urlParams.get('lrn') || 'N/A';
                const studentGender = urlParams.get('gender') || 'N/A';
                const studentAddress = urlParams.get('address') || 'N/A';
                
                // Calculate score using stored answers
                let score = 0;
                let correctAnswers = 0;
                let totalQuestions = questions.length;
                
                // Prepare answers array for database submission
                let answers = [];
                
                for (let i = 0; i < questions.length; i++) {
                    const selectedAnswer = storedAnswers[i];
                    const questionId = questions[i].id;
                    const selectedOption = selectedAnswer !== null ? questions[i].options[selectedAnswer] : null;
                    
                    answers.push({
                        question_id: questionId,
                        selected_answer: selectedOption
                    });
                    
                    if (selectedAnswer !== null && selectedAnswer === questions[i].correctAnswer) {
                        score += 1;
                        correctAnswers++;
                    }
                }
                
                // Get student ID from database
                fetch('get-student-id.php?lrn=' + encodeURIComponent(studentLrn))
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Submit exam results to database
                            fetch('submit-exam.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    student_id: data.student_id,
                                    answers: answers
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Hide the container class
                                    document.querySelector('.container').style.display = 'none';
                                    
                                    // Create and show results container
                                    const resultsContainer = document.createElement('div');
                                    resultsContainer.className = 'result-container';
                                    resultsContainer.innerHTML = `
                                        <div id="pdf-content">
                                            <h2>Exam Results</h2>
                                            <div class="student-details">
                                                <p><strong>Name:</strong> ${firstName} ${middleName} ${lastName} ${suffix}</p>
                                                <p><strong>LRN:</strong> ${studentLrn}</p>
                                                <p><strong>Strand:</strong> ${studentStrand}</p>
                                                <p><strong>Gender:</strong> ${studentGender}</p>
                                                <p><strong>Phone:</strong> ${studentPhone}</p>
                                                <p><strong>Address:</strong> ${studentAddress}</p>
                                                <p><strong>Exam Date:</strong> ${today.toLocaleDateString()}</p>
                                                <p><strong>Score:</strong> <span id="score-value">${score}/20</span></p>
                                            </div>
                                        </div>
                                        <div class="button-group">
                                            <button id="save-results">Save Results as PDF</button>
                                            <button id="back-to-login" onclick="window.location.href='index.php'">Back to Login</button>
                                        </div>
                                    `;
                                    document.body.appendChild(resultsContainer);
                                    
                                    // Save results as PDF
                                    document.getElementById('save-results').addEventListener('click', function() {
                                        const element = document.getElementById('pdf-content');
                                        const opt = {
                                            margin: 1,
                                            filename: 'exam_results.pdf',
                                            image: { type: 'jpeg', quality: 0.98 },
                                            html2canvas: { scale: 2 },
                                            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                                        };
                                        
                                        html2pdf().set(opt).from(element).save();
                                    });
                                } else {
                                    alert('Error saving exam results: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error saving exam results. Please try again.');
                            });
                        } else {
                            alert('Error: Could not find student record.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error: Could not find student record.');
                    });
            }
        });
    </script>

</body>
</html>