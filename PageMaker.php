<?php
require_once 'DbHandler.php';
$db = new DbHandler();

class PageMaker {

    public function displayHeadMatter() {
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
                <script src="myJavaScripts.js.js"></script> 
                <script src="https://cdn.tiny.cloud/1/00egprfeg5a0fti37lygyyjkx7k4qrv5y3mm1d208ebhi99j/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
                <script>
                    tinymce.init({
                        selector: 'textarea',
                        force_br_newlines: true,
                        force_p_newlines: false,
                        forced_root_block: '',
                        entity_encoding: "raw",
                        init_instance_callback: function (editor) {
                            var freeTiny = document.querySelector('.tox .tox-notification--in');
                            freeTiny.style.display = 'none';
                        }
                    });
                </script>
                
            </head>
            <body class="bg-light text-dark">
                <?php
            }

            public function displayMenu() {
                ?>
                <nav class="navbar navbar-expand-md bg-dark navbar-dark">
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
                                <a class="nav-link" href="index.php?action=lesson">Μάθημα</a>
                            </li>    
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="index.php?action=debit" id="navbardrop" data-toggle="dropdown">
                                    Ασκήσεις
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="index.php?action=panellinies">Πανελλήνιες</a>
                                    <a class="dropdown-item" href="index.php?action=theoria">Θεωρία</a>
                                    <a class="dropdown-item" href="index.php?action=ergasia">Εργασία</a>
                                </div>

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
                                    <a class="dropdown-item" href="index.php?action=studentsAskiseis">Ασκήσεις</a>
                                    <a class="dropdown-item" href="index.php?action=studentsPanellinies">Πανελλήνιες</a>
                                    <a class="dropdown-item" href="index.php?action=studentsTheoria">Θεωρία</a>
                                    <a class="dropdown-item" href="index.php?action=displayAskiseisGroup">Ομάδες Ασκήσεων</a>
                                    <a class="dropdown-item" href="index.php?action=displayPanelliniesGroup">Ομάδες Πανελληνίων</a>
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
                                    <a class="dropdown-item" href="index.php?action=addTimeTable">Προγραμματισμός Μαθημάτων</a>
                                    <a class="dropdown-item" href="index.php?action=editTimeTable">Διόρθωση προγραμματισμού</a>
                                    <a class="dropdown-item" href="index.php?action=deleteLesson">Διαγραφή μαθήματος</a>
                                    <a class="dropdown-item" href="index.php?action=deletePayment">Διαγραφή πληρωμής</a>
                                    <a class="dropdown-item" href="index.php?action=editNote">Διόρθωση Σημείωσης</a>
                                    <a class="dropdown-item" href="index.php?action=addAskiseisGroup">Νέα Ομάδα ασκήσεων</a>  
                                    <a class="dropdown-item" href="index.php?action=addAskiseisToGroup">Ασκήσεις σε ομάδα</a>
                                    <a class="dropdown-item" href="index.php?action=addPanelliniesToGroup">Πανελλήνιες σε ομάδα</a
                                </div>
                           </li>
                        </ul>
                    </div>  
                </nav>
                <?php
            }

