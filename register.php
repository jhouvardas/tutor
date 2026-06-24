<?php
session_start();
require_once 'DbHandler.php';
require_once 'MailMaker.php';

$db = new DbHandler();
$mail = new MailMaker();

if (empty($_SESSION['csrf_register'])) {
    $_SESSION['csrf_register'] = bin2hex(random_bytes(16));
}

$submitted = false;
$result = null;
$emailError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_register']) {
        $result = false;
    } elseif (!filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL)) {
        $emailError = true;
    } else {
        $result = $db->registerStudent($_POST);
    }

    if ($result === true) {
        $studentName  = htmlspecialchars(trim($_POST['name']) . ' ' . trim($_POST['lastName']));
        $studentEmail = htmlspecialchars(trim($_POST['email']));
        $phone        = htmlspecialchars(trim($_POST['phone']));
        $birthday     = htmlspecialchars(trim($_POST['birthday']));
        $target       = htmlspecialchars(trim($_POST['target']));
        $schoolYear   = (date('n') >= 8) ? (int)date('Y') + 1 : (int)date('Y');

        $subject = "Νέα Εγγραφή Μαθητή: $studentName";
        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                <h2 style='color: #007bff;'>Νέα Εγγραφή Μαθητή</h2>
                <p>Ένας νέος μαθητής μόλις συμπλήρωσε φόρμα εγγραφής.</p>
                <div style='padding: 15px; background-color: #f8f9fa; border-radius: 5px;'>
                    <p><strong>Ονοματεπώνυμο:</strong> $studentName</p>
                    <p><strong>Email:</strong> $studentEmail</p>
                    <p><strong>Τηλέφωνο:</strong> $phone</p>
                    <p><strong>Ημ/νία Γέννησης:</strong> $birthday</p>
                    <p><strong>Σχολή Προτίμησης:</strong> $target</p>
                    <p><strong>Σχολικό Έτος:</strong> $schoolYear</p>
                </div>
                <p style='margin-top:15px;'>Μπορείτε να τον διαχειριστείτε από το <a href='https://www.jhouv.eu/tutor/' style='color:#007bff;'>tutor</a>.</p>
            </div>
        ";
        $mail->sendMail($body, 'jhouvardas@jhouv.eu', $subject);
        $submitted = true;
    }
}

?>
<!DOCTYPE html>
<html lang="el">

<head>
    <title>Εγγραφή Μαθητή</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="myCSS.css">
    <link rel="icon" href="images/favicon.jpg" sizes="16x16" type="image/jpg">
</head>

<body class="bg-light text-dark">

    <div class="container mt-5 mb-5">

        <?php if ($submitted): ?>
            <div class="card shadow border-success mx-auto" style="max-width: 500px; border-radius: 15px;">
                <div class="card-header bg-success text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
                    <h4 class="mb-0">Εγγραφή Επιτυχής!</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <p class="lead">Τα στοιχεία σας καταχωρήθηκαν. Θα επικοινωνήσω σύντομα μαζί σας.</p>
                    <a href="register.php" class="btn btn-outline-success">Νέα Εγγραφή</a>
                </div>
            </div>

        <?php elseif ($result === "email_exists"): ?>
            <div class="card shadow border-warning mx-auto" style="max-width: 500px; border-radius: 15px;">
                <div class="card-header bg-warning text-dark text-center py-3" style="border-radius: 15px 15px 0 0;">
                    <h4 class="mb-0">Το Email υπάρχει ήδη</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <p>Υπάρχει ήδη εγγραφή με αυτό το email. Επικοινωνήστε μαζί μου αν χρειάζεστε βοήθεια.</p>
                    <a href="register.php" class="btn btn-warning">Επιστροφή</a>
                </div>
            </div>

        <?php elseif ($emailError): ?>
            <div class="card shadow border-warning mx-auto" style="max-width: 500px; border-radius: 15px;">
                <div class="card-header bg-warning text-dark text-center py-3" style="border-radius: 15px 15px 0 0;">
                    <h4 class="mb-0">Μη έγκυρο Email</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <p>Παρακαλώ εισάγετε έγκυρη διεύθυνση email.</p>
                    <a href="register.php" class="btn btn-warning">Επιστροφή</a>
                </div>
            </div>

        <?php elseif ($result === false && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="card shadow border-danger mx-auto" style="max-width: 500px; border-radius: 15px;">
                <div class="card-header bg-danger text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
                    <h4 class="mb-0">Σφάλμα</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <p>Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε ξανά ή επικοινωνήστε μαζί μου.</p>
                    <a href="register.php" class="btn btn-danger">Επιστροφή</a>
                </div>
            </div>

        <?php else: ?>
            <div class="card shadow border-primary mx-auto" style="max-width: 500px; border-radius: 15px;">
                <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
                    <h4 class="mb-0">Εγγραφή Νέου Μαθητή</h4>
                </div>
                <div class="card-body p-4">
                    <form action="register.php" method="POST" autocomplete="off">
                        <div class="form-group">
                            <label class="font-weight-bold">Όνομα</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Επώνυμο</label>
                            <input type="text" name="lastName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Τηλέφωνο</label>
                            <input type="text" name="phone" class="form-control" placeholder="π.χ. 69...">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Ημερομηνία Γέννησης</label>
                            <input type="date" name="birthday" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Σχολή Προτίμησης</label>
                            <input type="text" name="target" class="form-control" placeholder="π.χ. Πληροφορική ΑΠΘ">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Προσωπικός Κωδικός PIN <small class="text-muted">(για σύνδεση στην πλατφόρμα ασκήσεων)</small></label>
                            <input type="text" name="student_password" class="form-control" minlength="6" maxlength="6" pattern="\d{6}" title="Ο κωδικός πρέπει να αποτελείται ακριβώς από 6 αριθμούς." placeholder="Ακριβώς 6 ψηφία (π.χ. 123456)" inputmode="numeric">
                        </div>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_register']; ?>">
                        <button type="submit" name="register" class="btn btn-primary btn-block btn-lg font-weight-bold">Ολοκλήρωση Εγγραφής</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>