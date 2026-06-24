<?php

trait TimeTableFormTrait
{
    public function addTimeTableForm()
    {
        $db = new DbHandler();
        $groups = $db->getGroups();
?>
        <h5>Προγραμματισμός μαθήματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <div class="form-group">
                    <label>Προγραμματισμός για:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scheduleFor" id="sf_student" value="student" checked onchange="toggleScheduleTarget()">
                            <label class="form-check-label" for="sf_student">Μαθητή</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scheduleFor" id="sf_group" value="group" onchange="toggleScheduleTarget()">
                            <label class="form-check-label" for="sf_group">Ομάδα</label>
                        </div>
                    </div>
                </div>
                <div id="student_selector">
                    <?php $this->selectStudent(); ?>
                </div>
                <div id="group_selector" class="d-none form-group">
                    <label>Ομάδα:</label>
                    <select name="group_id" id="group_id" class="form-control">
                        <option value="">Επιλέξτε ομάδα</option>
                        <?php foreach ($groups as $g): ?>
                            <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['group_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dateFrom">Από Ημερομηνία:</label>
                            <input type="date" class="form-control" id="dateFrom" name="dateFrom" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="toDate">Μέχρι Ημερομηνία (Απαραίτητο):</label>
                            <input type="date" class="form-control" id="toDate" name="toDate" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>1η Ημέρα Μαθήματος</h6>
                        <div class="form-group">
                            <label for="day1">Ημέρα:</label>
                            <select class="form-control" id="day1" name="day1" required>
                                <option value="">Επιλέξτε ημέρα</option>
                                <option value="1">Δευτέρα</option>
                                <option value="2">Τρίτη</option>
                                <option value="3">Τετάρτη</option>
                                <option value="4">Πέμπτη</option>
                                <option value="5">Παρασκευή</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="timeFrom1">Ώρα Έναρξης (διάρκεια 1.5 ώρα):</label>
                            <input type="time" class="form-control" id="timeFrom1" name="timeFrom1" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>2η Ημέρα Μαθήματος</h6>
                        <div class="form-group">
                            <label for="day2">Ημέρα:</label>
                            <select class="form-control" id="day2" name="day2">
                                <option value="">Επιλέξτε ημέρα (Προαιρετικό)</option>
                                <option value="1">Δευτέρα</option>
                                <option value="2">Τρίτη</option>
                                <option value="3">Τετάρτη</option>
                                <option value="4">Πέμπτη</option>
                                <option value="5">Παρασκευή</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="timeFrom2">Ώρα Έναρξης (διάρκεια 1.5 ώρα):</label>
                            <input type="time" class="form-control" id="timeFrom2" name="timeFrom2">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3" name="setTimeTable">Υποβολή</button>
            </form>
        </div>
        <script>
            function toggleScheduleTarget() {
                var isGroup = document.getElementById('sf_group').checked;
                document.getElementById('student_selector').classList.toggle('d-none', isGroup);
                document.getElementById('group_selector').classList.toggle('d-none', !isGroup);
                document.querySelector('#student_selector select').required = !isGroup;
                document.getElementById('group_id').required = isGroup;
            }
        </script>
    <?php
    }

    public function getTimeTableForm()
    {
        $db = new DbHandler();
        $groups = $db->getGroups();
    ?>
        <h5>Αναζήτηση προγράμματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <div class="form-group">
                    <label>Αναζήτηση για:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="searchFor" id="search_student" value="student" checked onchange="toggleSearchTarget()">
                            <label class="form-check-label" for="search_student">Μαθητή</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="searchFor" id="search_group" value="group" onchange="toggleSearchTarget()">
                            <label class="form-check-label" for="search_group">Ομάδα</label>
                        </div>
                    </div>
                </div>
                <div id="search_student_selector">
                    <?php $this->selectStudent(); ?>
                </div>
                <div id="search_group_selector" class="d-none form-group">
                    <label>Ομάδα:</label>
                    <select name="group_id" id="search_group_id" class="form-control">
                        <option value="">Επιλέξτε ομάδα</option>
                        <?php foreach ($groups as $g): ?>
                            <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['group_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="search_student_btn">
                    <button type="submit" class="btn btn-success" name="findTimeTable">Υποβολή</button>
                </div>
                <div id="search_group_btn" class="d-none">
                    <button type="submit" class="btn btn-success" name="findGroupTimeTable">Υποβολή</button>
                </div>
            </form>
        </div>
        <script>
            function toggleSearchTarget() {
                var isGroup = document.getElementById('search_group').checked;
                document.getElementById('search_student_selector').classList.toggle('d-none', isGroup);
                document.getElementById('search_group_selector').classList.toggle('d-none', !isGroup);
                document.getElementById('search_student_btn').classList.toggle('d-none', isGroup);
                document.getElementById('search_group_btn').classList.toggle('d-none', !isGroup);
                document.querySelector('#search_student_selector select').required = !isGroup;
                document.getElementById('search_group_id').required = isGroup;
            }
        </script>
    <?php
    }

    public function selectGroupTimeTableForm($groupTimetable, $groupId)
    {
        $greekDays = ['Monday' => 'Δευτέρα', 'Tuesday' => 'Τρίτη', 'Wednesday' => 'Τετάρτη', 'Thursday' => 'Πέμπτη', 'Friday' => 'Παρασκευή', 'Saturday' => 'Σάββατο', 'Sunday' => 'Κυριακή'];
    ?>
        <h5>Προγραμματισμός Ομάδας</h5>
        <?php if (empty($groupTimetable)): ?>
            <div class="alert alert-info">Δεν βρέθηκαν μαθήματα για αυτή την ομάδα.</div>
            <a href="index.php?action=editTimeTable" class="btn btn-secondary">Επιστροφή</a>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Μαθητής</th>
                            <th>Ημέρα</th>
                            <th>Ημερομηνία</th>
                            <th>Ώρα</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupTimetable as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name'] . ' ' . $row['lastName']); ?></td>
                                <td><?php echo $greekDays[date('l', strtotime($row['date']))]; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['date'])); ?></td>
                                <td><?php echo date('H:i', strtotime($row['timeFrom'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" onsubmit="return confirm('Να διαγραφεί ΟΛΟ το πρόγραμμα για ΟΛΟΥΣ τους μαθητές της ομάδας από σήμερα και μετά;');">
                <input type="hidden" name="group_id" value="<?php echo (int)$groupId; ?>">
                <button type="submit" class="btn btn-danger" name="deleteGroupTimeTable">🗑️ Διαγραφή ΟΛΟΥ του προγράμματος ομάδας</button>
            </form>
            <a href="index.php?action=editTimeTable" class="btn btn-secondary ml-2 mt-1">Επιστροφή</a>
        <?php endif; ?>
    <?php
    }

    public function selectTimeTableForm($timeTableResource)
    {
        $greekDays = ['Monday' => 'Δευτέρα', 'Tuesday' => 'Τρίτη', 'Wednesday' => 'Τετάρτη', 'Thursday' => 'Πέμπτη', 'Friday' => 'Παρασκευή', 'Saturday' => 'Σάββατο', 'Sunday' => 'Κυριακή'];
    ?>
        <h5>Επιλογή μαθήματος προγράμματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <div class="form-group">
                    <label for="timeTableId">Μάθημα:</label>
                    <select class="form-control" id="timeTableId" name="timeTableId" required>
                        <?php
                        while ($row = $timeTableResource->fetch_assoc()) {
                            $date = date_create($row['date']);
                            $dayName = $greekDays[date_format($date, "l")];
                            $time = $row['timeFrom'];
                            echo '<option value="' . $row['timeTableId'] . '">' . $dayName . ' ' . date_format($date, "d/m/y") . '  ' . date('H:i', strtotime($time)) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="submit" class="btn btn-success btn-block" name="editOne">✏️ Διόρθωση</button>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-danger btn-block" name="deleteOneTimeTable" onclick="return confirm('Να διαγραφεί μόνο αυτό το μάθημα;');">🗑️ Διαγραφή ενός</button>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-danger btn-block" name="deleteTimeTable" onclick="return confirm('Να διαγραφούν ΟΛΑ τα επαναλαμβανόμενα μαθήματα από εδώ και πέρα;');">🗑️ Διαγραφή όλων</button>
                    </div>
                </div>
            </form>
        </div>
    <?php
    }

    public function editTimeTableForm($lessonTimeTableResource)
    {
        $row = $lessonTimeTableResource->fetch_assoc();
        $db = new DbHandler();
        $studentName = $db->getStudentName($row['studentId']);
        $greekDays = ['Monday' => 'Δευτέρα', 'Tuesday' => 'Τρίτη', 'Wednesday' => 'Τετάρτη', 'Thursday' => 'Πέμπτη', 'Friday' => 'Παρασκευή', 'Saturday' => 'Σάββατο', 'Sunday' => 'Κυριακή'];
        $dayName = $greekDays[date('l', strtotime($row['date']))];
    ?>
        <h5>Διόρθωση προγράμματος — <?php echo $studentName; ?></h5>
        <p class="text-muted">Επιλεγμένο μάθημα: <strong><?php echo $dayName . ' ' . date('d/m/Y', strtotime($row['date'])) . ' στις ' . date('H:i', strtotime($row['timeFrom'])); ?></strong></p>

        <div class="container">

            <!-- Φόρμα 1: Διόρθωση μόνο αυτού του μαθήματος -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">✏️ Διόρθωση μόνο αυτού του μαθήματος</div>
                <div class="card-body">
                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" onsubmit="return confirm('Να αλλαχθεί μόνο αυτό το μάθημα;');">
                        <input type="hidden" name="timeTableId" value="<?php echo $row['timeTableId']; ?>">
                        <div class="form-group">
                            <label for="date">Νέα Ημερομηνία:</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo $row['date']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="timeFrom1">Νέα Ώρα έναρξης (διάρκεια 1.5 ώρα):</label>
                            <input type="time" class="form-control" id="timeFrom1" name="timeFrom" value="<?php echo $row['timeFrom']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success" name="updateOneTimeTable">Αποθήκευση</button>
                    </form>
                </div>
            </div>

            <!-- Φόρμα 2: Αλλαγή ώρας σε όλα τα επαναλαμβανόμενα -->
            <div class="card mb-4">
                <div class="card-header bg-warning">🔁 Αλλαγή ώρας σε όλα τα επαναλαμβανόμενα (<?php echo $dayName; ?>)</div>
                <div class="card-body">
                    <p class="text-muted small">Θα αλλάξει η ώρα για <strong>όλα</strong> τα μαθήματα της <?php echo $dayName; ?> από <strong><?php echo date('d/m/Y', strtotime($row['date'])); ?></strong> και μετά.</p>
                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" onsubmit="return confirm('Να αλλαχθεί η ώρα σε ΟΛΑ τα μαθήματα της <?php echo $dayName; ?> από αυτή την ημερομηνία και μετά;');">
                        <input type="hidden" name="studentId" value="<?php echo $row['studentId']; ?>">
                        <input type="hidden" name="date" value="<?php echo $row['date']; ?>">
                        <div class="form-group">
                            <label for="timeFrom2">Νέα Ώρα έναρξης (διάρκεια 1.5 ώρα):</label>
                            <input type="time" class="form-control" id="timeFrom2" name="timeFrom" value="<?php echo $row['timeFrom']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-warning" name="updateTimeTable">Αποθήκευση σε όλα</button>
                    </form>
                </div>
            </div>

        </div>
    <?php
    }

    public function showTimeTableForm()
    {
    ?>
        <h5>Αναζήτηση προγράμματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <div class="form-group">
                    <label for="date">Ημερομηνία:</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <button type="submit" class="btn btn-success" name="showTimeTable">Υποβολή</button>
            </form>
        </div>
<?php
    }
}