            public function displayStudentLessons($studentLessonsResource) {
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
                                echo'<td>' . $i . '</td>';
                                $date = date_create($row['date']);
                                echo '<td>' . date_format($date, "D d/m/y") . '</td>';
                                echo'<td>' . $row['name'] . '</td>';
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

            public function displayStudentNotes($studentNotesResource) {
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
            
            public function displayOneDayTimeTable($timeTableResource) {
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
                            echo '<td>'. date ('H:i',strtotime($time)).'</td>';
                            echo '<td>'. $row['name'] .' '.$row['lastName']. '</td>';
                            echo '</tr>';                            
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }


            public function displayStudentApousies($studentApousiesResource) {
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

            public function displayStudentMathimataApousies($studentMathimataApousiesResource) {
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

            public function displayPayments($studentPaymentsResource) {
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

            public function displayStudentsDetails($studentsResource) {
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
                                echo'<td>' . $row['name'] . '</td>';
                                echo '<td>' . $row['lastName'] . '</td>';
                                echo '<td><a href="tel:' . $row['telephone'] . '">' . $row['telephone'] . '</a></td>';
                                $email = $row['email'];
                                echo '<td> <a href="mailto:'.$email.'">'.$email.'</a></td>';
                                echo '</tr>';
                                $i++;
                            }
                            ?>                                
                        </tbody>
                    </table>
                </div>
                <?php
            }

            public function displayStudentsAskiseis($askiseisResource) {
                ?>
                <div class="table-responsive-sm">
                    <table class="table table-borderless table-striped">
                        <thead class="table-success">
                            <tr >
                                <th>α/α</th>
                                <th>Ημερ.</th>
                                <th>Όνομα</th>
                                <th>Ασκήσ.</th>
                                <th></th>
                                <th>Βιβλ.</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            <?php
                            $i = 1;
                            while ($row = $askiseisResource->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $i . '</td>';
                                $date = date_create($row['date']);
                                echo '<td>' . date_format($date, " d/m/y") . '</td>';
                                echo'<td>' . $row['name'] . '</td>';
                                echo'<td>' . number_format($row['askisi'], 2) . '</td>';
                                echo'<td>' . $row['location'] . '</td>';
                                echo'<td>' . $row['askiseisSource'] . '</td>';
                                echo '</tr>';
                                $i++;
                            }
                            ?> 
                            <tr class="table-success">
                                <th colspan="6">Καλό Διάβασμα</th>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php
            }

            public function displayAskiseisArray($arrayOfAskiseis) {
                $askisi = new DbHandler;
                ?>
                <!--<div class="container">-->
                <div class="table-responsive-sm">
                    <table class="table table-borderless table-striped">
                        <thead class="table-success">
                            <tr><th>Άσκηση</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $arrlength = count($arrayOfAskiseis);
                            for ($i = 0; $i < $arrlength; $i++) {
                                echo '<tr>';
                                session_start();
                                $_SESSION['deleteAskisi'] = $i;
                                echo '<td>' . $arrayOfAskiseis[$i] . '</td>' . '<td>'
                                ?><img src="images/trash-fill.svg" class="float-end"><?php
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!--</div>-->
                <?php
            }

            public function displayErgasiaArray($arrayOfAskiseis, $name) {
                $askisi = new DbHandler;
                ?>
                <!--<div class="container">-->
                <div class="table-responsive-sm">
                    <table class="table table-borderless table-striped">
                        <thead class="table-success">
                            <?php $date = date_create($_SESSION['date']); ?>
                            <tr><th>Ασκήσεις</th><td><?php
                                    echo date_format($date, " d/m/y");
                                    _
                                    ?></td></tr>
                            <tr><th>Όνομα</th><td><?php
                                    echo $name;
                                    _
                                    ?></td></tr>
                            <tr><th>Τοποθεσία</th><td><?php
                                    echo $_SESSION['location'];
                                    _
                                    ?></td></tr>
                            <tr><th>Βιβλίο</th><td><?php
                                    echo $_SESSION['askiseisSource'];
                                    _
                                    ?></td></tr>
                        </thead>
                        <tbody>
                            <tr><th>α/α</th><th>Άσκηση</th></tr>
                            <?php
                            $arrlength = count($arrayOfAskiseis);
                            for ($i = 0; $i < $arrlength; $i++) {
                                echo '<tr>';
                                echo '<td>' . ($i + 1) . '</td><td>' . $arrayOfAskiseis[$i] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            <tr class="table-success"><th colspan="2"><p class="text-center">Καλό διάβασμα</p></th></tr>
                        </tbody>
                    </table>
                </div>
                <!--</div>-->
                <?php
            }
            
            public function displayAskiseisInGroup($askiseisResource, $name) {
                $askiseis = new DbHandler;
                ?>
                <!--<div class="container">-->
                <div class="table-responsive-sm">
                    <table class="table table-borderless table-striped">
                        <thead class="table-success">
                            <?php $date = date_create($_SESSION['date']); ?>
                            <tr><th>Ασκήσεις</th><td><?php
                                    echo date_format($date, " d/m/y");
                                    _
                                    ?></td></tr>
                            <tr><th>Όνομα</th><td><?php
                                    echo $name;
                                    _
                                    ?></td></tr>                           
                            
                        </thead>
                        <tbody>
                            <tr><th>α/α</th><th>Άσκηση</th></tr>
                            <?php
                            $i=0;
                            while ($row = $askiseisResource->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . (++$i) . '</td><td>' . $row['askisi'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            <tr class="table-success"><th colspan="2"><p class="text-center">Καλό διάβασμα</p></th></tr>
                        </tbody>
                    </table>
                </div>
                <!--</div>-->
                <?php
            }


            public function displayErgasiaArray01($arrayOfAskiseis, $name) {
                $askisi = new DbHandler;
                ?>
                <!--<div class="container">-->
                <div class="table-responsive-sm">
                    <table class="table table-borderless table-striped">
                        <thead class="table-success">
                            <?php $date = date_create($_SESSION['date']); ?>
                            <tr><th>Ασκήσεις</th><td><?php echo date_format($date, " d/m/y"); ?></td></tr>
                            <tr><th>Όνομα</th><td><?php echo $name; ?></td></tr>
                            <tr><th>Τοποθεσία</th><td><?php echo $_SESSION['location']; ?></td></tr>
                            <tr><th>Βιβλίο</th><td><?php echo $_SESSION['askiseisSource']; ?></td></tr>
                        </thead>
                        <tbody>
                            <tr><th>α/α</th><th>Άσκηση</th></tr>
                            <?php
                            $arrlength = count($arrayOfAskiseis);
                            for ($i = 0; $i < $arrlength; $i++) {
                                echo '<tr>';
                                echo '<td>' . ($i + 1) . '</td><td>' . $arrayOfAskiseis[$i] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            <tr class="table-success"><th colspan="2"><p class="text-center">Καλό διάβασμα</p></th></tr>
                        </tbody>
                    </table>
                </div>
                <!--</div>-->
                <?php
            }

            public function displayStudentTheoria($theoriaResource) {
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
                                echo'<td>' . $row['name'] . '</td>';
                                echo'<td>' . $row['book'] . '</td>';
                                echo'<td>' . $row['chapter'] . '</td>';
                                echo'<td>' . $row['comment'] . '</td>';
                                echo '</tr>';
                                $i++;
                            }
                            ?>                                
                        </tbody>
                    </table>
                </div>
                <?php
            }

            public function displayStudentsPanellinies($panelliniesResource) {
                ?>
                <div class="table-responsive-sm">
                    <table class="table table-borderless table-striped">
                        <thead class="table-success">
                            <tr>
                                <th></th>
                                <th>Ημερ.</th>
                                <th>Όνομα</th>                                
                                <th>Χρον</th>
                                <th>Λύκειο</th>
                                <th>Θέμ</th>
                                <th>Ερώτ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>                            
                            <?php
                            $i = 1;
                            while ($row = $panelliniesResource->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $i . '</td>';
                                $date = date_create($row['date']);
                                echo '<td>' . date_format($date, " d/m/y") . '</td>';
                                echo'<td>' . $row['name'] . '</td>';
                                echo'<td>' . $row['panelliniesYear'] . '</td>';
                                echo'<td>' . $row['lykeio'] . '</td>';
                                echo'<td>' . $row['thema'] . '</td>';
                                echo'<td>' . $row['erotima'] . '</td>';
                                echo'<td>' . $row['location'] . '</td>';
                                echo '</tr>';
                                $i++;
                            }
                            ?>                                
                        </tbody>
                    </table>
                </div>
                <?php
            }
            
            public function displayPanelliniesGroup($groupResource) {
                ?>
                <div class="table-responsive-sm">
                    <table class="table table-borderless table-striped">
                        <thead class="table-success">
                            <tr>
                                <th></th>
                                
                                                           
                                <th>Χρον</th>
                                <th>Λύκειο</th>
                                <th>Θέμ</th>
                                <th>Ερώτ</th>
                                
                            </tr>
                        </thead>
                        <tbody>                            
                            <?php
                            $i = 1;
                            while ($row = $groupResource->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $i . '</td>';
                                
                               
                                echo'<td>' . $row['panelliniesYear'] . '</td>';
                                echo'<td>' . $row['lykeio'] . '</td>';
                                echo'<td>' . $row['thema'] . '</td>';
                                echo'<td>' . $row['erotima'] . '</td>';
                               
                                echo '</tr>';
                                $i++;
                            }
                            ?>                                
                        </tbody>
                    </table>
                </div>
                <?php
            }


            public function displayStudentsBalance($studentsResource) {
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
                                echo '<tr>';
//                                    echo'<td>' . $i . '</td>';
                                echo '<td>' . $row['lastName'] . '</td>';
                                echo'<td>' . $row['name'] . '</td>';
                                $bal = $row['dur'] * 10 - $row['pay'];
                                echo '<td>' . $bal . '</td>';
                                echo '</tr>';
                                $sum += $bal;
                                $i++;
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

            public function displayStudentBalanceSheet($studentLessonsResource, $balance) {
                if (isset($_POST['showStudentBalanceSheet'])) {
                    ?>
                    <div class="table-responsive-sm">
                        <table class="table table-borderless table-striped">
                            <thead class="table-success">
                                <tr>
                                    <!--<th></th>-->                                
                                    <th>Όνομα</th>
                                    <th>Ημερομηνία</th> 
                                    <th>Χρέω</th>
                                    <th>Πίστ</th>
                                    <th>Υπόλ</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <tr>
                                    <!--<td></td>-->
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Μετ</td>
                                    <td><?php echo $balance; ?></td>
                                </tr>
                                <?php
                                $i = 1;
                                while ($rowLessons = $studentLessonsResource->fetch_assoc()) {
                                    echo '<tr>';
//                                    echo'<td>' . $i . '</td>';
                                    echo '<td>' . $rowLessons['name'] . '</td>';
                                    $date = date_create($rowLessons['date']);
                                    echo '<td>' . date_format($date, "D d/m/Y") . '</td>';
                                    //echo '<td>' . $rowLessons['date'] . '</td>';
                                    $lessonCost = $rowLessons['duration'] * 10;
                                    if ($lessonCost != 0) {
                                        echo '<td>' . $lessonCost . '</td>';
                                    } else {
                                        echo '<td>' . '</td>';
                                    }
                                    $payment = $rowLessons['payment'];
                                    if ($payment != 0) {
                                        echo '<td>' . $payment . '</td>';
                                    } else {
                                        echo '<td>' . '</td>';
                                    }
                                    $balance = $balance + $lessonCost - $payment;
                                    echo '<td>' . $balance . '</td>';
                                    echo '</tr>';
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                }
            }

            public function displayStudent($studentResource) {
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
            }

            public function displayEndMatter() {
                ?>
            </body>
        </html>
        <?php
    }
    
    public function displayCalendar(){
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2">
                    <?php  ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function displayNewStudentAskiseisSearch() {
        echo '<a href="index.php?action=studentsAskiseis" class="btn btn-dark btn-block" type="button">Νέα αναζήτηση</a>';
    }

}
