<?php
//require_once 'FormMaker.php';

class FormMaker2 extends FormMaker
{
    // Φόρμα Login για το κεντρικό σύστημα (αν χρειαστεί να καλείται από το tutor2)
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

    // JavaScript Validation που χρησιμοποιείται σε όλες τις φόρμες
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

    // Η κεντρική φόρμα της Τράπεζας Θεμάτων
    public function addQuestionToBankForm($sources)
    {
    ?>
        <div class="container mt-4">
            <h5 class="bg-primary text-white p-3 shadow-sm rounded">Εισαγωγή Νέας Άσκησης στην Τράπεζα</h5>
            <form action="tutor2.php?action=saveQuestion" method="post" enctype="multipart/form-data" class="bg-light p-4 border shadow-sm rounded">

                <div class="form-group">
                    <label><strong>1. Τύπος Άσκησης:</strong></label>
                    <select name="type" id="questionType" class="form-control" onchange="toggleFields()" required>
                        <option value="">Επιλέξτε τύπο...</option>
                        <option value="manual">Δική μου Άσκηση</option>
                        <option value="book">Από Βοήθημα</option>
                        <option value="panellinies">Θέμα Πανελληνίων</option>
                    </select>
                </div>

                <div id="bookFields" style="display:none;" class="alert alert-info border-info">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Επιλογή Βοηθήματος:</label>
                            <select name="book_name" class="form-control">
                                <option value="">-- Επιλέξτε Πηγή --</option>
                                <?php
                                if ($sources && $sources instanceof mysqli_result && $sources->num_rows > 0):
                                    $sources->data_seek(0);
                                    while ($src = $sources->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($src['sourceName']); ?>">
                                            <?php echo htmlspecialchars($src['sourceName']); ?>
                                        </option>
                                <?php endwhile;
                                endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Σελίδα:</label>
                            <input type="text" name="page_number" class="form-control">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Αρ. Άσκησης:</label>
                            <input type="text" name="exercise_number" class="form-control">
                        </div>
                    </div>
                </div>

                <div id="panelliniesFields" style="display:none;" class="alert alert-danger border-danger">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Έτος:</label>
                            <input type="number" name="pan_year" class="form-control" value="<?php echo date("Y"); ?>">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Τύπος:</label>
                            <select name="pan_type" class="form-control">
                                <option value="imerisia">Ημερήσια</option>
                                <option value="esperina">Εσπερινά</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Περίοδος:</label>
                            <select name="pan_session" class="form-control">
                                <option value="regular">Κανονικές</option>
                                <option value="repeat">Επαναληπτικές</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Θέμα:</label>
                            <select name="pan_thema" class="form-control">
                                <option value="A">Θέμα Α</option>
                                <option value="B">Θέμα Β</option>
                                <option value="G">Θέμα Γ</option>
                                <option value="D">Θέμα Δ</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ερώτημα (π.χ. Γ1):</label>
                        <input type="text" name="pan_erotima" class="form-control">
                    </div>
                </div>

                <div id="contentFields" style="display:none;">
                    <hr>
                    <div class="form-group">
                        <label><strong>2. Εκφώνηση / Κείμενο Άσκησης:</strong></label>
                        <textarea name="content_text" class="form-control" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label><strong>3. Μεταφόρτωση Εικόνας:</strong></label>
                        <input type="file" name="image_path" class="form-control-file border p-2 w-100">
                    </div>
                </div>

                <button type="submit" name="submitQuestion" class="btn btn-success btn-lg mt-3 shadow">Αποθήκευση στην Τράπεζα</button>
            </form>
        </div>

        <script>
            function toggleFields() {
                var type = document.getElementById('questionType').value;
                document.getElementById('bookFields').style.display = (type === 'book') ? 'block' : 'none';
                document.getElementById('panelliniesFields').style.display = (type === 'panellinies') ? 'block' : 'none';
                document.getElementById('contentFields').style.display = (type === 'manual' || type === 'panellinies') ? 'block' : 'none';
            }
        </script>
    <?php
    }

    // Φόρμα Δημιουργίας Ομάδας (1-4 μαθητές)
    public function createGroupForm($studentsResource)
    {
        $students = [];
        if ($studentsResource) {
            while ($row = $studentsResource->fetch_assoc()) {
                $students[] = $row;
            }
        }
    ?>
        <div class="container mt-4">
            <h4 class="bg-dark text-white p-3 shadow-sm rounded">Δημιουργία Νέας Ομάδας</h4>
            <form action="tutor2.php?action=saveGroup" method="post" class="border p-4 bg-light shadow-sm">
                <div class="form-group">
                    <label><strong>Όνομα Ομάδας:</strong></label>
                    <input type="text" name="groupName" class="form-control" placeholder="π.χ. Τμήμα Α1" required>
                </div>
                <div class="row mt-3">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="col-md-6 mb-3">
                            <label>Μαθητής <?php echo $i; ?>:</label>
                            <select name="studentIds[]" class="form-control">
                                <option value="">-- Επιλογή μαθητή --</option>
                                <?php foreach ($students as $s): ?>
                                    <option value="<?php echo $s['studentId']; ?>">
                                        <?php echo htmlspecialchars($s['lastName'] . " " . $s['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endfor; ?>
                </div>
                <button type="submit" class="btn btn-primary btn-block shadow mt-3">Αποθήκευση Ομάδας</button>
            </form>
        </div>
    <?php
    }

    // Φόρμα Δημιουργίας Εργασίας (Επιλογή από Τράπεζα)
    public function createAssignmentForm($questionsResource)
    {
    ?>
        <div class="container mt-4">
            <h5 class="bg-warning text-dark p-3 rounded shadow-sm">Δημιουργία Νέας Εργασίας</h5>
            <form action="tutor2.php?action=saveAssignment" method="post" class="bg-light p-4 border shadow-sm">
                <div class="form-group mb-4">
                    <label><strong>Τίτλος Εργασίας:</strong></label>
                    <input type="text" name="assignment_title" class="form-control" required>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover border bg-white">
                        <thead class="thead-dark">
                            <tr>
                                <th>Επιλογή</th>
                                <th>Τύπος</th>
                                <th>Εκφώνηση (Προεπισκόπηση)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($questionsResource): ?>
                                <?php while ($row = $questionsResource->fetch_assoc()): ?>
                                    <tr>
                                        <td><input type="checkbox" name="selected_questions[]" value="<?php echo $row['questionId']; ?>"></td>
                                        <td><span class="badge badge-secondary"><?php echo $row['type']; ?></span></td>
                                        <td><?php echo mb_strimwidth(strip_tags($row['content_text']), 0, 80, "..."); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" name="submitAssignment" class="btn btn-warning btn-block btn-lg shadow mt-3">Αποθήκευση Εργασίας</button>
            </form>
        </div>
    <?php
    }

    // Φόρμα Καταχώρησης Βαθμολογίας
    public function createGradeForm($students, $sources)
    {
    ?>
        <div class="container mt-4">
            <h4 class="bg-primary text-white p-3 rounded shadow-sm">Νέα Καταχώρηση Βαθμολογίας</h4>
            <form action="tutor2.php?action=saveGrade" method="post" class="border p-4 bg-light shadow-sm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Επιλογή Μαθητή:</label>
                        <select name="studentId" class="form-control" required>
                            <option value="">-- Επιλέξτε Μαθητή --</option>
                            <?php if ($students): while ($s = $students->fetch_assoc()): ?>
                                    <option value="<?php echo $s['studentId']; ?>"><?php echo htmlspecialchars($s['lastName'] . " " . $s['name']); ?></option>
                            <?php endwhile;
                            endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Αρ. Άσκησης:</label>
                        <input type="text" name="exercise_no" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Βαθμός (0-20):</label>
                        <input type="number" step="0.1" name="final_score_20" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Πηγή / Βιβλίο:</label>
                    <select name="book_source" class="form-control" required>
                        <option value="">-- Επιλέξτε Πηγή --</option>
                        <?php if ($sources): while ($src = $sources->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($src['sourceName']); ?>"><?php echo htmlspecialchars($src['sourceName']); ?></option>
                        <?php endwhile;
                        endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Σχόλια:</label>
                    <textarea name="comments" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-success btn-block shadow">Αποθήκευση Βαθμολογίας</button>
            </form>
        </div>
    <?php
    }

    // Διαχείριση Πηγών (Βοηθημάτων)
    public function createSourceForm($sources)
    {
    ?>
        <div class="container mt-4">
            <h4 class="bg-info text-white p-3 rounded shadow-sm">Διαχείριση Πηγών / Βιβλίων</h4>
            <form action="tutor2.php?action=saveSource" method="post" class="border p-4 bg-light shadow-sm mb-4">
                <div class="form-group">
                    <label>Όνομα Νέας Πηγής:</label>
                    <div class="input-group">
                        <input type="text" name="sourceName" class="form-control" placeholder="π.χ. Εκδόσεις Πατάκη" required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-info">Προσθήκη</button>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-bordered bg-white shadow-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Όνομα Πηγής</th>
                        <th class="text-center" style="width: 120px;">Ενέργεια</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($sources): while ($row = $sources->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['sourceName']); ?></td>
                                <td class="text-center">
                                    <a href="tutor2.php?action=deleteSource&id=<?php echo $row['sourceId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Διαγραφή;');">Διαγραφή</a>
                                </td>
                            </tr>
                    <?php endwhile;
                    endif; ?>
                </tbody>
            </table>
        </div>
    <?php
    }

    // Η φόρμα για τα PDF των Πανελληνίων
    public function uploadPanelliniesForm()
    {
    ?>
        <div class="card mt-4 shadow-sm border-primary">
            <div class="card-header bg-primary text-white font-weight-bold">Ανάρτηση Θεμάτων Πανελληνίων (PDF)</div>
            <div class="card-body">
                <form action="tutor2.php?action=savePanelliniesPdf" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Έτος:</label>
                            <input type="number" name="exam_year" class="form-control" value="<?php echo date('Y'); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label>Τύπος Σχολείου:</label>
                            <select name="school_type" class="form-control">
                                <option value="Ημερήσιο">Ημερήσιο</option>
                                <option value="Εσπερινό">Εσπερινό</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Τύπος Εξετάσεων:</label>
                            <select name="exam_type" class="form-control">
                                <option value="Κανονικές">Κανονικές</option>
                                <option value="Επαναληπτικές">Επαναληπτικές</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Αρχείο PDF:</label>
                            <input type="file" name="exam_file" class="form-control-file border p-1" accept=".pdf" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 px-4 shadow-sm">Αποθήκευση PDF</button>
                </form>
            </div>
        </div>
<?php
    }
}
