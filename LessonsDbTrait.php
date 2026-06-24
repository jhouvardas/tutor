<?php

trait LessonsDbTrait
{
    public function addLesson()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $duration = $_POST['duration'];
        $location = $_POST['location'];
        $date = $_POST['date'];

        if (isset($duration) && $duration != '' && $studentId != 0 && isset($date) && $date != '') {
            $stmt = $conn->prepare("INSERT INTO lesson (studentId,duration,date,type,location) VALUES (?,?,?,'lesson',?)");
            $stmt->bind_param("isss", $studentId, $duration, $date, $location);
            if ($stmt->execute()) {
                $stmt2 = $conn->prepare("SELECT lastName FROM student WHERE studentId = ?");
                $stmt2->bind_param("i", $studentId);
                $stmt2->execute();
                $result = $stmt2->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "Προστέθηκε μάθημα στον/ην μαθητή/τρια " . $row['lastName'] . ' στις ' . $date;
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function addNote()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $note = $_POST['note'];
        $date = $_POST['date'];

        if (isset($_POST['submitNote'])) {
            $stmt = $conn->prepare("INSERT INTO note (studentId,note,date) VALUES (?,?,?)");
            $stmt->bind_param("iss", $studentId, $note, $date);
            if ($stmt->execute()) {
                echo "Η σημείωση αποθηκεύτηκε";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function addTheoria()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $book = $_POST['book'];
        $chapter = $_POST['chapter'];
        $comment = $_POST['comment'];
        $date = $_POST['date'];
        if (isset($_POST['theoria'])) {
            $stmt = $conn->prepare("INSERT INTO theoria (studentId, book, chapter, comment, date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $studentId, $book, $chapter, $comment, $date);
            if ($stmt->execute()) {
                echo "Η Θεωρία αποθηκεύτηκε";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function addAskiseisGroup()
    {
        $conn = $this->connect();
        $askiseisGroupName = $_POST['askiseisGroupName'];
        $stmt = $conn->prepare("INSERT INTO tutor_askiseisGroup (askiseisGroupName) VALUES (?)");
        $stmt->bind_param("s", $askiseisGroupName);
        if ($stmt->execute()) {
            echo "Δημιουργήθηκε η " . htmlspecialchars($askiseisGroupName);
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    public function updateNote()
    {
        $conn = $this->connect();
        $noteId = (int)$_POST['noteId'];
        $note = $_POST['note'];

        if (isset($_POST['updateNote'])) {
            $stmt = $conn->prepare("UPDATE note SET note=? WHERE noteId=?");
            $stmt->bind_param("si", $note, $noteId);
            if ($stmt->execute()) {
                echo "Η σημείωση διορθώθηκε";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function deleteNote()
    {
        $conn = $this->connect();
        $noteId = (int)$_POST['noteId'];

        if (isset($_POST['deleteNote'])) {
            $stmt = $conn->prepare("DELETE FROM note WHERE noteId=?");
            $stmt->bind_param("i", $noteId);
            if ($stmt->execute()) {
                echo "Η σημείωση διαγράφηκε";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function addApousia()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $reason = $_POST['reason'];
        $date = $_POST['date'];

        if (isset($_POST['submitApousia'])) {
            $stmt = $conn->prepare("INSERT INTO apousia (studentId,reason,date) VALUES (?,?,?)");
            $stmt->bind_param("iss", $studentId, $reason, $date);
            if ($stmt->execute()) {
                echo "Η απουσία καταχωρήθηκε";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function addCancelLesson()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $reason = "Ακύρωση: " . $_POST['reason'];
        $date = $_POST['date'];

        if (isset($_POST['submitCancelLesson'])) {
            $stmt = $conn->prepare("INSERT INTO apousia (studentId,reason,date) VALUES (?,?,?)");
            $stmt->bind_param("iss", $studentId, $reason, $date);
            if ($stmt->execute()) {
                echo "<div class='alert alert-warning mt-3'>Η ακύρωση καταχωρήθηκε επιτυχώς</div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Error: " . $stmt->error . "</div>";
            }
        }
    }

    public function addAskiseisFromErgasia()
    {
        $conn = $this->connect();
        $studentId = (int)$_SESSION['studentId'];
        $location = $_SESSION['location'];
        $date = $_SESSION['date'];
        $askisisSource = $_SESSION['askiseisSource'];
        $stmt = $conn->prepare("INSERT INTO askiseis (studentId, location, date, askiseisSource, askisi) VALUES (?, ?, ?, ?, ?)");
        $added = 0;
        foreach ($_SESSION['askiseis'] as $askisi) {
            $askisi = (int)$askisi;
            $stmt->bind_param("isssi", $studentId, $location, $date, $askisisSource, $askisi);
            if ($stmt->execute()) {
                $added = 1;
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $conn->close();
    }

    public function addAskiseisFromGroup()
    {
        $conn = $this->connect();
        $askiseisGroupId = (int)$_SESSION['askiseisGroupId'];
        $askisisSource = $_SESSION['askiseisSource'];
        $stmt = $conn->prepare("INSERT INTO tutor_askiseisInGroup (askiseisGroupId, askiseisSource, askisi) VALUES (?, ?, ?)");
        foreach ($_SESSION['askiseis'] as $askisi) {
            $askisi = (int)$askisi;
            $stmt->bind_param("isi", $askiseisGroupId, $askisisSource, $askisi);
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            }
        }
        $conn->close();
    }

    public function addPanellinies()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $location = $_POST['location'];
        $date = $_POST['date'];
        $panelliniesYear = (int)$_POST['panelliniesYear'];
        $thema = $_POST['thema'];
        $erotima = $_POST['erotima'];
        $period = $_POST['period'];
        $lykeio = $_POST['lykeio'];
        if ($studentId != 0 && isset($_POST['panellinies'])) {
            $stmt = $conn->prepare("INSERT INTO askiseis (studentId, location, date, panelliniesYear, thema, erotima, period, lykeio, askiseisSource) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Πανελλήνιες')");
            $stmt->bind_param("ississss", $studentId, $location, $date, $panelliniesYear, $thema, $erotima, $period, $lykeio);
            if ($stmt->execute()) {
                echo "Προστέθηκαν οι Πανελλήνιες";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function addPanelliniesToGroup()
    {
        $conn = $this->connect();
        $askiseisGroupId = (int)$_POST['askiseisGroupId'];
        $panelliniesYear = (int)$_POST['panelliniesYear'];
        $thema = $_POST['thema'];
        $erotima = $_POST['erotima'];
        $period = $_POST['period'];
        $lykeio = $_POST['lykeio'];
        if ($askiseisGroupId != 0 && isset($_POST['panelliniesToGroup'])) {
            $stmt = $conn->prepare("INSERT INTO askiseis (studentId, location, date, panelliniesYear, thema, erotima, period, lykeio, askiseisSource) VALUES (50, '', CURDATE(), ?, ?, ?, ?, ?, 'Πανελλήνιες')");
            $stmt->bind_param("issss", $panelliniesYear, $thema, $erotima, $period, $lykeio);
            if ($stmt->execute()) {
                echo "Προστέθηκαν οι Πανελλήνιες";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function deleteLesson()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $lessonId = (int)$_POST['lessonId'];
        if ($studentId != 0) {
            $stmt = $conn->prepare("DELETE FROM lesson WHERE studentId = ? AND lessonId = ? AND type = 'lesson'");
            $stmt->bind_param("ii", $studentId, $lessonId);
            if ($stmt->execute()) {
                echo "Έγινε διαγραφή του μαθήματος";
            } else {
                echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
            }
        }
    }

    public function getStudentLessons()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = isset($_POST['date']) && $_POST['date'] != '' ? $_POST['date'] : '2020-01-01';

        if ($studentId != 0) {
            if ($studentId == 6974004099) {
                $stmt = $conn->prepare("SELECT * FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE lesson.date >= ? ORDER BY lesson.date, lesson.type");
                $stmt->bind_param("s", $date);
            } else {
                $stmt = $conn->prepare("SELECT * FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE student.studentId = ? AND lesson.date >= ? ORDER BY lesson.date, lesson.type");
                $stmt->bind_param("is", $studentId, $date);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result;
            }
        }
    }

    public function getStudentNotes()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = isset($_POST['date']) && $_POST['date'] != '' ? $_POST['date'] : '2020-01-01';

        if ($studentId != 0) {
            if ($studentId == 6974004099) {
                $stmt = $conn->prepare("SELECT * FROM student INNER JOIN note ON student.studentId = note.studentId WHERE note.date >= ? ORDER BY note.date");
                $stmt->bind_param("s", $date);
            } else {
                $stmt = $conn->prepare("SELECT * FROM student INNER JOIN note ON student.studentId = note.studentId WHERE student.studentId = ? AND note.date >= ? ORDER BY note.date");
                $stmt->bind_param("is", $studentId, $date);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result;
            }
        }
    }

    public function getNote($noteId)
    {
        $conn = $this->connect();
        $noteId = (int)$noteId;
        $stmt = $conn->prepare("SELECT * FROM note WHERE noteId = ?");
        $stmt->bind_param("i", $noteId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function getAskiseisGroupName($askiseisGroupId)
    {
        $conn = $this->connect();
        $askiseisGroupId = (int)$askiseisGroupId;
        $stmt = $conn->prepare("SELECT askiseisGroupName FROM tutor_askiseisGroup WHERE askiseisGroupId = ?");
        $stmt->bind_param("i", $askiseisGroupId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['askiseisGroupName'];
        }
    }

    public function getAskisisGroupName($askiseisGroupId)
    {
        $conn = $this->connect();
        $askiseisGroupId = (int)$askiseisGroupId;
        $stmt = $conn->prepare("SELECT name FROM tutor_askiseisGroup WHERE askiseisGroupId = ?");
        $stmt->bind_param("i", $askiseisGroupId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['name'];
        }
    }

    public function getGroupAskiseis()
    {
        $conn = $this->connect();
        $groupId = (int)$_POST['askiseisGroupId'];
        $stmt = $conn->prepare("SELECT * FROM tutor_askiseisInGroup WHERE askiseisGroupId = ?");
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function getStudentApousies()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = isset($_POST['date']) && $_POST['date'] != '' ? $_POST['date'] : '2020-01-01';

        if ($studentId != 0) {
            if ($studentId == 6974004099) {
                $stmt = $conn->prepare("SELECT * FROM student INNER JOIN apousia ON student.studentId = apousia.studentId WHERE apousia.date >= ? ORDER BY lastName, date");
                $stmt->bind_param("s", $date);
            } else {
                $stmt = $conn->prepare("SELECT * FROM student INNER JOIN apousia ON student.studentId = apousia.studentId WHERE student.studentId = ? AND apousia.date >= ? ORDER BY lastName, date");
                $stmt->bind_param("is", $studentId, $date);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result;
            }
        }
    }

    public function getStudentMathimataApousies()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = isset($_POST['date']) && $_POST['date'] != '' ? $_POST['date'] : '2020-01-01';

        if ($studentId != 0) {
            $stmt = $conn->prepare("SELECT date, type FROM lesson WHERE lesson.type='lesson' AND studentId = ? AND date >= ?
                                    UNION
                                    SELECT date, reason FROM apousia WHERE studentId = ? AND date >= ?
                                    ORDER BY date");
            $stmt->bind_param("isis", $studentId, $date, $studentId, $date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result;
            }
        }
    }

    public function getLessons()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $stmt = $conn->prepare("SELECT date, lessonId FROM lesson INNER JOIN student ON student.studentId = lesson.studentId WHERE student.studentId = ? AND lesson.type = 'lesson' ORDER BY date DESC");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function getLessonDate($lessonId)
    {
        if (isset($_POST['deletePayment'])) {
            $conn = $this->connect();
            $lessonId = (int)$lessonId;
            $stmt = $conn->prepare("SELECT date FROM lesson WHERE lessonId = ?");
            $stmt->bind_param("i", $lessonId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['date'];
            }
        }
    }

    public function getLessonLastName($lessonId)
    {
        if (isset($_POST['deletePayment'])) {
            $conn = $this->connect();
            $lessonId = (int)$lessonId;
            $stmt = $conn->prepare("SELECT lastName FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE lessonId = ?");
            $stmt->bind_param("i", $lessonId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['lastName'];
            }
        }
    }

    public function getDurationOfLessons()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = $_POST['date'];

        if ($studentId == 6974004099) {
            $stmt = $conn->prepare("SELECT SUM(duration) as total FROM lesson WHERE date < ?");
            $stmt->bind_param("s", $date);
        } else {
            $stmt = $conn->prepare("SELECT SUM(duration) as total FROM lesson WHERE studentId = ? AND date < ?");
            $stmt->bind_param("is", $studentId, $date);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    public function getStudentAskiseis()
    {
        $conn = $this->connect();
        $activeYear = $_SESSION['active_school_year'];
        $studentId = (int)$_POST['studentId'];
        $date = $_POST['date'];
        $toDate = $_POST['toDate'];
        $location = $_POST['location'];
        $isAllStudents = ($studentId == 6974004099) ? 1 : 0;

        $stmt = $conn->prepare("SELECT * FROM askiseis INNER JOIN student ON askiseis.studentId = student.studentId
            WHERE (? = 1 OR askiseis.studentId = ?)
            AND askiseis.askiseisSource != 'Πανελλήνιες'
            AND student.schoolYear = ?
            AND (? = '' OR date >= ?)
            AND (? = '' OR date <= ?)
            AND (? = '' OR location = ?)
            ORDER BY name, date, location, askisi ASC");
        $stmt->bind_param("iisssssss", $isAllStudents, $studentId, $activeYear, $date, $date, $toDate, $toDate, $location, $location);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        } else {
            echo "Δεν έχουν ανατεθεί ασκήσεις";
        }
    }

    public function getStudentPanellinies()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = $_POST['date'];
        $location = $_POST['location'];
        $isAllStudents = ($studentId == 6974004099) ? 1 : 0;

        $stmt = $conn->prepare("SELECT * FROM askiseis INNER JOIN student ON askiseis.studentId = student.studentId
            WHERE (? = 1 OR askiseis.studentId = ?)
            AND askiseisSource = 'Πανελλήνιες'
            AND (? = '' OR date >= ?)
            AND (? = '' OR location = ?)
            ORDER BY name, date, location ASC");
        $stmt->bind_param("iissss", $isAllStudents, $studentId, $date, $date, $location, $location);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        } else {
            echo "Δεν έχουν ανατεθεί πανελλήνιες";
        }
    }

    public function getStudentTheoria()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = $_POST['date'];
        $isAllStudents = ($studentId == 6974004099) ? 1 : 0;

        $stmt = $conn->prepare("SELECT * FROM theoria INNER JOIN student ON theoria.studentId = student.studentId
            WHERE (? = 1 OR theoria.studentId = ?)
            AND (? = '' OR date >= ?)
            ORDER BY name, date ASC");
        $stmt->bind_param("iiss", $isAllStudents, $studentId, $date, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        } else {
            echo "Δεν έχει ανατεθεί θεωρία";
        }
    }
}
