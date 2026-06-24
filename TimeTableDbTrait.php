<?php

trait TimeTableDbTrait
{
    public function getOneStudentTimeTable()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        if (isset($_POST['findTimeTable'])) {
            $stmt = $conn->prepare("SELECT * FROM student INNER JOIN tutor_timeTable ON student.studentId = tutor_timeTable.studentId WHERE student.studentId = ? AND tutor_timeTable.studentId = ? AND tutor_timeTable.date >= CURDATE() ORDER BY tutor_timeTable.date");
            $stmt->bind_param("ii", $studentId, $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result;
            } else {
                echo 'Δεν υπάρχουν προγραμματισμένα μαθήματα';
            }
        }
    }

    public function getOneDayTimeTable()
    {
        $conn = $this->connect();
        $date = $_POST['date'];
        $activeYear = $_SESSION['active_school_year'];
        $stmt = $conn->prepare("SELECT name,lastName,tutor_timeTable.timeFrom FROM student INNER JOIN tutor_timeTable ON student.studentId = tutor_timeTable.studentId WHERE tutor_timeTable.date = ? AND student.schoolYear = ? ORDER BY tutor_timeTable.timeFrom");
        $stmt->bind_param("ss", $date, $activeYear);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        } else {
            echo 'Δεν υπάρχουν προγραμματισμένα μαθήματα στις ' . htmlspecialchars($date);
        }
    }

    public function getWeeklyTimeTable($startDate, $endDate)
    {
        $conn = $this->connect();
        $activeYear = $_SESSION['active_school_year'];
        $sql = "SELECT tutor_timeTable.*, student.name, student.lastName
                FROM tutor_timeTable
                INNER JOIN student ON tutor_timeTable.studentId = student.studentId
                WHERE tutor_timeTable.date >= ? AND tutor_timeTable.date <= ?
                AND student.schoolYear = ?
                ORDER BY tutor_timeTable.date, tutor_timeTable.timeFrom";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $startDate, $endDate, $activeYear);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getOneLessonTimeTable()
    {
        $conn = $this->connect();
        $timeTableId = (int)$_POST['timeTableId'];
        $stmt = $conn->prepare("SELECT * FROM tutor_timeTable WHERE timeTableId = ?");
        $stmt->bind_param("i", $timeTableId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        } else {
            echo 'Δεν υπάρχουν προγραμματισμένα μαθήματα';
        }
    }

    public function updateOneDayTimeTable()
    {
        $conn = $this->connect();
        $timeTableId = $_POST['timeTableId'];
        $date = $_POST['date'];
        $timeFrom = $_POST['timeFrom'];
        $timeTo = date('H:i', strtotime($timeFrom . ' + 90 minutes'));
        $sql = "UPDATE tutor_timeTable SET date = ?, timeFrom = ?, timeTo = ? WHERE timeTableId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $date, $timeFrom, $timeTo, $timeTableId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<div class='alert alert-success'>Η αλλαγή έγινε με επιτυχία.</div>";
            } else {
                echo "<div class='alert alert-info'>Δεν έγινε κάποια αλλαγή στα δεδομένα.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }

    public function deleteOneDayTimeTable()
    {
        $conn = $this->connect();
        $timeTableId = $_POST['timeTableId'];
        $sql = "DELETE FROM tutor_timeTable WHERE timeTableId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $timeTableId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<div class='alert alert-success'>Το μάθημα διαγράφηκε επιτυχώς.</div>";
            } else {
                echo "<div class='alert alert-info'>Δεν βρέθηκε το μάθημα για διαγραφή.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }

    public function updateTimeTable()
    {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $date = $_POST['date'];
        $timeFrom = $_POST['timeFrom'];
        $timeTo = date('H:i', strtotime($timeFrom . ' + 90 minutes'));
        $sql = "UPDATE tutor_timeTable SET timeFrom=?, timeTo=? WHERE studentId = ? AND DAYOFWEEK(date) = DAYOFWEEK(?) AND date >= ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiss", $timeFrom, $timeTo, $studentId, $date, $date);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<div class='alert alert-success'>Η αλλαγή έγινε σε " . $stmt->affected_rows . " προγραμματισμένα μαθήματα.</div>";
            } else {
                echo "<div class='alert alert-info'>Δεν έγινε κάποια αλλαγή στα δεδομένα.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }

    public function deleteTimeTable()
    {
        $conn = $this->connect();
        $timeTableId = $_POST['timeTableId'];

        $sql_select = "SELECT studentId, date, timeFrom, timeTo FROM tutor_timeTable WHERE timeTableId = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $timeTableId);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        if ($row = $result->fetch_assoc()) {
            $studentId = $row['studentId'];
            $date = $row['date'];
            $timeFrom = $row['timeFrom'];
            $timeTo = $row['timeTo'];

            $sql = "DELETE FROM tutor_timeTable WHERE timeFrom=? AND timeTo=? AND studentId = ? AND DAYOFWEEK(date) = DAYOFWEEK(?) AND date >= ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiss", $timeFrom, $timeTo, $studentId, $date, $date);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "<div class='alert alert-success'>Η διαγραφή έγινε σε " . $stmt->affected_rows . " προγραμματισμένα μαθήματα.</div>";
                } else {
                    echo "<div class='alert alert-info'>Δεν βρέθηκαν μαθήματα για διαγραφή.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Δεν βρέθηκε το αρχικό μάθημα.</div>";
        }
        $stmt_select->close();
    }

    public function addTimeTable()
    {
        $conn = $this->connect();
        $dateFrom = $_POST['dateFrom'];
        $toDate = !empty($_POST['toDate']) ? $_POST['toDate'] : $dateFrom;
        $day1 = (int)$_POST['day1'];
        $timeFrom1 = $_POST['timeFrom1'];
        $day2 = !empty($_POST['day2']) ? (int)$_POST['day2'] : null;
        $timeFrom2 = !empty($_POST['timeFrom2']) ? $_POST['timeFrom2'] : null;

        // Καθορισμός μαθητών: ομάδα ή μεμονωμένος μαθητής
        if (!empty($_POST['group_id'])) {
            $studentIds = $this->getStudentIdsByGroupId((int)$_POST['group_id']);
            if (empty($studentIds)) {
                echo "<div class='alert alert-warning mt-3'>Η ομάδα δεν έχει μαθητές.</div>";
                return;
            }
        } elseif (!empty($_POST['studentId'])) {
            $studentIds = [(int)$_POST['studentId']];
        } else {
            echo "<div class='alert alert-warning mt-3'>Δεν επιλέχθηκε μαθητής ή ομάδα.</div>";
            return;
        }

        $stmt = $conn->prepare("INSERT INTO tutor_timeTable (studentId, date, timeFrom, timeTo) VALUES (?, ?, ?, ?)");
        $currentDate = $dateFrom;
        $insertedCount = 0;

        while ($currentDate <= $toDate) {
            $dayOfWeek = (int)date('N', strtotime($currentDate));

            foreach ($studentIds as $studentId) {
                if ($dayOfWeek == $day1 && !empty($timeFrom1)) {
                    $timeTo1 = date('H:i', strtotime($timeFrom1 . ' + 90 minutes'));
                    $stmt->bind_param("isss", $studentId, $currentDate, $timeFrom1, $timeTo1);
                    if ($stmt->execute()) $insertedCount++;
                }
                if (!empty($day2) && $dayOfWeek == $day2 && !empty($timeFrom2)) {
                    $timeTo2 = date('H:i', strtotime($timeFrom2 . ' + 90 minutes'));
                    $stmt->bind_param("isss", $studentId, $currentDate, $timeFrom2, $timeTo2);
                    if ($stmt->execute()) $insertedCount++;
                }
            }

            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        $studentCount = count($studentIds);
        if ($insertedCount > 0) {
            echo "<div class='alert alert-success mt-3'>Προστέθηκαν $insertedCount μαθήματα για $studentCount μαθητή/-ές.</div>";
        } else {
            echo "<div class='alert alert-warning mt-3'>Δεν προστέθηκε κανένα μάθημα. Ελέγξτε το εύρος ημερομηνιών και τις ημέρες.</div>";
        }
    }

    public function getStudentsDayLessons()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = $_POST['date'];
        $toDate = isset($_POST['toDate']) ? $_POST['toDate'] : '';
        $allStudents = ($studentId == 0 || $studentId == 6974004099);

        if (!empty($toDate)) {
            if ($allStudents) {
                $stmt = $conn->prepare("SELECT * FROM lesson INNER JOIN student ON lesson.studentId = student.studentId WHERE lesson.duration > 0 AND lesson.date >= ? AND lesson.date <= ? ORDER BY lesson.date");
                $stmt->bind_param("ss", $date, $toDate);
            } else {
                $stmt = $conn->prepare("SELECT * FROM lesson INNER JOIN student ON lesson.studentId = student.studentId WHERE student.studentId = ? AND lesson.duration > 0 AND lesson.date >= ? AND lesson.date <= ? ORDER BY lesson.date");
                $stmt->bind_param("iss", $studentId, $date, $toDate);
            }
        } else {
            if ($allStudents) {
                $stmt = $conn->prepare("SELECT * FROM lesson INNER JOIN student ON lesson.studentId = student.studentId WHERE lesson.duration > 0 AND lesson.date = ?");
                $stmt->bind_param("s", $date);
            } else {
                $stmt = $conn->prepare("SELECT * FROM lesson INNER JOIN student ON lesson.studentId = student.studentId WHERE student.studentId = ? AND lesson.duration > 0 AND lesson.date = ?");
                $stmt->bind_param("is", $studentId, $date);
            }
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    public function getWeeklyScheduleEmails($startDate, $endDate)
    {
        $conn = $this->connect();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $activeYear = $_SESSION['active_school_year'];

        // Βρίσκουμε όλα τα μαθήματα της εβδομάδας
        $sql = "SELECT tutor_timeTable.*, student.name, student.lastName, student.email
                FROM tutor_timeTable
                INNER JOIN student ON tutor_timeTable.studentId = student.studentId
                WHERE tutor_timeTable.date >= ? AND tutor_timeTable.date <= ?
                AND student.schoolYear = ?
                ORDER BY tutor_timeTable.date, tutor_timeTable.timeFrom";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $startDate, $endDate, $activeYear);
        $stmt->execute();
        $result = $stmt->get_result();

        $lessons = [];
        $classes = [];

        // Ομαδοποιούμε με βάση την ώρα για να βρούμε ποιοι είναι μαζί (συμμαθητές)
        while ($row = $result->fetch_assoc()) {
            $lessons[] = $row;
            $classKey = $row['date'] . '_' . $row['timeFrom'];
            if (!isset($classes[$classKey])) {
                $classes[$classKey] = [];
            }
            $classes[$classKey][] = $row['name'];
        }

        $studentSchedules = [];
        foreach ($lessons as $lesson) {
            $studentId = $lesson['studentId'];
            if (empty($lesson['email'])) continue; // Αγνοούμε όσους δεν έχουν email

            if (!isset($studentSchedules[$studentId])) {
                $studentSchedules[$studentId] = ['name' => $lesson['name'], 'email' => $lesson['email'], 'lessons' => []];
            }

            $classKey = $lesson['date'] . '_' . $lesson['timeFrom'];
            $allStudentsInClass = $classes[$classKey];
            // Αφαιρούμε τον ίδιο τον μαθητή από τη λίστα των συμμαθητών του
            $classmates = array_filter($allStudentsInClass, function ($name) use ($lesson) {
                return $name !== $lesson['name'];
            });

            $studentSchedules[$studentId]['lessons'][] = [
                'date' => $lesson['date'],
                'timeFrom' => $lesson['timeFrom'],
                'classmates' => array_values($classmates)
            ];
        }

        $emailsToSend = [];
        $greekDays = ['Monday' => 'Δευτέρα', 'Tuesday' => 'Τρίτη', 'Wednesday' => 'Τετάρτη', 'Thursday' => 'Πέμπτη', 'Friday' => 'Παρασκευή', 'Saturday' => 'Σάββατο', 'Sunday' => 'Κυριακή'];

        foreach ($studentSchedules as $studentId => $data) {
            $body = "<h3>Γεια σου " . $data['name'] . ",</h3><p>Το πρόγραμμά σου για αυτή την εβδομάδα είναι:</p><ul>";
            $groupedTextLessons = [];

            // Ομαδοποιούμε ξανά τις μέρες που ο μαθητής έχει ακριβώς την ίδια ώρα ΚΑΙ τους ίδιους συμμαθητές
            foreach ($data['lessons'] as $l) {
                $dayName = $greekDays[date('l', strtotime($l['date']))];
                $timeFormatted = date('H:i', strtotime($l['timeFrom']));
                sort($l['classmates']);
                $classmatesSignature = implode('|', $l['classmates']);

                $key = $timeFormatted . '___' . $classmatesSignature;
                if (!isset($groupedTextLessons[$key])) {
                    $groupedTextLessons[$key] = ['days' => [], 'time' => $timeFormatted, 'classmates' => $l['classmates']];
                }
                $groupedTextLessons[$key]['days'][] = $dayName;
            }

            foreach ($groupedTextLessons as $g) {
                $daysArr = $g['days'];
                $daysStr = count($daysArr) > 1 ? implode(', ', array_slice($daysArr, 0, -1)) . " και " . end($daysArr) : $daysArr[0];
                $body .= "<li style='margin-bottom:10px;'><b>" . $daysStr . "</b> στις <b>" . $g['time'] . "</b> στον Γιάννη Χουβαρδά";
                if (count($g['classmates']) > 0) {
                    $cArr = $g['classmates'];
                    $cStr = count($cArr) > 1 ? implode(', ', array_slice($cArr, 0, -1)) . " και " . end($cArr) : $cArr[0];
                    $body .= ", μαζί με: " . $cStr;
                }
                $body .= "</li>";
            }
            $emailsToSend[] = ['email' => $data['email'], 'body' => $body . "</ul><p>Καλή εβδομάδα!</p>"];
        }
        return $emailsToSend;
    }
}
