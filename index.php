
<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!$_SESSION['loggedin']) {
    //echo 'pisoooo';
    header('Location: login.php');
    exit;
}

function __autoload($name) {
    include_once $name . '.php';
}

$page = new PageMaker();
$form = new FormMaker;
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
                case 'ergasia':
                    if (isset($_POST['addErgasia'])) {
                        $form->addAskiseisToErgasiaForm();
                        session_start();
                        $_SESSION['date'] = $_POST['date'];
                        $_SESSION['studentId'] = $_POST['studentId'];
                        $_SESSION['location'] = $_POST['location'];
                        $_SESSION['askiseisSource'] = $_POST['askiseisSource'];
                        $_SESSION['askiseis'] = array();
                    } elseif (isset($_POST['addAskisi'])) {
                        session_start();
                        if ($_POST['askisi'] != '') {
                            array_push($_SESSION['askiseis'], $_POST['askisi']);
                        }
                        $_SESSION['studentName'] = $db->getStudentName($_SESSION['studentId']);
                        $form->displayEditAskiseisArrayForm();
                        $form->addAskiseisToErgasiaForm();
                    } elseif (isset($_POST['deleteAskisi'])) {
                        session_start();
                        $first = $_POST['deleteAskisi'];
                        $first = (int) filter_var($first, FILTER_SANITIZE_NUMBER_INT);
                        unset($_SESSION['askiseis'][$first]);
                        $_SESSION['askiseis'] = array_values($_SESSION['askiseis']);
                        $form->displayEditAskiseisArrayForm();
                        $form->addAskiseisToErgasiaForm();
                    } elseif (isset($_POST['submitErgasia'])) {
                        session_start();
                        $db->addAskiseisFromErgasia();
                        $studentName = $db->getStudentName($_SESSION['studentId']);
                        $email = $db->getStudentEmail($_SESSION['studentId']);
                        $page->displayErgasiaArray($_SESSION['askiseis'], $studentName);
                        $body = $mail->makeErgasiaEmail($_SESSION['askiseis'], $studentName);
                        $mail->sendMail($body, $email);
                    } else {
                        $form->addErgasiaForm();
                    }
                    ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                    <?php
                    break;

                case 'panellinies':
                    if (isset($_POST['panellinies'])) {
                        $db->addPanellinies();
                    }
                    $form->addPanelliniesForm();
                    ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                    <?php
                    break;
                case 'theoria':
                    if (isset($_POST['theoria'])) {
                        $db->addTheoria();
                    }
                    $form->addTheoriaForm();
                    ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                    <?php
                    break;
                case 'studentsAskiseis':
                    if ($_POST['studentId'] == '') {
                        $form->getStudentAskiseisForm();
                    }
                    $askiseisResource = $db->getStudentAskiseis();
                    if (isset($askiseisResource)) {
                        $page->displayStudentsAskiseis($askiseisResource);
                        $page->displayNewStudentAskiseisSearch();
                    }
                    break;
                case 'studentsPanellinies':
                    if ($_POST['studentId'] == '') {
                        $form->getStudentPanelliniesForm();
                    }
                    $panelliniesResource = $db->getStudentPanellinies();
                    $email = $db->getStudentEmail($_POST['studentId']);
                    if (isset($panelliniesResource)) {
                        $page->displayStudentsPanellinies($panelliniesResource);
                        $panelliniesResource = $db->getStudentPanellinies();
                        $body = $mail->makePanelliniesEmail($panelliniesResource);
                        $mail->sendMail($body, $email);
                    }
                    break;
                case 'studentsTheoria':
                    if ($_POST['studentId'] == '') {
                        $form->getStudentTheoriaForm();
                    }
                    $theoriaResource = $db->getStudentTheoria();
                    if (isset($theoriaResource)) {
                        $page->displayStudentTheoria($theoriaResource);
                    }

                    break;
                case 'payment':
                    $form->addPaymentForm();
                    $db->addPayment();
                    session_start();
                    $user = $_SESSION['name'];
                    if($user == 'jhouv' || $user == 'jhouv2023'){
                        $db->addPaymentToTransactions(); //προσθέτει την πληρωμή στην εφαρμογή Έσοδα Έξοδα
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
//                        echo '<a href="index.php?action=studentLessons" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
                    }
                    break;
                case 'studentApousies':
                    if ($_POST['studentId'] == '') {
                        $form->getStudentApousiesForm();
                    }
                    if (isset($_POST['getStudentApousies'])) {
                        $studentApousiesResource = $db->getStudentApousies();
                        $page->displayStudentApousies($studentApousiesResource);
//                        echo '<a href="index.php?action=studentLessons" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
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
                    $db->deletePaymentAtTransactions($date, $lastName); //αφαιρεί την πληρωμή στην εφαρμογή Έσοδα Έξοδα
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
                case 'mail':
                    $mail->sendMail();
                    ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                    <?php
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
                        //$db->addTimeTable();
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
                case 'addAskiseisGroup':
                    $form->addAskiseisGroup();
                    if (isset($_POST['addAskiseisGroup'])) {
                        $db->addAskiseisGroup();
                    }
                    break;
                case 'addAskiseisToGroup':
                    if (isset($_POST['updateGroup'])) {
                        $form->addAskiseisToGroupForm();
                        session_start();
                        $_SESSION['date'] = $_POST['date'];
                        $_SESSION['askiseisGroupId'] = $_POST['askiseisGroupId'];
                        $_SESSION['askiseisSource'] = $_POST['askiseisSource'];
                        $_SESSION['askiseis'] = array();
                    } elseif (isset($_POST['addAskisi'])) {
                        session_start();
                        if ($_POST['askisi'] != '') {
                            array_push($_SESSION['askiseis'], $_POST['askisi']);
                        }
                        $_SESSION['askiseisGroupName'] = $db->getAskiseisGroupName($_SESSION['askiseisGroupId']);
                        $form->displayEditAskiseisArrayForm();
                        $form->addAskiseisToGroupForm();
                    } elseif (isset($_POST['deleteAskisi'])) {
                        session_start();
                        $first = $_POST['deleteAskisi'];
                        $first = (int) filter_var($first, FILTER_SANITIZE_NUMBER_INT);
                        unset($_SESSION['askiseis'][$first]);
                        $_SESSION['askiseis'] = array_values($_SESSION['askiseis']);
                        $form->displayEditAskiseisArrayForm();
                        $form->addAskiseisToGroupForm();
                    } elseif (isset($_POST['submitAskiseisToGroup'])) {
                        session_start();
                        $db->addAskiseisFromGroup();
                        $studentName = $db->getAskiseisGroupName($_SESSION['askiseisGroupId']);
                        $page->displayErgasiaArray($_SESSION['askiseis'], $studentName);
                    } else {
                        $form->addGroupDetailsForm();
                    }
                    ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                    <?php
                    break;
                case 'addPanelliniesToGroup':
                    if (isset($_POST['panelliniesToGroup'])) {
                        $db->addPanelliniesToGroup();
                    }
                    $form->addPanelliniesToGroupForm();
                    ?>
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                    <?php
                    break;
                case 'displayAskiseisGroup':
                    if (isset($_POST['displayGroup'])) {
                        $askiseisResource = $db->getGroupAskiseis();
                        $studentName = $db->getAskiseisGroupName($_POST['askiseisGroupId']);
                        $page->displayAskiseisInGroup($askiseisResource, $studentName);
                        echo '<a href="index.php?action=displayAskiseisGroup" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
                    } else {
                        $groupType = 0;
                        $form->displayAskiseisGroupForm($groupType);
                    }
                    break;
                case 'displayPanelliniesGroup':
                    
                    if (isset($_POST['getPanelliniesGroup'])) {
                        $groupResource = $db->getGroupAskiseis();
                        $page->displayPanelliniesGroup($groupResource);
                        echo '<a href="index.php?action=displayPanelliniesGroup" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
                    } else {
                       $form->getPanelliniesInGroupForm(); 
                    }
//                    
//                    if (isset($groupResource)) {
//                        
//                    } else {
//                        $form->getPanelliniesInGroupForm();
//                    }
//                    $groupResource = $db->getGroupAskiseis();
//                    if (isset($groupResource)) {
//                        $page->displayStudentsPanellinies($groupResource);
//                    }
                    break;
                default :
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

