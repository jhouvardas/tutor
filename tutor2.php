<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header('Location: login.php');
    exit;
}

// 1. Φορτώνουμε πρώτα τις βασικές κλάσεις (Tutor 1)
require_once 'DbHandler.php';
require_once 'PageMaker.php';
require_once 'FormMaker.php';

// 2. Μετά φορτώνουμε τις νέες κλάσεις (Tutor 2) που κάνουν extends τις παραπάνω
require_once 'DbHandler2.php';
require_once 'PageMaker2.php';
require_once 'FormMaker2.php';

// 3. Δημιουργούμε τα αντικείμενα από τις ΝΕΕΣ κλάσεις
$page = new PageMaker2();
$form = new FormMaker2();
$db = new DbHandler2();

$page->displayHeadMatter();
$page->displayMenu();
?>
<div class="container-fluid mt-4">
    <?php
    // Διαχείριση των ενεργειών από το μενού
    switch (@$_GET['action']) {

        case 'addQuestion':
            $sources = $db->getSources(); // Φέρνουμε τις πηγές
            // Εδώ είναι το κλειδί: Περνάμε το $sources μέσα στην παρένθεση
            $form->addQuestionToBankForm($sources);
            break;

        case 'saveQuestion':
            if (isset($_POST['submitQuestion'])) {
                $db->saveQuestionToBank();
                echo '<div class="alert alert-success mt-3">Η άσκηση αποθηκεύτηκε με επιτυχία!</div>';
                echo '<a href="tutor2.php?action=addQuestion" class="btn btn-primary">Προσθήκη Νέας</a>';
            }
            break;

        case 'createAssignment':
            // Τώρα καλούμε την πραγματική μέθοδο για να δεις τις ασκήσεις σου
            $questions = $db->getQuestionsFromBank();
            $form->createAssignmentForm($questions);
            break;

        case 'saveAssignment':
            if (isset($_POST['submitAssignment'])) {
                $assignmentId = $db->saveAssignment();
                // Μετά την αποθήκευση, δες την εργασία έτοιμη για εκτύπωση
                header("Location: tutor2.php?action=viewAssignment&id=$assignmentId");
                exit;
            }
            break;

        case 'viewAssignment':
            $assignmentId = $_GET['id'];
            $assignmentData = $db->getAssignmentDetails($assignmentId);
            $questions = $db->getAssignmentQuestions($assignmentId);
            $page->displayAssignmentForPrint($assignmentData['title'], $questions);
            break;

        case 'manageGroups':
            //echo "--- ΤΕΣΤ: Ο κώδικας έφτασε εδώ! ---";
            // Φέρνουμε τους μαθητές μόνο της συγκεκριμένης χρονιάς (user)
            $students = $db->getActiveStudents();
            $form->createGroupForm($students);

            // Λίστα υπαρχουσών ομάδων
            $groups = $db->getGroupsWithMembers();
            echo "<div class='container mt-5'><h5>Οι Ομάδες μου</h5>";
            echo "<table class='table table-bordered table-striped mt-2'>
        <thead class='thead-dark'>
            <tr>
                <th>Όνομα Ομάδας</th>
                <th>Μαθητές</th>
                <th style='width: 100px;'>Ενέργειες</th>
            </tr>
        </thead>
        <tbody>";
            while ($g = $groups->fetch_assoc()) {
                echo "<tr>
            <td>" . htmlspecialchars($g['groupName']) . "</td>
            <td>" . htmlspecialchars($g['members']) . "</td>
            <td>
                <a href='tutor2.php?action=deleteGroup&id=" . $g['groupId'] . "' 
                   class='btn btn-danger btn-sm' 
                   onclick='return confirm(\"Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτή την ομάδα;\")'>
                   Διαγραφή
                </a>
            </td>
          </tr>";
            }
            echo "</tbody></table>";
            break;

        case 'deleteGroup':
            if (isset($_GET['id'])) {
                $db->deleteGroup($_GET['id']);
                header("Location: tutor2.php?action=manageGroups");
                exit;
            }
            break;

        case 'saveGroup':
            if (isset($_POST['groupName'])) {
                $db->saveGroup($_POST['groupName'], $_POST['studentIds']);
                header("Location: tutor2.php?action=manageGroups");
                exit;
            }
            break;

        case 'listAssignments':
        default:
            // Αν δεν έχει επιλεγεί κάτι, δείξε τη λίστα των εργασιών
            $assignments = $db->getAllAssignments();
            $page->displayAssignmentsList($assignments);
            break;
        case 'saveGrade':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $db->saveGrade(
                    $_POST['studentId'],
                    $_POST['exercise_no'],
                    $_POST['book_source'],
                    $_POST['grade'],
                    $_POST['comments']
                );
                // Επιστροφή στη φόρμα με μήνυμα επιτυχίας
                echo "<script>alert('Ο βαθμός καταχωρήθηκε επιτυχώς!'); window.location='tutor2.php?action=manageGrades';</script>";
            }
            break;
        case 'manageGrades':
            // 1. Παίρνουμε τους ενεργούς μαθητές για το dropdown
            $students = $db->getActiveStudents();

            // 2. Καλούμε τη φόρμα από το FormMaker
            $form->createGradeForm($students);
            break;
        case 'addSource':
            // 1. Πρώτα παίρνεις τα δεδομένα από τη βάση μέσω του DbHandler
            $sources = $db->getSources();

            // 2. Μετά τα στέλνεις στη μέθοδο του FormMaker
            // ΠΡΟΣΟΧΗ: Πρέπει να γράψεις το $sources μέσα στην παρένθεση!
            $form->createSourceForm($sources);
            break;

        case 'saveSource':
            if (isset($_POST['sourceName'])) {
                $db->saveSource($_POST['sourceName']);
                echo "<script>alert('Η πηγή προστέθηκε!'); window.location='tutor2.php?action=manageGrades';</script>";
            }
            break;

        case 'manageGrades':
            $students = $db->getActiveStudents();
            $sources = $db->getSources(); // Παίρνουμε τις πηγές
            $form->createGradeForm($students, $sources); // Τις περνάμε στη φόρμα
            break;
        case 'addSource':
            $sources = $db->getSources(); // Φέρνουμε τις πηγές για να τις δείξουμε στον πίνακα
            $form->createSourceForm($sources);
            break;

        case 'deleteSource':
            if (isset($_GET['id'])) {
                $db->deleteSource($_GET['id']);
                header("Location: tutor2.php?action=addSource");
                exit;
            }
            break;
        case 'viewPanellinies':
            // Εμφάνιση της φόρμας που πρόσθεσες στο FormMaker
            $form->uploadPanelliniesForm();

            // Λήψη των αρχείων από τη βάση (θα φτιάξουμε τη μέθοδο στο DbHandler μετά)
            $allPdfs = $db->getPanelliniesPdfs();

            // Εμφάνιση της λίστας (θα την προσθέσουμε στο PageMaker για να είναι νοικοκυρεμένα)
            $page->displayPanelliniesList($allPdfs);
            break;

        case 'savePanelliniesPdf':
            if (isset($_FILES['exam_file'])) {
                $year = $_POST['exam_year'];
                $s_type = $_POST['school_type'];
                $e_type = $_POST['exam_type'];
                $desc = "Πανελλήνιες " . $year . " - " . $s_type . " (" . $e_type . ")";

                // Φάκελος προορισμού (βεβαιώσου ότι υπάρχει ο φάκελος 'pdfs' στο tutor)
                $target_dir = "pdfs/panellinies/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $fileName = basename($_FILES["exam_file"]["name"]);
                $target_file = $target_dir . time() . "_" . $fileName; // Προσθήκη timestamp για μοναδικότητα

                if (move_uploaded_file($_FILES["exam_file"]["tmp_name"], $target_file)) {
                    // Αποθήκευση στη βάση δεδομένων
                    $db->savePanelliniesPdf($year, $s_type, $e_type, $target_file, $desc);
                    echo '<div class="alert alert-success mt-3">Το αρχείο ανέβηκε επιτυχώς!</div>';
                } else {
                    echo '<div class="alert alert-danger mt-3">Σφάλμα κατά το ανέβασμα του αρχείου.</div>';
                }
                echo '<a href="tutor2.php?action=viewPanellinies" class="btn btn-primary">Επιστροφή</a>';
            }
            break;

        // Μέσα στο switch του tutor2.php
        case 'saveAssignment':
            if (isset($_POST['submitAssignment'])) {
                $assignmentId = $db->saveAssignment();
                // Προβολή της εργασίας αμέσως μετά την αποθήκευση
                $questions = $db->getAssignmentQuestions($assignmentId); // Χρειάζεται μια μικρή μέθοδο select
                $page->displayAssignmentForPrint($_POST['assignment_title'], $questions);
            }
            break;
        // Μέσα στο switch του tutor2.php
        case 'listAssignments':
        default:
            $assignments = $db->getAllAssignments();
            $page->displayAssignmentsList($assignments);
            break;

        case 'saveGroup':
            if (isset($_POST['groupName']) && isset($_POST['studentIds'])) {
                // Καλούμε τη μέθοδο που μόλις φτιάξαμε
                $db->saveGroup($_POST['groupName'], $_POST['studentIds']);

                // Μας γυρίζει πίσω στη διαχείριση για να δούμε τη νέα ομάδα στη λίστα
                header("Location: tutor2.php?action=manageGroups");
                exit;
            }
            break;

        case 'deleteAssignment':
            $id = $_GET['id'];
            // Εδώ θα μπορούσαμε να βάλουμε μια μέθοδο $db->deleteAssignment($id)
            echo "<div class='alert alert-danger'>Η λειτουργία διαγραφής θα ενεργοποιηθεί σύντομα.</div>";
            break;
    }


    ?>
</div>

<?php $page->displayEndMatter(); ?>