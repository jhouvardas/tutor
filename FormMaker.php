<?php

require_once 'DbHandler.php';
require_once 'StudentFormTrait.php';
require_once 'TimeTableFormTrait.php';
require_once 'LessonsFormTrait.php';
require_once 'PaymentFormTrait.php';

class FormMaker
{
    use StudentFormTrait;
    use TimeTableFormTrait;
    use LessonsFormTrait;
    use PaymentFormTrait;

    public function selectDate()
    {
        $defaultDate = isset($_POST['date']) ? $_POST['date'] : (isset($_GET['date']) ? $_GET['date'] : date("Y-m-d"));
?>
        <div class="form-group">
            <label for="date">Ημερομηνία:</label>
            <input type="date" class="form-control" id="date" value="<?php echo $defaultDate; ?>" name="date" required>
        </div>
    <?php
    }

    public function selectToDateNotRequired()
    {
    ?>
        <div class="form-group">
            <label for="date">Μέχρι Ημερομηνία:</label>
            <input type="date" class="form-control" id="date" placeholder="Ημερομηνία" name="toDate">
        </div>
    <?php
    }

    public function selectDateNotRequired()
    {
    ?>
        <div class="form-group">
            <label for="date">Ημερομηνία:</label>
            <input type="date" class="form-control" id="date" placeholder="Ημερομηνία" name="date">
        </div>
    <?php
    }

    public function selectLesson()
    {
        $lessonList = new DbHandler;
    ?>
        <div class="form-group">
            <label for="lesson">Μάθημα:</label>
            <select class="form-control" id="lessonId" name="lessonId">
                <?php
                $result = $lessonList->getLessons();
                echo '<option value=""></option>';
                while ($row = $result->fetch_assoc()) {
                    $date = date_create($row['date']);
                    echo '<option value="' . $row['lessonId'] . '">' . date_format($date, "D d/m/y") . '</option>';
                }
                ?>
            </select>
        </div>
    <?php
    }

