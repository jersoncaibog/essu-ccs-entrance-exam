### ESSU-Guiuan CCS Entrance Exam Platform

A web-based entrance examination system for the College of Computer Studies (CCS), ESSU-Guiuan. It lets students register and take a timed multiple-choice exam, and provides an admin dashboard to manage questions, view/filter student records, and export data.

### Features

- **Student**: registration form (name, LRN, strand, etc.), 10-minute multiple-choice exam, real-time pagination and timer, result display with PDF download, prevents retakes by LRN.
- **Admin Dashboard**:
  - **Manage Questions**: add, edit, delete questions with 4 options and a correct answer.
  - **Student Records**: view all takers with score and timestamp, filter by strand.
  - **Export**: one-click export to Excel for questions and student records.

### Tech Stack

- **PHP** (procedural), **MySQL/MariaDB**
- Frontend: HTML, CSS, JavaScript
- Tested on **XAMPP** (PHP 8.x, MariaDB 10.x)

### Project Structure

- `index.php`: Student registration + Admin login UI
- `form.php`: Exam UI (renders questions, timer, pagination, submit)
- `submit-exam.php`: Scores and stores exam results
- `admin-dashboard.php`: Admin features (questions, records, exports)
- `add-question.php`, `edit-question.php`, `delete-question.php`: Question CRUD endpoints
- `export_questions.php`, `export_students.php`: Excel exports
- `classes/connection.php`: DB connection settings
- `student_db.sql`, `admin_quiz.sql`: Database schema and seed data
- `js/script.js`, `js/exam.js`: Client-side logic

### Prerequisites

- XAMPP (or PHP 8+, MySQL/MariaDB)
- Git (optional)

### Installation (Local/XAMPP)

1. Copy this folder to your XAMPP htdocs directory, e.g.

   - `C:\xampp\htdocs\cris_act3`

2. Create database and import schema/data in phpMyAdmin:

   - Create database: `student_db`
   - Import `student_db.sql` (creates tables `admin`, `admin_quiz`, `student` with sample rows)
   - Optionally import `admin_quiz.sql` to load/refresh sample questions

3. Configure database connection in `classes/connection.php` if different from defaults:

   - Host: `localhost`
   - User: `root`
   - Password: `` (empty by default on XAMPP)
   - DB: `student_db`

4. Start Apache and MySQL from XAMPP Control Panel.

5. Open the app in your browser:
   - `http://localhost/cris_act3/index.php`

### Usage

- **Student flow**

  1. Fill in the registration form (LRN must be 12 digits and unique).
  2. You will be redirected to the exam page.
  3. The exam is timed (10 minutes). Navigate between questions, then submit.
  4. Score is saved in the database and shown on screen. You can save a PDF.
  5. Retakes are blocked for the same LRN once a score exists.

- **Admin flow**
  1. From `index.php`, click “Login as Admin”.
  2. Manage questions (add/edit/delete), view student records, filter by strand, and export data.

### Admin Authentication Notes

- The database includes an `admin` table. Update `index.php` to use secure password hashing if you plan to use DB-backed auth in production.
- The current frontend (`js/script.js`) also has a basic localStorage-based login for demo use. Replace or disable this for production.

### Exports

- Questions: `export_questions.php` → `exam_questions.xls`
- Students: `export_students.php` → `student_records.xls`

### Configuration Tips

- Change the number of questions served by the exam in `form.php`:
  - Look for the line that slices the randomized array of questions.
  - Example: replace `array_slice($questions, 0, 2)` with `array_slice($questions, 0, 20)` for twenty questions.

### Database Schema (Overview)

- `admin(id, username, password)`
- `admin_quiz(id, question, option1..option4, answer, created_at)`
- `student(id, first_name, middle_name, last_name, suffix, lrn, strand, phone, gender, address, score, exam_date)`

### Security/Production Considerations

- Use prepared statements everywhere (many endpoints already do; review all queries).
- Store admin passwords hashed (e.g., `password_hash`) and verify with `password_verify`.
- Add server-side session checks on admin pages (e.g., protect `admin-dashboard.php`).
- Validate and sanitize all inputs; enforce CORS and CSRF protections if exposing publicly.

### Troubleshooting

- Blank page or DB errors: verify `classes/connection.php` credentials and that `student_db` exists.
- Admin login not working: align the auth method (DB vs localStorage) and credentials.
- Exports download as `.xls` but open as text: open with Excel/LibreOffice; content is TSV with Excel headers.

### License

No license specified. For institutional use within ESSU-Guiuan CCS unless otherwise noted.
