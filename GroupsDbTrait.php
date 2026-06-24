<?php

trait GroupsDbTrait
{
    private function getStudentIdsByGroupId($groupId)
    {
        $conn = $this->connectToFamilyDB();
        $stmt = $conn->prepare("SELECT student_id FROM aepp_student_groups WHERE group_id = ?");
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int)$row['student_id'];
        }
        return $ids;
    }

    public function getGroups()
    {
        $conn = $this->connectToFamilyDB();
        $year = (string)$_SESSION['active_school_year'];
        $stmt = $conn->prepare("SELECT * FROM aepp_groups WHERE user_year = ? ORDER BY group_name ASC");
        $stmt->bind_param("s", $year);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createGroup($name)
    {
        $conn = $this->connectToFamilyDB();
        $year = (string)$_SESSION['active_school_year'];
        $stmt = $conn->prepare("INSERT INTO aepp_groups (group_name, user_year) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $year);
        return $stmt->execute();
    }

    public function renameGroup($groupId, $newName)
    {
        $conn = $this->connectToFamilyDB();
        $year = (string)$_SESSION['active_school_year'];
        $stmt = $conn->prepare("UPDATE aepp_groups SET group_name = ? WHERE id = ? AND user_year = ?");
        $stmt->bind_param("sis", $newName, $groupId, $year);
        return $stmt->execute();
    }

    public function addStudentToGroup($studentId, $groupId)
    {
        $conn = $this->connectToFamilyDB();
        $stmtDel = $conn->prepare("DELETE FROM aepp_student_groups WHERE student_id = ?");
        $stmtDel->bind_param("i", $studentId);
        $stmtDel->execute();
        $stmt = $conn->prepare("INSERT INTO aepp_student_groups (student_id, group_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $studentId, $groupId);
        return $stmt->execute();
    }

    public function removeStudentFromGroup($studentId)
    {
        $conn = $this->connectToFamilyDB();
        $stmt = $conn->prepare("DELETE FROM aepp_student_groups WHERE student_id = ?");
        $stmt->bind_param("i", $studentId);
        return $stmt->execute();
    }

    public function getAssignedStudents()
    {
        $conn = $this->connectToFamilyDB();
        $result = $conn->query("SELECT student_id, group_id FROM aepp_student_groups");
        $assignments = [];
        while ($row = $result->fetch_assoc()) {
            $assignments[$row['student_id']] = $row['group_id'];
        }
        return $assignments;
    }

    public function getStudentsByGroupId($groupId)
    {
        $studentIds = $this->getStudentIdsByGroupId($groupId);

        if (empty($studentIds)) return [];

        $connTutor = $this->connect();
        $year = (string)$_SESSION['active_school_year'];
        $placeholders = implode(',', array_fill(0, count($studentIds), '?'));
        $sql = "SELECT studentId, name, lastName, email, phone FROM student WHERE studentId IN ($placeholders) AND schoolYear = ? ORDER BY name ASC";
        $stmt2 = $connTutor->prepare($sql);
        $types = str_repeat('i', count($studentIds)) . 's';
        $params = array_merge($studentIds, [$year]);
        $stmt2->bind_param($types, ...$params);
        $stmt2->execute();
        return $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllActiveStudents()
    {
        $conn = $this->connect();
        $year = (string)$_SESSION['active_school_year'];
        $stmt = $conn->prepare("SELECT studentId, name, lastName, email, phone FROM student WHERE status = 1 AND schoolYear = ? ORDER BY name ASC");
        $stmt->bind_param("s", $year);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function logGroupEmail($groupId, $subject, $message)
    {
        $conn = $this->connectToFamilyDB();
        $year = (string)$_SESSION['active_school_year'];
        $conn->query("CREATE TABLE IF NOT EXISTS aepp_group_email_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            group_id INT NOT NULL,
            subject VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            user_year VARCHAR(50) NOT NULL,
            sent_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $stmt = $conn->prepare("INSERT INTO aepp_group_email_history (group_id, subject, message, user_year, sent_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isss", $groupId, $subject, $message, $year);
        $ok = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $ok;
    }

    public function getGroupEmailHistory()
    {
        $conn = $this->connectToFamilyDB();
        $year = (string)$_SESSION['active_school_year'];
        $conn->query("CREATE TABLE IF NOT EXISTS aepp_group_email_history (
            id INT AUTO_INCREMENT PRIMARY KEY, group_id INT NOT NULL, subject VARCHAR(255) NOT NULL, message TEXT NOT NULL, user_year VARCHAR(50) NOT NULL, sent_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $stmt = $conn->prepare("SELECT h.*, g.group_name FROM aepp_group_email_history h LEFT JOIN aepp_groups g ON h.group_id = g.id WHERE h.user_year = ? ORDER BY h.sent_at DESC");
        $stmt->bind_param("s", $year);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $res;
    }

    public function getGroupFutureTimeTable($groupId)
    {
        $studentIds = $this->getStudentIdsByGroupId($groupId);
        if (empty($studentIds)) return [];
        $conn = $this->connect();
        $placeholders = implode(',', array_fill(0, count($studentIds), '?'));
        $stmt = $conn->prepare("SELECT tt.timeTableId, tt.date, tt.timeFrom, s.name, s.lastName
            FROM tutor_timeTable tt
            JOIN student s ON tt.studentId = s.studentId
            WHERE tt.studentId IN ($placeholders) AND tt.date >= CURDATE()
            ORDER BY tt.date, tt.timeFrom, s.name ASC");
        $types = str_repeat('i', count($studentIds));
        $stmt->bind_param($types, ...$studentIds);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteGroupAllTimeTable($groupId)
    {
        $studentIds = $this->getStudentIdsByGroupId($groupId);
        if (empty($studentIds)) return 0;
        $conn = $this->connect();
        $placeholders = implode(',', array_fill(0, count($studentIds), '?'));
        $stmt = $conn->prepare("DELETE FROM tutor_timeTable WHERE studentId IN ($placeholders) AND date >= CURDATE()");
        $types = str_repeat('i', count($studentIds));
        $stmt->bind_param($types, ...$studentIds);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