    public function selectPayment1()
    {
        $paymentsList = new DbHandler;
    ?>
        <div class="form-group">
            <label for="lesson"> Διαγραφή πληρωμής:</label>
            <select class="form-control" id="studentId" name="lessonId" required>
                <?php
                $result = $paymentsList->getStudentPayments1();
                echo '<option value=""></option>';
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['lessonId'] . '">' . $row['date'] . ' Ποσό ' . $row['payment'] . '€' . '</option>';
                }
                ?>
            </select>
        </div>
    <?php
    }

    public function selectPaymentToDeleteInTransactions()
    {
        $paymentsList = new DbHandler;
    ?>
        <div class="form-group">
            <label for="lesson"> Διαγραφή πληρωμής:</label>
            <select class="form-control" id="studentId" name="lessonId" required ">
                <?php
                $result = $paymentsList->getStudentPayments1();
                echo '<option value=""></option>';
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['lessonId'] . '">' . $row['date'] . ' Ποσό ' . $row['payment'] . '€' . '</option>';
                }
                ?>
            </select>
        </div>
    <?php
    }

    public function selectStudent()
    {
        $studentList = new DbHandler();
    ?>
        <div class=" form-group">
                <label for="student">Μαθητής:</label>
                <select class="form-control" id="studentId" name="studentId" required>
                    <?php
                    $result = $studentList->getStudents();
                    echo '<option value=""></option>';
                    echo '<option value="6974004099">Όλοι</option>';
                    while ($row = $result->fetch_assoc()) {
                        $studentId = $row['studentId'];
                        if ($studentId == $_GET['studentId']) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option value= "' . $studentId . ' " ' . $selected . '>' . $row['name'] . ' ' . $row['lastName'] . '</option>';
                    }
                    ?>
                </select>
        </div>
    <?php
    }

    public function selectStudentOnChangeSubmit()
    {
        $studentList = new DbHandler();
    ?>
        <div class="form-group">
            <label for="student">Μαθητής:</label>
            <select class="form-control" id="studentId" name="studentId" required onchange="this.form.submit()">
                <?php
                $result = $studentList->getStudents();
                echo '<option value=""></option>';
                echo '<option value="6974004099">Όλοι</option>';
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['studentId'] . '">' . $row['name'] . ' ' . $row['lastName'] . '</option>';
                }
                ?>
                <script type="text/javascript">
                    document.getElementById('studentId').value = "<?php echo $_POST['studentId']; ?>";
                </script>
            </select>
        </div>
    <?php
    }

    public function selectStudentToEditOnChangeSubmitForm()
    {
        $studentList = new DbHandler();
    ?>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
            <div class="form-group">
                <label for="student">Μαθητής:</label>
                <select class="form-control" id="studentId" name="studentId" required onchange="this.form.submit()">
                    <?php
                    $result = $studentList->getAllStudents();
                    echo '<option value=""></option>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['studentId'] . '">' . $row['name'] . ' ' . $row['lastName'] . '</option>';
                    }
                    ?>
                    <script type="text/javascript">
                        document.getElementById('studentId').value = "<?php echo $_POST['studentId']; ?>";
                    </script>
                </select>
            </div>
        </form>
    <?php
    }

    public function frontPageForm()
    {
        $db = new DbHandler();
    ?>
        <h5>Εκκρεμότητες Προγράμματος</h5>
        <div class="table-responsive-sm">
            <table class="table table-borderless table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Όνομα</th>
                        <th>Ημερομηνία</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $studentsResource = $db->getStudentsWithLesson();
                    if ($studentsResource && $studentsResource->num_rows > 0) {
                        while ($row = $studentsResource->fetch_assoc()) {
                            $isPast = (strtotime($row['date']) < strtotime(date('Y-m-d')));
                            $rowClass = $isPast ? ' class="table-danger font-weight-bold"' : '';
                            $dateStr = date('d/m/Y', strtotime($row['date']));
                            $timeStr = date('H:i', strtotime($row['timeFrom']));
                    ?>
                            <tr<?php echo $rowClass; ?>>
                                <td> <?php echo $row['name'] . ' ' . $row['lastName']; ?> </td>
                                <td>
                                    <?php
                                    if ($isPast) {
                                        echo '⚠️ <span class="text-danger">' . $dateStr . ' (' . $timeStr . ')</span>';
                                    } else {
                                        echo $dateStr . ' (' . $timeStr . ')';
                                    }
                                    ?>
                                </td>
                                <td><button class="btn btn-success" onclick="document.location = 'index.php?action=lesson&studentId=<?php echo $row['studentId']; ?>&date=<?php echo $row['date']; ?>'">Μάθημα</button></td>
                                <td><button class="btn btn-danger" onclick="document.location = 'index.php?action=apousia&studentId=<?php echo $row['studentId']; ?>&date=<?php echo $row['date']; ?>'">Απουσία</button></td>
                                <td><button class="btn btn-warning" onclick="document.location = 'index.php?action=cancelLesson&studentId=<?php echo $row['studentId']; ?>&date=<?php echo $row['date']; ?>'">Ακύρωση</button></td>
                                </tr>
                        <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center font-weight-bold text-success">Δεν υπάρχουν εκκρεμότητες! 🎉</td></tr>';
                    }
                        ?>
                </tbody>
            </table>
        </div>
    <?php
    }

    public function loginForm()
    {
    ?>
        <div class="container">
            <form action="authenticate.php" class="needs-validation" novalidate method="post">
                <div class="form-group">
                    <label for="uname">Username:</label>
                    <input type="text" class="form-control" id="uname" placeholder="Enter username" name="username" required>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" required>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <button type="submit" class="btn btn-success" name="login">Submit</button>
            </form>
        </div>
    <?php
        $this->addFormValidation();
    }

    public function addFormValidation()
    {
    ?>
        <script>
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
    <?php
    }

    public function manageGroupsForm($groups, $students, $assignments)
    {
    ?>
        <div class="container mt-4">
            <h4>Διαχείριση Ομάδων</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">Δημιουργία Ομάδας</div>
                        <form action="index.php?action=saveGroup" method="POST" class="card-body">
                            <input type="text" name="group_name" class="form-control mb-2" placeholder="Όνομα ομάδας" required>
                            <button type="submit" class="btn btn-primary btn-block">Δημιουργία</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">Ανάθεση σε Ομάδα</div>
                        <form action="index.php?action=addStudentToGroup" method="POST" class="card-body">
                            <select name="student_id" class="form-control mb-2" required>
                                <option value="">Επίλεξε Μαθητή</option>
                                <?php foreach ($students as $s):
                                    if (array_key_exists($s['studentId'], $assignments)) continue; ?>
                                    <option value="<?php echo $s['studentId']; ?>">
                                        <?php echo htmlspecialchars($s['name'] . ' ' . $s['lastName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="group_id" class="form-control mb-2" required>
                                <option value="">Επίλεξε Ομάδα</option>
                                <?php foreach ($groups as $g): ?>
                                    <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['group_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-info btn-block">Ανάθεση</button>
                        </form>
                    </div>
                </div>
            </div>

            <?php if (empty($groups)): ?>
                <div class="alert alert-secondary">Δεν υπάρχουν ακόμα ομάδες για αυτό το σχολικό έτος.</div>
            <?php else: ?>
                <h5 class="mt-2">Ομάδες &amp; Μέλη</h5>
                <table class="table table-sm table-bordered bg-white shadow-sm">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:35%">Ομάδα</th>
                            <th>Μέλη</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $g):
                            $members = [];
                            foreach ($students as $s) {
                                if (isset($assignments[$s['studentId']]) && $assignments[$s['studentId']] == $g['id']) {
                                    $members[] = $s;
                                }
                            }
                        ?>
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center justify-content-between" id="group-label-<?php echo $g['id']; ?>">
                                        <strong><?php echo htmlspecialchars($g['group_name']); ?></strong>
                                        <button class="btn btn-sm btn-outline-secondary ml-2" onclick="showRenameForm(<?php echo $g['id']; ?>)">✏️</button>
                                    </div>
                                    <form action="index.php?action=renameGroup" method="POST" id="rename-form-<?php echo $g['id']; ?>" class="d-none mt-2">
                                        <input type="hidden" name="group_id" value="<?php echo $g['id']; ?>">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="new_group_name" class="form-control" value="<?php echo htmlspecialchars($g['group_name']); ?>" required>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-success">✔</button>
                                                <button type="button" class="btn btn-secondary" onclick="hideRenameForm(<?php echo $g['id']; ?>)">✖</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <?php if (empty($members)): ?>
                                        <span class="text-muted small">Κενή ομάδα</span>
                                    <?php else: ?>
                                        <ul class="list-unstyled mb-0 small">
                                            <?php foreach ($members as $m): ?>
                                                <li class="mb-1">
                                                    <?php echo htmlspecialchars($m['name'] . ' ' . $m['lastName']); ?>
                                                    <a href="index.php?action=removeStudentFromGroup&student_id=<?php echo $m['studentId']; ?>"
                                                        class="text-danger ml-1"
                                                        onclick="return confirm('Αφαίρεση από την ομάδα;')">✖</a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <script>
            function showRenameForm(id) {
                document.getElementById('group-label-' + id).classList.add('d-none');
                document.getElementById('rename-form-' + id).classList.remove('d-none');
            }

            function hideRenameForm(id) {
                document.getElementById('group-label-' + id).classList.remove('d-none');
                document.getElementById('rename-form-' + id).classList.add('d-none');
            }
        </script>
<?php
    }
}
