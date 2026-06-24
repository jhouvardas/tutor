<?php

trait StudentDbTrait
{
    public function login($username, $password)
    {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                return true;
            }
            // Fallback για plain text - αυτόματη μετατροπή σε hash
            if ($row['password'] === $password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
                $upd->bind_param("ss", $hash, $username);
                $upd->execute();
                return true;
            }
        }
        return false;
    }

    public function getStudentsDetails()
    {
        $conn = $this->connect();
        $activeYear = $_SESSION['active_school_year'];
        $stmt = $conn->prepare("SELECT * FROM student WHERE status = 1 AND schoolYear = ? ORDER BY name ASC");
        $stmt->bind_param("s", $activeYear);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function getOneStudentsDetails()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $stmt = $conn->prepare("SELECT * FROM student WHERE studentId = ?");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function getStudents()
    {
        $conn = $this->connect();
        $activeYear = $_SESSION['active_school_year'];
        $stmt = $conn->prepare("SELECT studentId, paying, paymentType, rate, name, lastName,
            (SELECT SUM(duration) FROM lesson WHERE lesson.studentId = student.studentId) AS dur,
            (SELECT SUM(payment) FROM lesson WHERE lesson.studentId = student.studentId) AS pay,
            (SELECT COUNT(DISTINCT DATE_FORMAT(date, '%Y-%m')) FROM lesson WHERE lesson.studentId = student.studentId AND type = 'lesson') AS months
            FROM student WHERE status = 1 AND schoolYear = ? ORDER BY name");
        $stmt->bind_param("s", $activeYear);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function getAllStudents()
    {
        $conn = $this->connect();
        $activeYear = $_SESSION['active_school_year'];
        $stmt = $conn->prepare("SELECT * FROM student WHERE schoolYear = ? ORDER BY name");
        $stmt->bind_param("s", $activeYear);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function getStudentsWithLesson()
    {
        $conn = $this->connect();
        $activeYear = $_SESSION['active_school_year'];
        $stmt = $conn->prepare("SELECT student.name, student.lastName, student.studentId, tutor_timeTable.date, tutor_timeTable.timeFrom
                FROM student
                INNER JOIN tutor_timeTable ON student.studentId=tutor_timeTable.studentId
                WHERE tutor_timeTable.date <= CURDATE()
                AND NOT EXISTS (SELECT 1 FROM lesson WHERE lesson.studentId = student.studentId AND lesson.date = tutor_timeTable.date AND lesson.type = 'lesson')
                AND NOT EXISTS (SELECT 1 FROM apousia WHERE apousia.studentId = student.studentId AND apousia.date = tutor_timeTable.date)
                AND student.schoolYear = ?
                ORDER BY tutor_timeTable.date ASC, tutor_timeTable.timeFrom ASC");
        $stmt->bind_param("s", $activeYear);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function addPhone()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $phone = $_POST['phone'];
        if ($phone != '') {
            $stmt = $conn->prepare("UPDATE student SET phone = ? WHERE studentId = ?");
            $stmt->bind_param("si", $phone, $studentId);
            if ($stmt->execute()) {
                echo "Προστέθηκε το τηλέφωνο";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function addNewStudent()
    {
        $conn = $this->connect();
        $name = $_POST['name'];
        $lastName = $_POST['lastName'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $school = $_POST['school'];
        $birthday = $_POST['birhtday'];
        $target = $_POST['target'];
        $paymentType = $_POST['paymentType'] ?? 'hour';
        $rate = (float)($_POST['rate'] ?? 10);
        $user = $_SESSION['name'];
        $activeYear = $_SESSION['active_school_year'];

        if (isset($name) && $name != '') {
            $stmt = $conn->prepare("INSERT INTO student (name,lastName,address,birthday,school,phone,email,user,schoolYear,target,paymentType,rate) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssssd", $name, $lastName, $address, $birthday, $school, $phone, $email, $user, $activeYear, $target, $paymentType, $rate);
            if ($stmt->execute()) {
                echo "Προστέθηκε ο Μαθητής";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function updateStudent()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $name = $_POST['name'];
        $lastName = $_POST['lastName'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $birthday = $_POST['birhtday'];
        $target = $_POST['target'];
        $paymentType = $_POST['paymentType'] ?? 'hour';
        $rate = (float)($_POST['rate'] ?? 10);

        if (isset($name) && $name != '') {
            $stmt = $conn->prepare("UPDATE student SET name=?, lastName=?, address=?, birthday=?, phone=?, email=?, target=?, paymentType=?, rate=? WHERE studentId=?");
            $stmt->bind_param("ssssssssdi", $name, $lastName, $address, $birthday, $phone, $email, $target, $paymentType, $rate, $studentId);
            if ($stmt->execute()) {
                echo "Διορθώθηκε ο Μαθητής";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function deleteAllStudentLessonsAndPayments()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        if ($studentId != 0) {
            $stmt = $conn->prepare("DELETE FROM lesson WHERE studentId = ?");
            $stmt->bind_param("i", $studentId);
            if (!$stmt->execute()) {
                echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
            }
        }
    }

    public function deleteAllStudentNotes()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        if ($studentId != 0) {
            $stmt = $conn->prepare("DELETE FROM note WHERE studentId = ?");
            $stmt->bind_param("i", $studentId);
            if (!$stmt->execute()) {
                echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
            }
        }
    }

    public function deleteAllStudentApousies()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        if ($studentId != 0) {
            $stmt = $conn->prepare("DELETE FROM apousia WHERE studentId = ?");
            $stmt->bind_param("i", $studentId);
            if (!$stmt->execute()) {
                echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
            }
        }
    }

    public function deleteAllStudentTimeTable()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        if ($studentId != 0) {
            $stmt = $conn->prepare("DELETE FROM tutor_timeTable WHERE studentId = ?");
            $stmt->bind_param("i", $studentId);
            if (!$stmt->execute()) {
                echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
            }
        }
    }

    public function deleteAllStudentTelephones()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        if ($studentId != 0) {
            $stmt = $conn->prepare("DELETE FROM telephone WHERE studentId = ?");
            $stmt->bind_param("i", $studentId);
            if (!$stmt->execute()) {
                echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
            }
        }
    }

    public function deleteStudent()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        if ($studentId != 0) {
            $stmt = $conn->prepare("DELETE FROM student WHERE studentId = ?");
            $stmt->bind_param("i", $studentId);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success mt-3'>Ο μαθητής διαγράφηκε επιτυχώς.</div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Σφάλμα: " . $stmt->error . "</div>";
            }
        }
    }

    public function getStudentName($studentId)
    {
        $conn = $this->connect();
        $studentId = (int)$studentId;
        $stmt = $conn->prepare("SELECT name FROM student WHERE studentId = ?");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['name'];
        }
    }

    public function getStudentEmail($studentId)
    {
        $conn = $this->connect();
        $studentId = (int)$studentId;
        $stmt = $conn->prepare("SELECT email FROM student WHERE studentId = ?");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['email'];
        }
    }

    public function registerStudent($data)
    {
        $conn = $this->connect();

        $name      = trim($data['name'] ?? '');
        $lastName  = trim($data['lastName'] ?? '');
        $email     = trim($data['email'] ?? '');
        $phone     = trim($data['phone'] ?? '');
        $birthday  = trim($data['birthday'] ?? '');
        $target    = trim($data['target'] ?? '');
        $password  = trim($data['student_password'] ?? '');
        $schoolYear = (date('n') >= 8) ? (int)date('Y') + 1 : (int)date('Y');

        if ($name === '' || $email === '') {
            return false;
        }

        $stmtCheck = $conn->prepare("SELECT studentId FROM student WHERE email = ?");
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        if ($stmtCheck->get_result()->num_rows > 0) {
            return "email_exists";
        }

        $stmt = $conn->prepare("INSERT INTO student (name, lastName, email, phone, birthday, target, password, schoolYear, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("sssssssi", $name, $lastName, $email, $phone, $birthday, $target, $password, $schoolYear);
        return $stmt->execute();
    }

    public function getStudentsArray()
    {
        $conn = $this->connect();
        $activeYear = $_SESSION['active_school_year'];
        $stmt = $conn->prepare("SELECT studentId, name, lastName FROM student WHERE status = 1 AND schoolYear = ? ORDER BY name ASC");
        $stmt->bind_param("s", $activeYear);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
