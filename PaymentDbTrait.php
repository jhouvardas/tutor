<?php

trait PaymentDbTrait
{
    public function getStudentAllLessonsCost($studentId)
    {
        $conn = $this->connect();
        $studentId = (int)$studentId;
        $stmt = $conn->prepare("SELECT SUM(duration) as total FROM lesson WHERE studentId = ?");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'] * 10;
        }
    }

    public function getStudentAllPaymentsTotal($studentId)
    {
        $conn = $this->connect();
        $studentId = (int)$studentId;
        $stmt = $conn->prepare("SELECT SUM(payment) as total FROM lesson WHERE studentId = ?");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
    }

    public function addPayment()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $payment = $_POST['payment'];
        $date = $_POST['date'];

        if (isset($_POST['submitPayment'])) {
            $stmt = $conn->prepare("INSERT INTO lesson (studentId,payment,date,type) VALUES (?,?,?,'payment')");
            $stmt->bind_param("ids", $studentId, $payment, $date);
            if ($stmt->execute()) {
                echo "Η πληρωμή καταχωρήθηκε <br>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function addPaymentToTransactions()
    {
        $conn = $this->connectToFamilyDB();
        $credit = (float)$_POST['payment'];
        $date = $_POST['date'];

        if (isset($_POST['submitPayment'])) {
            $stmt = $conn->prepare("INSERT INTO trans_transact (credit, date, transactionDescriptionId, methodId, userId) VALUES (?, ?, '14', '5', 1)");
            $stmt->bind_param("ds", $credit, $date);
            if ($stmt->execute()) {
                echo "Ενημερώθηκαν τα έσοδα";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function deletePaymentAtTransactions($date, $lastName)
    {
        $conn = $this->connectToFamilyDB();
        if (isset($_POST['deletePayment'])) {
            $stmt = $conn->prepare("DELETE FROM trans_transact WHERE trans_transact.date = ? AND transactionDescriptionId = 14 AND methodId = 5");
            $stmt->bind_param("s", $date);
            if ($stmt->execute()) {
                echo "Ενημερώθηκαν τα έσοδα";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    public function deletePayment()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $lessonId = (int)$_POST['lessonId'];
        if ($studentId != 0 && isset($_POST['deletePayment'])) {
            $stmt = $conn->prepare("DELETE FROM lesson WHERE studentId = ? AND lessonId = ? AND type = 'payment'");
            $stmt->bind_param("ii", $studentId, $lessonId);
            if ($stmt->execute()) {
                echo "Η πληρωμή διαγράφηκε";
            } else {
                echo "<div class='alert alert-danger'>Σφάλμα: " . $stmt->error . "</div>";
            }
        }
    }

    public function getStudentBalanceSheetData()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = isset($_POST['date']) && $_POST['date'] != '' ? $_POST['date'] : '2020-01-01';
        $hasToDate = isset($_POST['toDate']) && !empty($_POST['toDate']);
        $toDate = $hasToDate ? $_POST['toDate'] : null;

        if ($studentId != 0) {
            $allStudents = ($studentId == 6974004099);

            if ($allStudents) {
                if ($hasToDate) {
                    $stmt = $conn->prepare("SELECT student.name, student.lastName, lesson.date, lesson.duration, lesson.payment, lesson.type as entry_type, '' as reason FROM lesson INNER JOIN student ON student.studentId = lesson.studentId WHERE lesson.date >= ? AND lesson.date <= ?
                        UNION ALL SELECT student.name, student.lastName, apousia.date, 0, 0, 'apousia', apousia.reason FROM apousia INNER JOIN student ON student.studentId = apousia.studentId WHERE apousia.date >= ? AND apousia.date <= ? ORDER BY date, entry_type");
                    $stmt->bind_param("ssss", $date, $toDate, $date, $toDate);
                } else {
                    $stmt = $conn->prepare("SELECT student.name, student.lastName, lesson.date, lesson.duration, lesson.payment, lesson.type as entry_type, '' as reason FROM lesson INNER JOIN student ON student.studentId = lesson.studentId WHERE lesson.date >= ?
                        UNION ALL SELECT student.name, student.lastName, apousia.date, 0, 0, 'apousia', apousia.reason FROM apousia INNER JOIN student ON student.studentId = apousia.studentId WHERE apousia.date >= ? ORDER BY date, entry_type");
                    $stmt->bind_param("ss", $date, $date);
                }
            } else {
                if ($hasToDate) {
                    $stmt = $conn->prepare("SELECT student.name, student.lastName, lesson.date, lesson.duration, lesson.payment, lesson.type as entry_type, '' as reason FROM lesson INNER JOIN student ON student.studentId = lesson.studentId WHERE student.studentId = ? AND lesson.date >= ? AND lesson.date <= ?
                        UNION ALL SELECT student.name, student.lastName, apousia.date, 0, 0, 'apousia', apousia.reason FROM apousia INNER JOIN student ON student.studentId = apousia.studentId WHERE student.studentId = ? AND apousia.date >= ? AND apousia.date <= ? ORDER BY date, entry_type");
                    $stmt->bind_param("issiSs", $studentId, $date, $toDate, $studentId, $date, $toDate);
                } else {
                    $stmt = $conn->prepare("SELECT student.name, student.lastName, lesson.date, lesson.duration, lesson.payment, lesson.type as entry_type, '' as reason FROM lesson INNER JOIN student ON student.studentId = lesson.studentId WHERE student.studentId = ? AND lesson.date >= ?
                        UNION ALL SELECT student.name, student.lastName, apousia.date, 0, 0, 'apousia', apousia.reason FROM apousia INNER JOIN student ON student.studentId = apousia.studentId WHERE student.studentId = ? AND apousia.date >= ? ORDER BY date, entry_type");
                    $stmt->bind_param("isis", $studentId, $date, $studentId, $date);
                }
            }
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                return $result;
            }
        }
    }

    public function getStudentPayments1()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = isset($_POST['date']) && $_POST['date'] != '' ? $_POST['date'] : '2020-01-01';

        if ($studentId > 0) {
            if ($studentId == 6974004099) {
                $stmt = $conn->prepare("SELECT student.name, student.lastName, lesson.date, lesson.lessonId, lesson.payment FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE lesson.date >= ? AND lesson.payment > 0 ORDER BY lesson.date");
                $stmt->bind_param("s", $date);
            } else {
                $stmt = $conn->prepare("SELECT student.name, student.lastName, lesson.date, lesson.lessonId, lesson.payment FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE student.studentId = ? AND lesson.date >= ? AND lesson.payment > 0 ORDER BY lesson.date");
                $stmt->bind_param("is", $studentId, $date);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }
    }

    public function getStudentPaymentsTotal()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = $_POST['date'];

        if ($studentId == 6974004099) {
            $stmt = $conn->prepare("SELECT SUM(payment) as total FROM lesson WHERE date < ?");
            $stmt->bind_param("s", $date);
        } else {
            $stmt = $conn->prepare("SELECT SUM(payment) as total FROM lesson WHERE studentId = ? AND date < ?");
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

    public function getStudentPaymentInfo()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $stmt = $conn->prepare("SELECT paymentType, rate FROM student WHERE studentId = ?");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getMonthsCountBefore()
    {
        $conn = $this->connect();
        $studentId = (int)$_POST['studentId'];
        $date = $_POST['date'];
        $stmt = $conn->prepare("SELECT COUNT(DISTINCT DATE_FORMAT(date, '%Y-%m')) as months FROM lesson WHERE studentId = ? AND date < ? AND type = 'lesson'");
        $stmt->bind_param("is", $studentId, $date);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)($row['months'] ?? 0);
    }
}
