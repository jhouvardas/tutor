<?php
require_once 'DbHandler.php';
$db = new DbHandler();

class PageMaker
{

    public function displayHeadMatter()
    {
?>
        <!DOCTYPE html>
        <html lang="el">

        <head>
            <title>Μαθήματα</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" href="myCSS.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <link rel="icon" href="images/favicon.jpg" sizes="16x16" type="image/jpg">
            <script src="https://cdn.tiny.cloud/1/00egprfeg5a0fti37lygyyjkx7k4qrv5y3mm1d208ebhi99j/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
            <script>
                tinymce.init({
                    selector: 'textarea:not(.no-mce)',
                    force_br_newlines: true,
                    force_p_newlines: false,
                    forced_root_block: '',
                    entity_encoding: "raw",
                    init_instance_callback: function(editor) {
                        var freeTiny = document.querySelector('.tox .tox-notification--in');
                        freeTiny.style.display = 'none';
                    }
                });
            </script>

        </head>

        <body class="bg-light text-dark">
        <?php
    }

    public function displayMenu()
    {
        $currentYear = $_SESSION['active_school_year'] ?? date('Y');
        ?>
            <nav class="navbar navbar-expand-md bg-dark navbar-dark d-print-none">
                <a class="navbar-brand" href="index.php">Μαθήματα</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=timeTable">Πρόγραμμα</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=weeklyCalendar">Εβδ. Πρόγραμμα</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=lesson">Μάθημα</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=apousia">Απουσία</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=payment">Πληρωμή</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=note">Σημείωση</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="index.php?action=debit" id="navbardrop" data-toggle="dropdown">
                                Αναφορές
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="index.php?action=studentBalanceSheet">Καρτέλα μαθητή</a>
                                <a class="dropdown-item" href="index.php?action=studentList">Κατάσταση Μαθητών</a>
                                <a class="dropdown-item" href="index.php?action=studentPayments">Κατάσταση πληρωμών</a>
                                <a class="dropdown-item" href="index.php?action=studentLessons">Κατάσταση μαθημάτων</a>
                                <a class="dropdown-item" href="index.php?action=studentsBalance">Υπόλοιπα Μαθητών</a>
                                <a class="dropdown-item" href="index.php?action=studentNotes">Σημειώσεις Μαθητών</a>
                                <a class="dropdown-item" href="index.php?action=studentApousies">Απουσίες</a>
                                <a class="dropdown-item" href="index.php?action=studentMathimataApousies">Μαθήματα - Απουσίες</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="index.php?action=debit" id="navbardrop" data-toggle="dropdown">
                                Διαχείριση
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="index.php?action=student">Νέος Μαθητής</a>
                                <a class="dropdown-item" href="index.php?action=editStudent">Διόρθωση Μαθητή</a>
                                <a class="dropdown-item" href="index.php?action=deleteStudent">Διαγραφή Μαθητή</a>
                                <a class="dropdown-item" href="index.php?action=addTimeTable">Προγραμματισμός Μαθημάτων</a>
                                <a class="dropdown-item" href="index.php?action=editTimeTable">Διόρθωση προγραμματισμού</a>
                                <a class="dropdown-item" href="index.php?action=deleteLesson">Διαγραφή μαθήματος</a>
                                <a class="dropdown-item" href="index.php?action=deletePayment">Διαγραφή πληρωμής</a>
                                <a class="dropdown-item" href="index.php?action=editNote">Διόρθωση Σημείωσης</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="index.php?action=groups">Ομάδες</a>
                                <a class="dropdown-item" href="index.php?action=group_email_form">Email Ομάδων</a>
                                <a class="dropdown-item" href="index.php?action=mass_sms_form">Μαζικό SMS</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="copyRegLink(event)">
                                    <span id="regLinkBtnText">Αντιγραφή link εγγραφής</span>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=logOut">Αποσύνδεση</a>
                        </li>
                        <li class="nav-item ml-2 d-flex align-items-center">
                            <form class="form-inline m-0" method="POST" action="">
                                <select name="set_school_year" class="form-control form-control-sm bg-secondary text-white border-secondary font-weight-bold" onchange="this.form.submit()">
                                    <?php for ($y = 2024; $y <= 2030; $y++): ?>
                                        <option value="<?php echo $y; ?>" <?php if ($currentYear == $y) echo 'selected'; ?>><?php echo ($y - 1) . '-' . $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            <script>
                function copyRegLink(event) {
                    event.preventDefault();
                    var link = 'https://www.jhouv.eu/tutor/register.php';
                    var btn = document.getElementById('regLinkBtnText');
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(link).then(function() {
                            btn.textContent = 'Αντιγράφηκε!';
                            setTimeout(function() {
                                btn.textContent = 'Αντιγραφή link εγγραφής';
                            }, 2000);
                        });
                    } else {
                        var ta = document.createElement('textarea');
                        ta.value = link;
                        document.body.appendChild(ta);
                        ta.select();
                        try {
                            document.execCommand('copy');
                        } catch (e) {}
                        document.body.removeChild(ta);
                        btn.textContent = 'Αντιγράφηκε!';
                        setTimeout(function() {
                            btn.textContent = 'Αντιγραφή link εγγραφής';
                        }, 2000);
                    }
                }
            </script>
        <?php
    }

    public function displayStudentLessons($studentLessonsResource)
    {
        //                if (isset($_POST['getStudentLessons'])) {
        ?>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">
                    <thead>
                        <tr class="table-success">
                            <th></th>
                            <th>Ημερομηνία</th>
                            <th>Όνομα</th>
                            <?php
                            if (isset($_POST['lastName'])) {
                                echo '<th>Επωνυμο</th>';
                            }
                            $location = $_POST['location'];
                            if (isset($location)) {
                                echo '<th>Τοποθεσία</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = $studentLessonsResource->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $i . '</td>';
                            $date = date_create($row['date']);
                            echo '<td>' . date_format($date, "D d/m/y") . '</td>';
                            echo '<td>' . $row['name'] . '</td>';
                            if (isset($_POST['lastName'])) {
                                echo '<td>' . $row['lastName'] . '</td>';
                            }
                            $location = $_POST['location'];
                            if (isset($location)) {
                                echo '<td>' . $row['location'] . '</td>';
                            }
                            echo '</tr>';
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        //                }
    }

    public function displayStudentNotes($studentNotesResource)
    {
        //                if (isset($_POST['getStudentLessons'])) {
        ?>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">
                    <thead>
                        <tr class="table-success">
                            <th>Σημειώσεις</th>
                        </tr>
                    </thead>
                    <?php
                    $i = 1;
                    while ($row = $studentNotesResource->fetch_assoc()) {
                        echo '<tbody>';
                        echo '<tr>';
                        $date = date_create($row['date']);
                        echo '<td>' . date_format($date, "D d/m/y") . '     <b>' . $row['name'] . '</b></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>' . $row['note'] . '</td>';
                        echo '</tr>';
                        $i++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        <?php
        //                }
    }

    public function displayOneDayTimeTable($timeTableResource)
    {
        ?>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">
                    <thead>
                        <tr class="table-success">
                            <th>Ημέρα</th>
                            <?php
                            $date = date_create($_POST['date']);
                            ?>
                            <th><?php echo date_format($date, "l d/m/y"); ?></th>
                        </tr>
                        <tr class="table-success">
                            <th>Ώρα</th>
                            <th>Όνομα</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = $timeTableResource->fetch_assoc()) {
                            echo '<tr>';
                            $time = $row['timeFrom'];
                            echo '<td>' . date('H:i', strtotime($time)) . '</td>';
                            echo '<td>' . $row['name'] . ' ' . $row['lastName'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
    }

    public function displayWeeklyCalendar($startDate, $endDate, $result, $weekOffset)
    {
        $schedule = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $schedule[$row['date']][] = $row;
            }
        }

        $greekDays = ['Monday' => 'Δευτέρα', 'Tuesday' => 'Τρίτη', 'Wednesday' => 'Τετάρτη', 'Thursday' => 'Πέμπτη', 'Friday' => 'Παρασκευή', 'Saturday' => 'Σάββατο', 'Sunday' => 'Κυριακή'];
        $colors = ['#007bff', '#28a745', '#dc3545', '#d39e00', '#17a2b8', '#6610f2', '#e83e8c', '#fd7e14', '#20c997', '#6f42c1'];

        // Πρώτο πέρασμα: βρίσκουμε όλες τις μοναδικές ομάδες και δίνουμε χρώμα με σειρά
        $groupColorMap = [];
        $colorCounter = 0;
        $scanDate = new DateTime($startDate);
        for ($i = 0; $i < 5; $i++) {
            $dateStr = $scanDate->format('Y-m-d');
            if (isset($schedule[$dateStr])) {
                $groupedByTime = [];
                foreach ($schedule[$dateStr] as $lesson) {
                    $timeKey = $lesson['timeFrom'];
                    $groupedByTime[$timeKey][] = $lesson['name'];
                }
                foreach ($groupedByTime as $students) {
                    sort($students);
                    $groupKey = implode('|', $students);
                    if (!isset($groupColorMap[$groupKey])) {
                        $groupColorMap[$groupKey] = $colors[$colorCounter % count($colors)];
                        $colorCounter++;
                    }
                }
            }
            $scanDate->modify('+1 day');
        }

        echo '<div class="container-fluid mt-3 mb-5">';

        echo '<div class="text-center mb-3">';
        echo '<h4><span class="badge badge-light border shadow-sm px-3 py-2 text-dark font-weight-normal">📅 ' . date('d/m/Y', strtotime($startDate)) . ' &mdash; ' . date('d/m/Y', strtotime($endDate)) . '</span></h4>';
        echo '</div>';

        echo '<div class="d-flex justify-content-center mb-4 flex-wrap">';
        echo '<div class="btn-group shadow-sm mb-2" role="group">';
        echo '<a href="index.php?action=weeklyCalendar&weekOffset=' . ($weekOffset - 1) . '" class="btn btn-secondary px-3">&laquo; Προηγ.</a>';
        echo '<a href="index.php?action=weeklyCalendar&weekOffset=0" class="btn ' . ($weekOffset == 0 ? 'btn-success disabled' : 'btn-light border') . ' px-3 font-weight-bold">Σήμερα</a>';
        echo '<a href="index.php?action=weeklyCalendar&weekOffset=' . ($weekOffset + 1) . '" class="btn btn-secondary px-3">Επόμ. &raquo;</a>';
        echo '</div>';
        echo '<a href="index.php?action=sendWeeklyEmails&weekOffset=' . $weekOffset . '" class="btn btn-warning shadow-sm mb-2 ml-md-3" onclick="return confirm(\'Να σταλούν τα email με το πρόγραμμα σε όλους τους μαθητές αυτής της εβδομάδας;\');">📧 Αποστολή Προγράμματος</a>';
        echo '</div>';

        echo '<div class="row">';
        $currentDate = new DateTime($startDate);
        for ($i = 0; $i < 5; $i++) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayNameGr = $greekDays[$currentDate->format('l')];
            $displayDate = $currentDate->format('d/m');

            echo '<div class="col-12 col-md-6 col-lg-4 col-xl mb-3">';
            echo '<div class="card h-100 shadow-sm border-0">';
            echo '<div class="card-header bg-success text-white text-center p-2">';
            echo '<strong style="font-size: 1.1rem;">' . $dayNameGr . '</strong><br><small>' . $displayDate . '</small>';
            echo '</div>';
            echo '<div class="card-body p-2 bg-light">';

            if (isset($schedule[$dateStr])) {
                $groupedLessons = [];
                foreach ($schedule[$dateStr] as $lesson) {
                    $timeKey = $lesson['timeFrom'] . '-' . $lesson['timeTo'];
                    if (!isset($groupedLessons[$timeKey])) {
                        $groupedLessons[$timeKey] = [
                            'timeFrom' => $lesson['timeFrom'],
                            'timeTo' => $lesson['timeTo'],
                            'students' => []
                        ];
                    }
                    $groupedLessons[$timeKey]['students'][] = $lesson['name'];
                }

                foreach ($groupedLessons as $group) {
                    $sortedStudents = $group['students'];
                    sort($sortedStudents);
                    $groupKey = implode('|', $sortedStudents);
                    $eventColor = $groupColorMap[$groupKey] ?? '#6c757d';
                    $studentNames = implode(', ', $sortedStudents);

                    echo '<div class="bg-white border rounded p-2 mb-2 shadow-sm" style="border-left: 4px solid ' . $eventColor . ' !important;">';
                    echo '<div class="font-weight-bold" style="color: ' . $eventColor . '; font-size: 1rem;">' . date('H:i', strtotime($group['timeFrom'])) . ' - ' . date('H:i', strtotime($group['timeTo'])) . '</div>';
                    echo '<div class="text-dark mt-1" style="font-size: 0.95rem;">' . $studentNames . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="text-muted text-center small mt-3">Κενό</div>';
            }

            echo '</div></div></div>';
            $currentDate->modify('+1 day');
        }
        echo '</div></div>';
    }

    public function displayStudentApousies($studentApousiesResource)
    {
        //                if (isset($_POST['getStudentLessons'])) {
        ?>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">

                    <?php
                    $i = 1;
                    while ($row = $studentApousiesResource->fetch_assoc()) {
                        echo '<tbody>';
                        echo '<tr>';
                        $date = date_create($row['date']);
                        echo '<td>' . date_format($date, "D d/m/y") . '</td><td><b>' . $row['name'] . '</b></td>' . '<td>' . $row['reason'] . '</td>';
                        echo '</tr>';
                        $i++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        <?php
        //                }
    }

    public function displayStudentMathimataApousies($studentMathimataApousiesResource)
    {
        //                if (isset($_POST['getStudentLessons'])) {
        ?>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">

                    <?php
                    $i = 1;
                    while ($row = $studentMathimataApousiesResource->fetch_assoc()) {
                        echo '<tbody>';
                        echo '<tr>';
                        $date = date_create($row['date']);
                        $type = $row['type'];
                        if ($type == 'lesson') {
                            $type = '<td> Μάθημα </td>';
                        } else {
                            $type = '<td> <b>Απουσία </b>' . $type . '</td>';
                        }
                        echo '<td>' . date_format($date, "D d/m/y") . '</td>' . $type;
                        echo '</tr>';
                        $i++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
            //                }
        }

        public function displayPayments($studentPaymentsResource)
        {
            if (isset($_POST['getStudentPayments'])) {
            ?>
                <div class="table-responsive-sm">
                    <table class="table table-borderless table-striped">
                        <thead>
                            <tr class="table-success">
                                <!--<th></th>-->
                                <th>Επώνυμο</th>
                                <th>Ημερομηνία</th>
                                <th>Ποσό</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $sum = 0;
                            while ($row = $studentPaymentsResource->fetch_assoc()) {

                                echo '<tr>';
                                //                                    echo'<td>' . $i . '</td>';
                                //echo'<td>' . $row['name'] . '</td>';
                                echo '<td>' . $row['lastName'] . '</td>';
                                $date = date_create($row['date']);
                                echo '<td>' . date_format($date, "D d/m/y") . '</td>';
                                echo '<td>' . $row['payment'] . '</td>';
                                echo '</tr>';
                                $sum += $row['payment'];
                                $i++;
                            }
                            ?>
                            <tr>
                                <!--<td></td>-->
                                <td></td>
                                <td>Σύνολο</td>
                                <td><?php echo $sum; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php
            }
        }

        public function displayStudentsDetails($studentsResource)
        {
            ?>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">
                    <thead class="table-success">
                        <tr>
                            <th></th>
                            <th>Όνομα</th>
                            <th>Επώνυμο</th>
                            <!--<th>Κατάσταση</th>-->
                            <th>Τηλέφωνο</th>
                            <th>email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = $studentsResource->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $i . '</td>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td>' . $row['lastName'] . '</td>';
                            echo '<td><a href="tel:' . $row['phone'] . '">' . $row['phone'] . '</a></td>';
                            $email = $row['email'];
                            echo '<td> <a href="mailto:' . $email . '">' . $email . '</a></td>';
                            echo '</tr>';
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        }

        public function displayStudentTheoria($theoriaResource)
        {
        ?>
            <h1>Θεωρία</h1>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">
                    <thead class="table-success">
                        <tr>
                            <!--<th></th>-->
                            <th>Ημερ.</th>
                            <th>Όνομα</th>
                            <th>Βιβλίο</th>
                            <th>Κεφάλαιο</th>
                            <th>Σημείωσ.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = $theoriaResource->fetch_assoc()) {
                            echo '<tr>';
                            //                                echo '<td>' . $i . '</td>';
                            $date = date_create($row['date']);
                            echo '<td>' . date_format($date, " d/m/y") . '</td>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td>' . $row['book'] . '</td>';
                            echo '<td>' . $row['chapter'] . '</td>';
                            echo '<td>' . $row['comment'] . '</td>';
                            echo '</tr>';
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        }


        public function displayStudentsBalance($studentsResource)
        {
            //                if (isset($_GET['studentsBalance'])) {
        ?>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">
                    <thead class="table-success">
                        <tr>
                            <!--<th></th>-->
                            <th>Επώνυμο</th>
                            <th>Όνομα</th>
                            <th>Υπόλοιπο</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $sum = 0;
                        if ($studentsResource->num_rows > 0) {
                            //                                    echo 'aaaaaaaaaaa';
                        }
                        while ($row = $studentsResource->fetch_assoc()) {
                            if ($row['paying'] == 1) {
                                $rate = (float)($row['rate'] ?? 10);
                                $paymentType = $row['paymentType'] ?? 'hour';
                                if ($paymentType === 'month') {
                                    $bal = (int)($row['months'] ?? 0) * $rate - (float)($row['pay'] ?? 0);
                                } else {
                                    $bal = (float)($row['dur'] ?? 0) * $rate - (float)($row['pay'] ?? 0);
                                }
                                echo '<tr>';
                                echo '<td>' . $row['lastName'] . '</td>';
                                echo '<td>' . $row['name'] . '</td>';
                                echo '<td>' . number_format($bal, 2) . '</td>';
                                echo '</tr>';
                                $sum += $bal;
                                $i++;
                            }
                        }
                        ?>
                        <tr>
                            <td></td>
                            <!--<td></td>-->
                            <td>Σύνολο</td>
                            <td><?php echo $sum; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php
            //                }
        }

        public function displayStudentBalanceSheet($ledgerResource, $balance, $rate = 10, $paymentType = 'hour')
        {
            $greekMonths = ['', 'Ιανουάριος', 'Φεβρουάριος', 'Μάρτιος', 'Απρίλιος', 'Μάιος', 'Ιούνιος', 'Ιούλιος', 'Αύγουστος', 'Σεπτέμβριος', 'Οκτώβριος', 'Νοέμβριος', 'Δεκέμβριος'];
        ?>
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped">
                    <thead class="table-success">
                        <tr>
                            <th>Όνομα</th>
                            <th>Ημερομηνία</th>
                            <th>Χρέω</th>
                            <th>Πίστ</th>
                            <th>Υπόλ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Μετ</td>
                            <td><?php echo number_format($balance, 2); ?></td>
                        </tr>
                        <?php
                        $seenMonths = [];
                        if ($ledgerResource && $ledgerResource->num_rows > 0) {
                            while ($entry = $ledgerResource->fetch_assoc()) {
                                $isCancellation = ($entry['entry_type'] === 'apousia' && strpos($entry['reason'] ?? '', 'Ακύρωση:') === 0);

                                // Μηνιαία χρέωση: εισάγουμε γραμμή όταν αλλάζει ο μήνας
                                if ($paymentType === 'month' && $entry['entry_type'] === 'lesson') {
                                    $monthKey = substr($entry['date'], 0, 7);
                                    if (!in_array($monthKey, $seenMonths)) {
                                        $seenMonths[] = $monthKey;
                                        $balance += $rate;
                                        $mNum = (int)substr($monthKey, 5, 2);
                                        $mYear = substr($monthKey, 0, 4);
                                        echo '<tr class="table-primary font-weight-bold">';
                                        echo '<td colspan="2">Μηνιαία χρέωση ' . $greekMonths[$mNum] . ' ' . $mYear . '</td>';
                                        echo '<td>' . number_format($rate, 2) . '</td>';
                                        echo '<td></td>';
                                        echo '<td>' . number_format($balance, 2) . '</td>';
                                        echo '</tr>';
                                    }
                                }

                                if ($entry['entry_type'] === 'lesson' || $entry['entry_type'] === 'payment') {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($entry['name'] ?? '') . '</td>';
                                    $date = date_create($entry['date']);
                                    echo '<td>' . date_format($date, "D d/m/Y") . '</td>';

                                    if ($paymentType === 'hour') {
                                        $lessonCost = ($entry['duration'] ?? 0) * $rate;
                                    } else {
                                        $lessonCost = 0;
                                    }
                                    echo '<td>' . ($lessonCost != 0 ? number_format($lessonCost, 2) : '') . '</td>';

                                    $payment = $entry['payment'] ?? 0;
                                    echo '<td>' . ($payment != 0 ? number_format($payment, 2) : '') . '</td>';

                                    $balance = $balance + $lessonCost - $payment;
                                    echo '<td>' . number_format($balance, 2) . '</td>';
                                    echo '</tr>';
                                } else {
                                    $rowClass = $isCancellation ? ' class="table-warning"' : '';
                                    echo '<tr' . $rowClass . '>';
                                    if ($isCancellation) {
                                        $cleanReason = trim(str_replace('Ακύρωση:', '', $entry['reason']));
                                        echo '<td><span class="font-weight-bold">Ακύρωση:</span> <small>' . htmlspecialchars($cleanReason) . '</small></td>';
                                    } else {
                                        echo '<td><span class="text-danger font-weight-bold">Απουσία:</span> <small>' . htmlspecialchars($entry['reason'] ?? '') . '</small></td>';
                                    }
                                    $date = date_create($entry['date']);
                                    echo '<td>' . date_format($date, "D d/m/Y") . '</td>';
                                    echo '<td></td><td></td>';
                                    echo '<td>' . number_format($balance, 2) . '</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        }

        /* public function displayStudent($studentResource)
        {
            ?>
            <div class="container">
                <h1>Στοιχεία μαθητή</h1>
                <?php
                echo '<dl>';
                echo '<dt>Όνομα</dt>';
                echo '<dt>' . $name . '</dt>';
                echo '<dt>Επώνυμο</dt>';
                echo '<dt>' . $lastName . '</dt>';
                echo '<dt>Διεύθυνση</dt>';
                echo '<dt>' . $address . '</dt>';
                echo '<dt>Σχολείο</dt>';
                echo '<dt>' . $school . '</dt>';
                echo '</dl>';
                ?>
            </div>
        <?php
        } */

        public function groupEmailForm($groups)
        {
        ?>
            <div class="container mt-4 border p-4 bg-light shadow mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0 text-primary"><i class="fa fa-envelope"></i> Αποστολή Ομαδικού Email</h3>
                    <a href="index.php?action=group_email_history" class="btn btn-outline-primary shadow-sm"><i class="fa fa-history"></i> Ιστορικό</a>
                </div>
                <form action="index.php?action=send_group_email" method="post" onsubmit="return handleGroupEmailSubmit(this);">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Επιλογή Ομάδας</label>
                        <select name="group_id" class="form-control" required>
                            <option value="">-- Επιλέξτε Ομάδα --</option>
                            <?php foreach ($groups as $g): ?>
                                <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['group_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Θέμα (Subject)</label>
                        <input type="text" name="subject" class="form-control no-mce" required placeholder="π.χ. Ενημέρωση για το επόμενο μάθημα">
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold mb-2">Κείμενο Μηνύματος</label>
                        <textarea name="message" class="form-control" rows="8"></textarea>
                    </div>
                    <button type="submit" id="btnSendGroupEmail" class="btn btn-primary btn-lg btn-block shadow font-weight-bold">
                        <i class="fa fa-paper-plane"></i> Αποστολή Email
                    </button>
                </form>
            </div>
            <script>
                function handleGroupEmailSubmit(form) {
                    if (confirm('Είστε σίγουροι ότι θέλετε να στείλετε το email στην επιλεγμένη ομάδα;')) {
                        var btn = document.getElementById('btnSendGroupEmail');
                        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Αποστολή... Παρακαλώ περιμένετε';
                        btn.classList.add('disabled');
                        btn.style.pointerEvents = 'none';
                        return true;
                    }
                    return false;
                }
            </script>
        <?php
        }

        public function massSmsForm($groups)
        {
        ?>
            <div class="container mt-4 border p-4 bg-light shadow mb-5">
                <h3 class="mb-4 text-primary"><i class="fa fa-mobile-phone"></i> Μαζική Αποστολή SMS</h3>
                <form action="index.php?action=send_mass_sms" method="post" onsubmit="return confirm('Είστε σίγουροι;');">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Παραλήπτες</label>
                        <select name="group_id" class="form-control" required>
                            <option value="">-- Επιλέξτε Παραλήπτες --</option>
                            <option value="all">Όλοι οι ενεργοί μαθητές</option>
                            <?php foreach ($groups as $g): ?>
                                <option value="<?php echo $g['id']; ?>">Ομάδα: <?php echo htmlspecialchars($g['group_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold mb-2">Μήνυμα (έως 160 χαρακτήρες)</label>
                        <div class="small text-muted mb-2"><i class="fa fa-info-circle"></i> Αν γράψετε <b>[ΟΝΟΜΑ]</b>, θα αντικατασταθεί με το μικρό όνομα του μαθητή.</div>
                        <textarea name="message" class="form-control no-mce" rows="4" maxlength="160" required placeholder="Γράψτε το μήνυμά σας εδώ..."></textarea>
                        <div class="text-right mt-1"><small class="text-muted font-weight-bold">Χαρακτήρες: <span id="charCount">0</span> / 160</small></div>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg btn-block shadow font-weight-bold">
                        <i class="fa fa-mobile-phone fa-lg"></i> Αποστολή μέσω Κινητού (Δωρεάν)
                    </button>
                </form>
            </div>
            <script>
                document.querySelector('textarea[name="message"]').addEventListener('input', function() {
                    document.getElementById('charCount').textContent = this.value.length;
                });
            </script>
        <?php
        }

        public function showGroupEmailResults($groupName, $subject, $successful, $failed)
        {
            $successful = is_array($successful) ? $successful : [];
            $failed = is_array($failed) ? $failed : [];
            $successCount = count($successful);
            $failCount = count($failed);
        ?>
            <div class="container mt-4 mb-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-primary"><i class="fa fa-paper-plane-o"></i> Αποτέλεσμα Αποστολής</h3>
                        <a href="index.php?action=group_email_form" class="btn btn-secondary shadow-sm"><i class="fa fa-arrow-left"></i> Νέο Email</a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info shadow-sm">
                            <p class="mb-1"><strong>Ομάδα:</strong> <?php echo htmlspecialchars($groupName); ?></p>
                            <p class="mb-1"><strong>Θέμα:</strong> <?php echo htmlspecialchars($subject); ?></p>
                            <hr>
                            <p class="mb-0">
                                <span class="text-success font-weight-bold"><i class="fa fa-check-circle"></i> Επιτυχής αποστολή: <?php echo $successCount; ?></span><br>
                                <span class="text-danger font-weight-bold"><i class="fa fa-times-circle"></i> Αποτυχία: <?php echo $failCount; ?></span>
                            </p>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6 mb-4">
                                <h5 class="text-success"><i class="fa fa-check"></i> Επιτυχείς (<?php echo $successCount; ?>)</h5>
                                <div class="list-group shadow-sm" style="max-height:400px;overflow-y:auto;">
                                    <?php if (empty($successful)): ?>
                                        <div class="list-group-item text-muted">Καμία.</div>
                                    <?php else: ?>
                                        <?php foreach ($successful as $s): ?>
                                            <div class="list-group-item small py-2">
                                                <strong><?php echo htmlspecialchars($s['name']); ?></strong><br>
                                                <span class="text-muted"><?php echo htmlspecialchars($s['email']); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-danger"><i class="fa fa-times"></i> Αποτυχημένες (<?php echo $failCount; ?>)</h5>
                                <div class="list-group shadow-sm" style="max-height:400px;overflow-y:auto;">
                                    <?php if (empty($failed)): ?>
                                        <div class="list-group-item text-muted">Καμία.</div>
                                    <?php else: ?>
                                        <?php foreach ($failed as $f): ?>
                                            <div class="list-group-item small py-2">
                                                <strong><?php echo htmlspecialchars($f['name']); ?></strong> (<?php echo htmlspecialchars($f['email']); ?>)<br>
                                                <span class="text-danger" style="font-size:0.8rem;"><em><?php echo htmlspecialchars($f['error'] ?? 'Άγνωστο σφάλμα'); ?></em></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        public function showGroupEmailHistory($history)
        {
        ?>
            <div class="container mt-4 border p-4 bg-light shadow mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0 text-primary"><i class="fa fa-history"></i> Ιστορικό Ομαδικών Emails</h3>
                    <a href="index.php?action=group_email_form" class="btn btn-secondary shadow-sm"><i class="fa fa-arrow-left"></i> Επιστροφή</a>
                </div>
                <div class="table-responsive bg-white rounded shadow-sm border">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th>Ημερομηνία</th>
                                <th>Ομάδα</th>
                                <th>Θέμα</th>
                                <th>Μήνυμα</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($history)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Δεν υπάρχει ιστορικό.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($history as $h): ?>
                                    <tr>
                                        <td class="text-center align-middle" style="width:15%;"><?php echo date('d/m/Y H:i', strtotime($h['sent_at'])); ?></td>
                                        <td class="text-center align-middle font-weight-bold text-primary" style="width:15%;"><?php echo htmlspecialchars($h['group_name'] ?? 'Άγνωστη'); ?></td>
                                        <td class="align-middle font-weight-bold" style="width:25%;"><?php echo htmlspecialchars($h['subject']); ?></td>
                                        <td class="small" style="width:45%;">
                                            <iframe srcdoc="<?php echo htmlspecialchars($h['message']); ?>" sandbox style="width:100%;border:none;min-height:80px;max-height:120px;background:#f8f9fa;border-radius:5px;"></iframe>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        }

        public function displayEndMatter()
        {
        ?>
        </body>

        </html>
    <?php
        }

        public function displayCalendar()
        {
    ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2">
                    <?php ?>
                </div>
            </div>
        </div>
<?php
        }
    }
