<?php
include 'DbHandler.php';
session_start();

// Έλεγχος αν ο μαθητής έχει κάνει login. Αν όχι, ανακατεύθυνση στην είσοδο.
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit;
}

$db = new DbHandler();
$student_name = $_SESSION['student_name'];
$student_id = $_SESSION['student_id'];

// Τραβάμε τους βαθμούς του συγκεκριμένου μαθητή
$grades = $db->getStudentGrades($student_id);
?>
<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <title>Tutor 2.0 - Οι Βαθμοί μου</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .grade-card {
            margin-top: 50px;
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead {
            background-color: #007bff;
            color: white;
        }

        .high-grade {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h4>Καλωσόρισες, <span class="text-primary"><?php echo htmlspecialchars($student_name); ?></span></h4>
                    <a href="logout.php" class="btn btn-danger btn-sm">Αποσύνδεση</a>
                </div>

                <div class="card grade-card shadow">
                    <div class="card-header text-center bg-dark text-white">
                        <h5 class="mb-0">Ιστορικό Βαθμολογιών</h5>
                    </div>
                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ημερομηνία</th>
                                        <th>Βοήθημα / Βιβλίο</th>
                                        <th>Αρ. Άσκησης</th>
                                        <th class="text-center">Βαθμός</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($grades && $grades->num_rows > 0): ?>
                                        <?php while ($row = $grades->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($row['date_graded'])); ?></td>
                                                <td><?php echo htmlspecialchars($row['book_source']); ?></td>
                                                <td><?php echo htmlspecialchars($row['exercise_no']); ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-primary p-2" style="font-size: 1rem;">
                                                        <?php echo number_format($row['grade'], 2); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Δεν υπάρχουν ακόμα καταχωρημένοι βαθμοί για εσένα.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <p class="text-center mt-4 text-muted" style="font-size: 0.8rem;">Tutor 2.0 Management System</p>
            </div>
        </div>
    </div>

</body>

</html>