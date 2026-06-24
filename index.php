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
                        $db->deleteAllStudentNotes();
                        $db->deleteAllStudentApousies();
                        $db->deleteAllStudentTimeTable();
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
                    if (isset($_POST['showStudentBalanceSheet']) && !empty($_POST['studentId'])) {
                        $ledgerResource = $db->getStudentBalanceSheetData();
                        $studentInfo = $db->getStudentPaymentInfo();
                        $rate = (float)($studentInfo['rate'] ?? 10);
                        $paymentType = $studentInfo['paymentType'] ?? 'hour';
                        $studentBalance = 0;

                        if (isset($_POST['date'])) {
                            $studentPaymentsTotal = $db->getStudentPaymentsTotal();
                            if ($paymentType === 'month') {
                                $studentLessonsCost = $db->getMonthsCountBefore() * $rate;
                            } else {
                                $studentLessonsCost = $db->getDurationOfLessons() * $rate;
                            }
                            $studentBalance = $studentLessonsCost - $studentPaymentsTotal;
                        }
                        $page->displayStudentBalanceSheet($ledgerResource, $studentBalance, $rate, $paymentType);

                        echo '<div class="d-print-none mt-4 mb-4">';
                        echo '<button onclick="window.print()" class="btn btn-primary btn-block mb-2">🖨️ Εκτύπωση Καρτέλας</button>';
                        echo '<a href="index.php?action=studentBalanceSheet" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
                        echo '</div>';
                    } else {
                        // Εμφάνιση φόρμας επιλογής
                        $form->getStudentBalanceSheetForm();
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
                    if (isset($_POST['findGroupTimeTable'])) {
                        if (empty($_POST['group_id'])) {
                            $form->getTimeTableForm();
                        } else {
                            $groupId = (int)$_POST['group_id'];
                            $groupTimetable = $db->getGroupFutureTimeTable($groupId);
                            $form->selectGroupTimeTableForm($groupTimetable, $groupId);
                        }
                    } elseif (isset($_POST['deleteGroupTimeTable'])) {
                        if (!empty($_POST['group_id'])) {
                            $db->deleteGroupAllTimeTable((int)$_POST['group_id']);
                        }
                        $form->getTimeTableForm();
                    } elseif (isset($_POST['findTimeTable'])) {
                        $timeTableResource = $db->getOneStudentTimeTable();
                        $form->selectTimeTableForm($timeTableResource);
                    } elseif (isset($_POST['editOne'])) {
                        $lessonTimeTableResource = $db->getOneLessonTimeTable();
                        $form->editTimeTableForm($lessonTimeTableResource);
                    } elseif (isset($_POST['updateOneTimeTable'])) {
                        $db->updateOneDayTimeTable();
                        $form->getTimeTableForm();
                    } elseif (isset($_POST['updateTimeTable'])) {
                        $db->updateTimeTable();
                        $form->getTimeTableForm();
                    } elseif (isset($_POST['deleteOneTimeTable'])) {
                        $db->deleteOneDayTimeTable();
                        $form->getTimeTableForm();
                    } elseif (isset($_POST['deleteTimeTable'])) {
                        $db->deleteTimeTable();
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
                case 'weeklyCalendar':
                    $weekOffset = isset($_GET['weekOffset']) ? (int)$_GET['weekOffset'] : 0;
                    $dt = new DateTime();
                    if ($dt->format('N') != 1) {
                        $dt->modify('last Monday');
                    }
                    if ($weekOffset != 0) {
                        $modifier = $weekOffset > 0 ? '+' . $weekOffset . ' weeks' : $weekOffset . ' weeks';
                        $dt->modify($modifier);
                    }
                    $startDate = $dt->format('Y-m-d');
                    $dtEnd = clone $dt;
                    $dtEnd->modify('+4 days');
                    $endDate = $dtEnd->format('Y-m-d');
                    $result = $db->getWeeklyTimeTable($startDate, $endDate);
                    $page->displayWeeklyCalendar($startDate, $endDate, $result, $weekOffset);
                    break;
                case 'sendWeeklyEmails':
                    $weekOffset = isset($_GET['weekOffset']) ? (int)$_GET['weekOffset'] : 0;
                    $dt = new DateTime();
                    if ($dt->format('N') != 1) {
                        $dt->modify('last Monday');
                    }
                    if ($weekOffset != 0) {
                        $modifier = $weekOffset > 0 ? '+' . $weekOffset . ' weeks' : $weekOffset . ' weeks';
                        $dt->modify($modifier);
                    }
                    $startDate = $dt->format('Y-m-d');
                    $dtEnd = clone $dt;
                    $dtEnd->modify('+4 days');
                    $endDate = $dtEnd->format('Y-m-d');
                    $emails = $db->getWeeklyScheduleEmails($startDate, $endDate);
                    $sentCount = 0;
                    foreach ($emails as $emailData) {
                        $mail->sendMail($emailData['body'], $emailData['email'], 'Πρόγραμμα Εβδομάδας');
                        $sentCount++;
                    }
                    echo "<script>alert('Στάλθηκαν $sentCount email με το πρόγραμμα!'); window.location.href='index.php?action=weeklyCalendar&weekOffset=$weekOffset';</script>";
                    break;
                case 'groups':
                    $groups = $db->getGroups();
                    $students = $db->getStudentsArray();
                    $assignments = $db->getAssignedStudents();
                    $form->manageGroupsForm($groups, $students, $assignments);
                    break;
                case 'saveGroup':
                    if (!empty($_POST['group_name'])) {
                        $db->createGroup($_POST['group_name']);
                    }
                    header('Location: index.php?action=groups');
                    exit;
                case 'renameGroup':
                    if (!empty($_POST['group_id']) && !empty($_POST['new_group_name'])) {
                        $db->renameGroup((int)$_POST['group_id'], $_POST['new_group_name']);
                    }
                    header('Location: index.php?action=groups');
                    exit;
                case 'addStudentToGroup':
                    if (!empty($_POST['student_id']) && !empty($_POST['group_id'])) {
                        $db->addStudentToGroup((int)$_POST['student_id'], (int)$_POST['group_id']);
                    }
                    header('Location: index.php?action=groups');
                    exit;
                case 'removeStudentFromGroup':
                    if (!empty($_GET['student_id'])) {
                        $db->removeStudentFromGroup((int)$_GET['student_id']);
                    }
                    header('Location: index.php?action=groups');
                    exit;
                case 'group_email_form':
                    $groups = $db->getGroups();
                    $page->groupEmailForm($groups);
                    break;
                case 'send_group_email':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (empty($_POST['group_id'])) {
                            echo "<div class='alert alert-danger'>Παρακαλώ επιλέξτε ομάδα.</div>";
                            break;
                        }
                        $groupId = (int)$_POST['group_id'];
                        $subject = htmlspecialchars($_POST['subject']);
                        $messageHtml = $_POST['message'];
                        $students = $db->getStudentsByGroupId($groupId);
                        $groups = $db->getGroups();
                        $groupName = 'Ομάδα';
                        foreach ($groups as $g) {
                            if ($g['id'] == $groupId) {
                                $groupName = $g['group_name'];
                                break;
                            }
                        }
                        $results = $mail->sendBulkGroupMails($students, $subject, $groupName, $messageHtml);
                        if (!empty($results['successful'])) {
                            $db->logGroupEmail($groupId, $subject, $messageHtml);
                        }
                        $_SESSION['email_results'] = ['groupName' => $groupName, 'subject' => $subject, 'message' => $messageHtml, 'successful' => $results['successful'], 'failed' => $results['failed']];
                        header('Location: index.php?action=group_email_results');
                        exit;
                    }
                    break;
                case 'group_email_results':
                    if (isset($_SESSION['email_results'])) {
                        $results = $_SESSION['email_results'];
                        $page->showGroupEmailResults($results['groupName'], $results['subject'], $results['successful'], $results['failed']);
                        unset($_SESSION['email_results']);
                    } else {
                        header('Location: index.php?action=group_email_form');
                        exit;
                    }
                    break;
                case 'group_email_history':
                    $history = $db->getGroupEmailHistory();
                    $page->showGroupEmailHistory($history);
                    break;
                case 'mass_sms_form':
                    $groups = $db->getGroups();
                    $page->massSmsForm($groups);
                    break;
                case 'send_mass_sms':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (empty($_POST['group_id'])) {
                            echo "<div class='alert alert-danger'>Παρακαλώ επιλέξτε παραλήπτες.</div>";
                            break;
                        }
                        $groupId = $_POST['group_id'];
                        $message = $_POST['message'];
                        $students = ($groupId === 'all') ? $db->getAllActiveStudents() : $db->getStudentsByGroupId((int)$groupId);
                        $isPersonalized = strpos($message, '[ΟΝΟΜΑ]') !== false;
                        $individualLinks = [];
                        $noPhone = [];
                        foreach ($students as $student) {
                            if (!empty($student['phone']) && $student['phone'] !== '-') {
                                $phone = preg_replace('/[^0-9]/', '', $student['phone']);
                                if (strlen($phone) === 10) $phone = '+30' . $phone;
                                if (!empty($phone)) {
                                    $personalizedMsg = $isPersonalized ? str_replace('[ΟΝΟΜΑ]', $student['name'], $message) : $message;
                                    $individualLinks[] = ['name' => $student['name'] . ' ' . $student['lastName'], 'link' => 'sms:' . $phone . '?body=' . rawurlencode($personalizedMsg)];
                                }
                            } else {
                                $noPhone[] = htmlspecialchars($student['name'] . ' ' . $student['lastName']);
                            }
                        }
                        echo "<div class='container mt-4'><div class='card shadow-sm border-success'>";
                        echo "<div class='card-header bg-success text-white'><h4 class='mb-0'><i class='fa fa-mobile-phone'></i> Αποστολή SMS από το Κινητό</h4></div>";
                        echo "<div class='card-body'>";
                        echo "<p class='text-muted small mb-3'><i class='fa fa-info-circle'></i> Πατήστε το κουμπί κάθε μαθητή — θα ανοίξει η εφαρμογή SMS με έτοιμο το μήνυμα.</p>";
                        echo "<div class='list-group mb-3'>";
                        if (empty($individualLinks)) {
                            echo "<div class='alert alert-warning mb-0'>Κανένας μαθητής δεν έχει καταχωρημένο κινητό.</div>";
                        }
                        foreach ($individualLinks as $item) {
                            echo "<a href='" . htmlspecialchars($item['link']) . "' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
                                <span><i class='fa fa-user'></i> " . htmlspecialchars($item['name']) . "</span>
                                <span class='badge badge-success'><i class='fa fa-paper-plane'></i> Αποστολή</span></a>";
                        }
                        echo "</div>";
                        if (!empty($noPhone)) {
                            echo "<div class='alert alert-warning small mt-2'><i class='fa fa-exclamation-triangle'></i> Χωρίς τηλέφωνο: " . implode(', ', $noPhone) . "</div>";
                        }
                        echo "<a href='index.php?action=mass_sms_form' class='btn btn-secondary btn-block mt-2'>Επιστροφή</a>";
                        echo "</div></div></div>";
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
