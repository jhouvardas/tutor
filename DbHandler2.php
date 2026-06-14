<?php
//require_once 'DbHandler.php';

class DbHandler2 extends DbHandler
{

    // --- ΔΙΑΧΕΙΡΙΣΗ ΠΑΝΕΛΛΗΝΙΩΝ PDF ---
    public function savePanelliniesPdf($year, $school_type, $exam_type, $file_path, $description)
    {
        $conn = $this->connect();
        $sql = "INSERT INTO tutor2_panellinies_pdf (exam_year, school_type, exam_type, file_path, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $year, $school_type, $exam_type, $file_path, $description);
        $stmt->execute();
        $stmt->close();
    }

    public function getPanelliniesPdfs()
    {
        $conn = $this->connect();
        $sql = "SELECT * FROM tutor2_panellinies_pdf ORDER BY exam_year DESC, exam_type ASC";
        return $conn->query($sql);
    }

    // --- ΤΡΑΠΕΖΑ ΘΕΜΑΤΩΝ ---
    public function saveQuestionToBank()
    {
        $conn = $this->connect();
        $imagePath = "";
        if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0) {
            $targetDir = "uploads/questions/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            $imagePath = $targetDir . time() . "_" . basename($_FILES['image_path']['name']);
            move_uploaded_file($_FILES['image_path']['tmp_name'], $imagePath);
        }
        $sql = "INSERT INTO tutor2_questions (type, content_text, image_path, book_name, page_number, exercise_number, pan_year, pan_type, pan_session, pan_thema, pan_erotima) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $_POST['type'], $_POST['content_text'], $imagePath, $_POST['book_name'], $_POST['page_number'], $_POST['exercise_number'], $_POST['pan_year'], $_POST['pan_type'], $_POST['pan_session'], $_POST['pan_thema'], $_POST['pan_erotima']);
        $stmt->execute();
        $stmt->close();
    }

    public function getQuestionsFromBank()
    {
        $conn = $this->connect();
        return $conn->query("SELECT * FROM tutor2_questions ORDER BY questionId DESC");
    }

    // --- ΔΙΑΧΕΙΡΙΣΗ ΕΡΓΑΣΙΩΝ (ASSIGNMENTS) ---
    public function saveAssignment()
    {
        $conn = $this->connect();
        $title = $_POST['assignment_title'];
        $selected_questions = $_POST['selected_questions'];
        $sql = "INSERT INTO tutor2_assignments (title) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $assignmentId = $conn->insert_id;
        $sql_item = "INSERT INTO tutor2_assignment_items (assignmentId, questionId) VALUES (?, ?)";
        $stmt_item = $conn->prepare($sql_item);
        foreach ($selected_questions as $qId) {
            $stmt_item->bind_param("ii", $assignmentId, $qId);
            $stmt_item->execute();
        }
        return $assignmentId;
    }

    public function getAssignmentQuestions($assignmentId)
    {
        $conn = $this->connect();
        $sql = "SELECT q.* FROM tutor2_questions q JOIN tutor2_assignment_items ai ON q.questionId = ai.questionId WHERE ai.assignmentId = ? ORDER BY ai.itemId ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getAssignmentDetails($id)
    {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM tutor2_assignments WHERE assignmentId = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllAssignments()
    {
        $conn = $this->connect();
        return $conn->query("SELECT * FROM tutor2_assignments ORDER BY date_created DESC");
    }

    // --- ΔΙΑΧΕΙΡΙΣΗ ΟΜΑΔΩΝ ---
    public function getActiveStudents()
    {
        $conn = $this->connect();
        $user = $_SESSION['name'];
        $sql = "SELECT studentId, name, lastName FROM student WHERE user = '$user' AND studentId NOT IN (SELECT studentId FROM tutor2_group_members) ORDER BY lastName ASC";
        return $conn->query($sql);
    }

    public function saveGroup($groupName, $studentIds)
    {
        $conn = $this->connect();
        $user = $_SESSION['name'];
        $stmt = $conn->prepare("INSERT INTO tutor2_groups (groupName, year_user) VALUES (?, ?)");
        $stmt->bind_param("ss", $groupName, $user);
        $stmt->execute();
        $groupId = $stmt->insert_id;
        if ($groupId > 0 && !empty($studentIds)) {
            $stmt_member = $conn->prepare("INSERT INTO tutor2_group_members (groupId, studentId) VALUES (?, ?)");
            foreach ($studentIds as $sId) {
                $stmt_member->bind_param("ii", $groupId, $sId);
                $stmt_member->execute();
            }
        }
        return $groupId;
    }

    public function getGroupsWithMembers()
    {
        $conn = $this->connect();
        $user = $_SESSION['name'];
        $sql = "SELECT g.groupId, g.groupName, GROUP_CONCAT(CONCAT(s.name, ' ', s.lastName) SEPARATOR ', ') as members 
                FROM tutor2_groups g
                LEFT JOIN tutor2_group_members gm ON g.groupId = gm.groupId
                LEFT JOIN student s ON gm.studentId = s.studentId
                WHERE g.year_user = ?
                GROUP BY g.groupId ORDER BY g.groupId DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function deleteGroup($groupId)
    {
        $conn = $this->connect();
        $stmt1 = $conn->prepare("DELETE FROM tutor2_group_members WHERE groupId = ?");
        $stmt1->bind_param("i", $groupId);
        $stmt1->execute();
        $stmt2 = $conn->prepare("DELETE FROM tutor2_groups WHERE groupId = ?");
        $stmt2->bind_param("i", $groupId);
        $stmt2->execute();
    }

    // --- ΒΑΘΜΟΛΟΓΙΕΣ & ΠΗΓΕΣ ---
    public function saveGrade($studentId, $exercise, $book, $score, $comments)
    {
        $conn = $this->connect();
        $user = $_SESSION['name'];
        $assignmentId = 1;
        $stmt = $conn->prepare("INSERT INTO tutor2_grades (assignmentId, exercise_no, book_source, studentId, final_score_20, comments, year_user) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issidss", $assignmentId, $exercise, $book, $studentId, $score, $comments, $user);
        $stmt->execute();
        $stmt->close();
    }

    public function getStudentGrades($studentId)
    {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM tutor2_grades WHERE studentId = ? ORDER BY date_graded DESC");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function saveSource($name)
    {
        $conn = $this->connect();
        $stmt = $conn->prepare("INSERT INTO tutor2_sources (sourceName) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    public function getSources()
    {
        $conn = $this->connect();
        return $conn->query("SELECT * FROM tutor2_sources ORDER BY sourceName ASC");
    }

    public function deleteSource($sourceId)
    {
        $conn = $this->connect();
        $stmt = $conn->prepare("DELETE FROM tutor2_sources WHERE sourceId = ?");
        $stmt->bind_param("i", $sourceId);
        $stmt->execute();
    }

    // --- LOGIN ΜΑΘΗΤΩΝ ---
    public function checkStudentLogin($email, $pass)
    {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT studentId, name FROM student WHERE email = ? AND quiz_password = ? LIMIT 1");
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
