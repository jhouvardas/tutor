<?php
include 'DbHandler.php';
session_start();
$db = new DbHandler();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Έλεγχος στο DbHandler αν υπάρχει ο μαθητής με αυτό το email και password
    $student = $db->checkStudentLogin($email, $pass);

    if ($student) {
        $_SESSION['student_id'] = $student['studentId'];
        $_SESSION['student_name'] = $student['name'];
        header("Location: student_portal.php");
        exit;
    } else {
        $error = "Λάθος email ή κωδικός πρόσβασης.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Είσοδος Μαθητή</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="bg-dark text-white">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4 border p-4 bg-light text-dark shadow rounded">
                <h3 class="text-center">Είσοδος Μαθητή</h3>
                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="post">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Είσοδος</button>
                </form>
                <div class="text-center mt-3">
                    <a href="student_register.php" class="text-muted">Δεν έχεις λογαριασμό; Κάνε Εγγραφή</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>