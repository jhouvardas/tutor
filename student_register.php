<?php
require_once 'DbHandler.php';
require_once 'DbHandler2.php';
require_once 'FormMaker.php';
require_once 'FormMaker2.php';

$db = new DbHandler2();
$form = new FormMaker2();
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Περνάμε τα δεδομένα της φόρμας ($_POST) ως παράμετρο στη μέθοδο
    if ($db->registerStudent($_POST)) {
        $message = "<div class='alert alert-success'>Η εγγραφή σου ολοκληρώθηκε με επιτυχία! Μπορείς πλέον να συνδεθείς.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Υπήρξε κάποιο πρόβλημα κατά την αποθήκευση. Παρακαλώ δοκίμασε ξανά.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <title>Εγγραφή Νέου Μαθητή</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <?php
    // Εμφάνιση της φόρμας μέσω του FormMaker2
    $form->studentRegisterForm($message);
    ?>
</body>

</html>