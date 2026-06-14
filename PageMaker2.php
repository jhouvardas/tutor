<?php
//require_once 'PageMaker.php';

class PageMaker2 extends PageMaker
{
    /**
     * Εξειδικευμένο Μενού για το Tutor 2.0
     * Αντικαθιστά το μενού του λογιστηρίου όταν είμαστε στην ακαδημαϊκή εφαρμογή
     */
    public function displayMenu()
    {
?>
        <nav class="navbar navbar-expand-md bg-dark navbar-dark shadow-sm">
            <a class="navbar-brand text-warning font-weight-bold" href="tutor2.php">Tutor 2.0</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav w-100">
                    <li class="nav-item">
                        <a class="nav-link" href="tutor2.php?action=viewPanellinies">📂 Αρχείο PDF</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tutor2.php?action=addQuestion">Bank</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tutor2.php?action=listAssignments">Εργασίες</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tutor2.php?action=manageGroups">Ομάδες</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tutor2.php?action=manageGrades">Βαθμοί</a>
                    </li>

                    <li class="nav-item ml-auto">
                        <a class="nav-link btn btn-outline-info btn-sm text-white px-3" href="index.php">
                            🔙 Επιστροφή στο Λογιστήριο
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Έξοδος</a>
                    </li>
                </ul>
            </div>
        </nav>
    <?php
    }

    // Μέθοδος για την αναζήτηση ασκήσεων μαθητών
    public function displayNewStudentAskiseisSearch()
    {
        echo '<a href="index.php?action=studentsAskiseis" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
    }

    // Η μεγάλη μέθοδος για την εκτύπωση της εργασίας (με το CSS και το Layout)
    public function displayAssignmentForPrint($title, $questionsResource)
    {
    ?>
        <div id="printableAssignment" class="p-5 bg-white border shadow-sm mx-auto mt-3" style="max-width: 800px; font-family: 'Times New Roman', serif; min-height: 29.7cm;">
            <div class="row mb-4 no-print">
                <div class="col-6"><strong>Ονοματεπώνυμο:</strong> ...........................................................</div>
                <div class="col-6 text-right"><strong>Ημερομηνία:</strong> ...... / ...... / 202...</div>
            </div>

            <div class="text-center mb-5 border-bottom pb-3">
                <h3 class="text-uppercase">Εργασία Πληροφορικής</h3>
                <h4 class="text-primary"><?php echo htmlspecialchars($title); ?></h4>
            </div>

            <?php
            $count = 1;
            if ($questionsResource && $questionsResource->num_rows > 0) {
                while ($row = $questionsResource->fetch_assoc()): ?>
                    <div class="question-item mb-5" style="page-break-inside: avoid;">
                        <h5 class="border-left border-dark pl-2" style="background-color: transparent;"><strong>ΘΕΜΑ <?php echo $count++; ?></strong></h5>

                        <?php if ($row['type'] == 'panellinies'): ?>
                            <small class="text-danger font-weight-bold">
                                (ΠΑΝΕΛΛΗΝΙΕΣ <?php echo $row['pan_year']; ?> - <?php echo $row['pan_type']; ?>)
                            </small>
                        <?php elseif ($row['type'] == 'book'): ?>
                            <small class="text-info font-weight-bold">
                                (Βοήθημα: <?php echo $row['book_name']; ?>, σελ. <?php echo $row['page_number']; ?>)
                            </small>
                        <?php endif; ?>

                        <div class="mt-3 lead">
                            <?php echo $row['content_text']; ?>
                        </div>

                        <?php if (!empty($row['image_path'])): ?>
                            <div class="text-center mt-3">
                                <img src="<?php echo $row['image_path']; ?>" class="img-fluid border p-2" style="max-height: 450px;">
                            </div>
                        <?php endif; ?>
                    </div>
            <?php endwhile;
            } else {
                echo "<p class='text-center'>Δεν βρέθηκαν ασκήσεις για αυτή την εργασία.</p>";
            }
            ?>

            <div class="mt-5 pt-4 text-center border-top no-print">
                <p class="text-muted"><em>Καλή επιτυχία και καλό διάβασμα!</em></p>
            </div>
        </div>

        <div class="text-center mt-4 mb-5 no-print">
            <button onclick="window.print();" class="btn btn-danger btn-lg px-5 shadow">
                Εξαγωγή σε PDF / Εκτύπωση
            </button>
        </div>

        <style>
            @media print {

                .no-print,
                .navbar,
                .btn,
                .dropdown-menu {
                    display: none !important;
                }

                body {
                    background: white !important;
                    margin: 0;
                    padding: 0;
                }

                #printableAssignment {
                    border: none !important;
                    box-shadow: none !important;
                    width: 100% !important;
                    max-width: none !important;
                    margin: 0 !important;
                    padding: 1cm !important;
                }

                a {
                    text-decoration: none;
                    color: black;
                }
            }
        </style>
    <?php
    }

    // Λίστα όλων των εργασιών
    public function displayAssignmentsList($assignmentsResource)
    {
    ?>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Οι Εργασίες μου</h2>
                <a href="tutor2.php?action=createAssignment" class="btn btn-success">+ Νέα Εργασία</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover border">
                    <thead class="thead-dark">
                        <tr>
                            <th>α/α</th>
                            <th>Τίτλος Εργασίας</th>
                            <th>Ημερομηνία Δημιουργίας</th>
                            <th class="text-center">Ενέργειες</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if ($assignmentsResource) {
                            while ($row = $assignmentsResource->fetch_assoc()):
                                $date = date_create($row['date_created']);
                        ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                    <td><?php echo date_format($date, "d/m/Y H:i"); ?></td>
                                    <td class="text-center">
                                        <a href="tutor2.php?action=viewAssignment&id=<?php echo $row['assignmentId']; ?>" class="btn btn-info btn-sm">Προβολή / PDF</a>
                                        <a href="tutor2.php?action=deleteAssignment&id=<?php echo $row['assignmentId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Σίγουρα διαγραφή;')">Διαγραφή</a>
                                    </td>
                                </tr>
                        <?php endwhile;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }

    // Λίστα των PDF Πανελληνίων
    public function displayPanelliniesList($panelliniesResource)
    {
    ?>
        <div class="card mt-4 shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="background-color: transparent;">Αρχείο Θεμάτων Πανελληνίων</h5>
                <span class="badge badge-light"><?php echo ($panelliniesResource) ? $panelliniesResource->num_rows : 0; ?> Αρχεία</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Έτος</th>
                                <th>Τύπος Σχολείου</th>
                                <th>Εξετάσεις</th>
                                <th>Περιγραφή</th>
                                <th class="text-center">Ενέργεια</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($panelliniesResource && $panelliniesResource->num_rows > 0): ?>
                                <?php while ($row = $panelliniesResource->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo $row['exam_year']; ?></strong></td>
                                        <td><?php echo $row['school_type']; ?></td>
                                        <td>
                                            <span class="badge <?php echo ($row['exam_type'] == 'Κανονικές') ? 'badge-success' : 'badge-warning'; ?>">
                                                <?php echo $row['exam_type']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo $row['file_path']; ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-file-pdf"></i> Άνοιγμα PDF
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Δεν έχουν ανέβει ακόμα θέματα.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php
    }
}
