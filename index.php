<?php
session_start();

if (isset($_POST['set_school_year'])) {
    $_SESSION['active_school_year'] = (int)$_POST['set_school_year'];
    setcookie('preferred_school_year', $_POST['set_school_year'], time() + (86400 * 365), "/");
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

if (!$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

require_once 'DbHandler.php';
require_once 'PageMaker.php';
require_once 'FormMaker.php';
require_once 'MailMaker.php';

$page = new PageMaker();
$form = new FormMaker();
$db = new DbHandler();
$mail = new MailMaker();
$page->displayHeadMatter();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <?php
            $page->displayMenu();
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php
            switch (@$_REQUEST['action']) {
                case 'student':
                    $form->addNewStudentForm();
                    $db->addNewStudent();
                    break;
                case 'editStudent':
                    if (isset($_POST['studentId']) && isset($_POST['updateStudent'])) {
                        $db->updateStudent();
                    } elseif (isset($_POST['studentId'])) {
                        $form->editStudentForm();
                    } else {
                        $form->selectStudentToEditOnChangeSubmitForm();
                    }
                    break;
                case 'deleteStudent':
                    if (isset($_POST['deleteStudentBtn']) && isset($_POST['studentId'])) {
                        $db->deleteAllStudentLessonsAndPayments();
                        $db->deleteAllStudentTelephones();
                        $db->deleteStudent();
                    }
                    $form->deleteStudentForm();
                    break;
                case 'lesson':
                    $form->addLessonForm();
                    $db->addLesson();
            ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'payment':
                    $form->addPaymentForm();
                    $db->addPayment();
                    $user = $_SESSION['name'];
                    if ($user == 'jhouv2025' || $user == 'jhouv2026') {
                        $db->addPaymentToTransactions();
                    }
                ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'note':
                    $form->addNoteForm();
                    $db->addNote();
                ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'editNote':
                    if (isset($_POST['getStudentNotes'])) {
                        $studentNotesResource = $db->getStudentNotes();
                        $form->displayEditDeleteStudentNotes($studentNotesResource);
                    } elseif (isset($_POST['editStudentNote'])) {
                        $noteResource = $db->getNote($_POST['noteId']);
                        $form->editNoteForm($noteResource);
                    } elseif (isset($_POST['updateNote'])) {
                        $db->updateNote();
                    } elseif (isset($_POST['deleteNote'])) {
                        $db->deleteNote();
                    } else {
                        $form->getStudentNotesForm();
                    }
                ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'apousia':
                    $form->addApousiaForm();
                    $db->addApousia();
                ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'studentNotes':
                    if ($_POST['studentId'] == '') {
                        $form->getStudentNotesForm();
                    }
                    if (isset($_POST['getStudentNotes'])) {
                        $studentNotesResource = $db->getStudentNotes();
                        $page->displayStudentNotes($studentNotesResource);
                    }
                    break;
                case 'studentApousies':
                    if ($_POST['studentId'] == '') {
                        $form->getStudentApousiesForm();
                    }
                    if (isset($_POST['getStudentApousies'])) {
                        $studentApousiesResource = $db->getStudentApousies();
                        $page->displayStudentApousies($studentApousiesResource);
                    }
                    break;
                case 'studentMathimataApousies':
                    if ($_POST['studentId'] == '') {
                        $form->getStudentMathimataApousiesForm();
                    }
                    if (isset($_POST['getStudentMathimataApousies'])) {
                        $studentMathimataApousiesResource = $db->getStudentMathimataApousies();
                        $page->displayStudentMathimataApousies($studentMathimataApousiesResource);
                    }
                    break;
                case 'studentLessons':
                    if ($_POST['studentId'] == '') {
                        $form->getStudentLessonsForm();
                    }
                    if (isset($_POST['getStudentLessons'])) {
                        $studentLessonsResource = $db->getStudentsDayLessons();
                        $page->displayStudentLessons($studentLessonsResource);
                        echo '<a href="index.php?action=studentLessons" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
                    }
                    break;
                case 'studentPayments':
                    if ($_POST['studentId'] == 0) {
                        $form->getStudentPaymentsForm();
                    }
                    $studentPaymentsResource = $db->getStudentPayments1();
                    if (isset($studentPaymentsResource)) {
                        $page->displayPayments($studentPaymentsResource);
                        echo '<a href="index.php?action=studentPayments" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
                    }
                    break;
                case 'deleteLesson':
                    $form->deleteLessonForm();
                    if (isset($_POST['lessonId']) && $_POST['lessonId'] != 0) {
                        $db->deleteLesson();
                    }
                ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'deletePayment':
                    $form->deletePaymentForm();
                    $date = $db->getLessonDate($_POST['lessonId']);
                    $lastName = $db->getLessonLastName($_POST['lessonId']);
                    $db->deletePayment();
                    $db->deletePaymentAtTransactions($date, $lastName);
                ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'studentList':
                    $studentsResource = $db->getStudentsDetails();
                    $page->displayStudentsDetails($studentsResource);
                    break;
                case 'studentsBalance':
                    $studentsResource = $db->getStudents();
                    $page->displayStudentsBalance($studentsResource);
                    break;
                case 'studentBalanceSheet':
                    if ($_POST['studentId'] == 0) {
                        $form->getStudentBalanceSheetForm();
                    }
                    if (isset($_POST['showStudentBalanceSheet'])) {
                        $studentLessonsResource = $db->getStudentLessons();
                        $studentBalance = 0;
                        if (isset($_POST['date'])) {
                            $studentPaymentsTotal = $db->getStudentPaymentsTotal();
                            $studentLessonsDuration = $db->getDurationOfLessons();
                            $studentLessonsCost = $studentLessonsDuration * 10;
                            $studentBalance = $studentLessonsCost - $studentPaymentsTotal;
                        }
                        $page->displayStudentBalanceSheet($studentLessonsResource, $studentBalance);
                        echo '<a href="index.php?action=studentBalanceSheet" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
                    }
                    break;
                case 'addTimeTable':
                    $form->addTimeTableForm();
                    if (isset($_POST['setTimeTable'])) {
                        $db->addTimeTable();
                    }
                ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'editTimeTable':
                    if (isset($_POST['findTimeTable'])) {
                        $timeTableResource = $db->getOneStudentTimeTable();
                        $form->selectTimeTableForm($timeTableResource);
                    } elseif (isset($_POST['editOne'])) {
                        $lessonTimeTableResource = $db->getOneLessonTimeTable();
                        $form->editTimeTableForm($lessonTimeTableResource);
                    } elseif (isset($_POST['updateOneTimeTable'])) {
                        $db->updateOneDayTimeTable();
                        $form->getTimeTableForm();
                    } elseif (isset($_POST['deleteTimeTable'])) {
                        $timeTableResource = $db->getOneStudentTimeTable();
                        $db->deleteTimeTable($timeTableResource);
                        $form->getTimeTableForm();
                    } else {
                        $form->getTimeTableForm();
                    }
                ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                <?php
                    break;
                case 'timeTable':
                    if (isset($_POST['showTimeTable'])) {
                        $timeTableResource = $db->getOneDayTimeTable();
                        $page->displayOneDayTimeTable($timeTableResource);
                    } else {
                        $form->showTimeTableForm();
                    }
                    break;
                case 'logOut':
                    session_destroy();
                    break;
                default:
                    $form->frontPageForm();
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php
            // $page->displayMenu();
            ?>
        </div>
    </div>
</div>

<?php
$page->displayEndMatter();
