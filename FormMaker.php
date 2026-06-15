<?php

class FormMaker
{

    public function addNewStudentForm()
    {
?>
        <div class="container">
            <h5>Εισαγωγή Μαθητή</h5>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <div class="form-group">
                    <label for="name">Όνομα:</label>
                    <input type="text" class="form-control" id="name" placeholder="Δώστε όνομα" name="name" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Επώνυμο:</label>
                    <input type="text" class="form-control" id="lastName" placeholder="Δώστε Επώνυμο" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="address">Διεύθυνση:</label>
                    <input type="text" class="form-control" id="address" placeholder="Δώστε Διεύθυνση" name="address" required>
                </div>
                <div class="form-group">
                    <label for="email">email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Δώστε email" name="email">
                </div>
                <div class="form-group">
                    <label for="tel">τηλέφωνο:</label>
                    <input type="tel" class="form-control" id="phone" placeholder="Δώστε τηλέφωνο" name="phone">
                </div>
                <div class="form-group">
                    <label for="birthday">Ημερομηνία γέννησης:</label>
                    <input type="date" class="form-control" id="birthday" placeholder="Ημερομηνία Γέννησης" name="birhtday">
                </div>
                <div class="form-group">
                    <label for=" school">Σχολείο Μαθητή:</label>
                    <select class="form-control" id=" school" name="school" required>
                        <option value=""></option>
                        <option value="ΓΕΛ Καρλοβάσου">ΓΕΛ Καρλοβάσου</option>
                        <option value="ΓΕΛ Μαραθοκάμπου">ΓΕΛ Μαραθοκάμπου</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="note">Σχολές</label>
                    <textarea class="form-control" rows="4" name="target" id="note"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function editStudentForm()
    {
        $db = new DbHandler();
        $studentResource = $db->getOneStudentsDetails();
        $row = $studentResource->fetch_assoc()
    ?>
        <div class="container">
            <h5> Διόρθωση Μαθητή</h5>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <input type="hidden" name="studentId" value="<?php echo $row['studentId']; ?>">
                <div class="form-group">
                    <label for="name">Όνομα:</label>
                    <input type="text" class="form-control" id="name" placeholder="Δώστε όνομα" name="name" value="<?php echo $row['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Επώνυμο:</label>
                    <input type="text" class="form-control" id="lastName" placeholder="Δώστε Επώνυμο" name="lastName" value="<?php echo $row['lastName']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Διεύθυνση:</label>
                    <input type="text" class="form-control" id="address" placeholder="Δώστε Διεύθυνση" name="address" value="<?php echo $row['address']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Δώστε email" name="email" value="<?php echo $row['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="tel">τηλέφωνο:</label>
                    <input type="tel" class="form-control" id="phone" placeholder="Δώστε τηλέφωνο" name="phone" value="<?php echo $row['phone']; ?>">
                </div>
                <div class="form-group">
                    <label for="birthday">Ημερομηνία γέννησης:</label>
                    <input type="date" class="form-control" id="birthday" placeholder="Ημερομηνία Γέννησης" name="birhtday" value="<?php echo $row['birthday']; ?>">
                </div>
                <div class="form-group">
                    <label for="note">Σχολές</label>
                    <textarea class="form-control" rows="4" name="target" id="note"><?php echo $row['target']; ?></textarea>
                </div>
                <!--                <div class="form-group">         
                                    <label for=" school">Σχολείο Μαθητή:</label>             
                                    <select class="form-control" id=" school" name="school" required> 
                                        <option value=""></option>
                                        <option value="ΓΕΛ Καρλοβάσου">ΓΕΛ Καρλοβάσου</option>                 
                                        <option value="ΓΕΛ Μαραθοκάμπου">ΓΕΛ Μαραθοκάμπου</option>                                     
                                    </select>             
                                </div>                         -->
                <button type="submit" class="btn btn-success" name="updateStudent">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function deleteStudentForm()
    {
    ?>
        <div class="container mt-4">
            <h5 class="bg-danger text-white p-3 rounded shadow-sm">Διαγραφή Μαθητή</h5>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="bg-light p-4 border shadow-sm rounded" onSubmit="return confirm('ΠΡΟΣΟΧΗ: Είστε σίγουρος ότι θέλετε να διαγράψετε οριστικά αυτόν τον μαθητή και όλα τα δεδομένα του;');">
                <?php $this->selectStudent(); ?>
                <button type="submit" class="btn btn-danger mt-3 shadow" name="deleteStudentBtn">Οριστική Διαγραφή</button>
            </form>
        </div>
    <?php
    }

    public function addTelephoneForm()
    {
    ?>
        <h5>Εισαγωγή τηλεφώνου</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <?php $this->selectStudent(); ?>
                <div class="form-group">
                    <label for="telephone">Τηλέφωνο:</label>
                    <input type="tel" class="form-control" id="phone" placeholder="Δώστε τηλέφωνο" name="phone">
                </div>
                <button type="submit" class="btn btn-success">Υποβολή</button>
            </form>
        </div>
    <?php
    }



    public function addLessonForm()
    {
    ?>
        <h5>Εισαγωγή μαθήματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDate(); ?>
                <div class="form-group">
                    <label for="duration">Διάρκεια:</label>
                    <select class="form-control" id="sel1" name="duration">
                        <option value="1.5">1.5</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="location">Τοποθεσία:</label>
                    <select class="form-control" id="location" name="location">
                        <option value="Live">Live</option>
                        <option value="Zoom">Zoom</option>
                    </select>
                </div>
                <button type="submit" name="addNewLesson" class="btn btn-success">Υποβολή</button>
            </form>
        </div>
    <?php
        //$this->addFormValidation();
    }

    public function addTimeTableForm()
    {
    ?>
        <h5>Προγραμματισμός μαθήματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <?php $this->selectStudent(); ?>
                <div class="form-group">
                    <label for="dateFrom">Από Ημερομηνία:</label>
                    <input type="date" class="form-control" id="dateFrom" name="dateFrom" required>
                </div>
                <?php $this->selectToDateNotRequired(); ?>
                <div class="form-group">
                    <label for="timeFrom">Από:</label>
                    <input type="time" class="form-control" id="timeFrom" name="timeFrom" required>
                </div>
                <div class="form-group">
                    <label for="timeΤο">Μέχρι:</label>
                    <input type="time" class="form-control" id="timeΤο" name="timeTo" required>
                </div>
                <button type="submit" class="btn btn-success" name="setTimeTable">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function getTimeTableForm()
    {
    ?>
        <h5>Αναζήτηση προγράμματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <?php $this->selectStudent(); ?>
                <button type="submit" class="btn btn-success" name="findTimeTable">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function selectTimeTableForm($timeTableResource)
    {
    ?>
        <h5>Αναζήτηση προγράμματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <div class="form-group">
                    <label for="student">Μάθημα:</label>
                    <select class="form-control" id="timeTableId" name="timeTableId" required>
                        <?php
                        while ($row = $timeTableResource->fetch_assoc()) {
                            $date = date_create($row['date']);
                            $time = $row['timeFrom'];
                            echo '<option value="' . $row['timeTableId'] . '">' . date_format($date, "l d/m/y") . '  ' . date('H:i', strtotime($time)) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" name="editOne">Διόρθωση</button>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger" name="deleteOneTimeTable" onclick="return confirm('Να διαγραφεί μόνο αυτό το μάθημα;');">Διαγραφή ενός</button>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger" name="deleteTimeTable" onclick="return confirm('Να διαγραφούν ΟΛΑ τα επαναλαμβανόμενα μαθήματα από εδώ και πέρα;');">Διαγραφή όλων</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php
    }

    public function editTimeTableForm($lessonTimeTableResource)
    {
        $row = $lessonTimeTableResource->fetch_assoc();
    ?>
        <h5>Προγραμματισμός μαθήματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Να γίνει η αλλαγή?');">
                <input type="hidden" name="timeTableId" value="<?php echo $row['timeTableId']; ?>">
                <input type="hidden" name="studentId" value="<?php echo $row['studentId']; ?>">
                <div class="form-group">
                    <label for="date">Ημερομηνία:</label>
                    <input type="date" class="form-control" id="dateFrom" name="date" value="<?php echo $row['date']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="timeFrom">Από:</label>
                    <input type="time" class="form-control" id="timeFrom" name="timeFrom" value="<?php echo $row['timeFrom']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="timeΤο">Μέχρι:</label>
                    <input type="time" class="form-control" id="timeΤο" name="timeTo" value="<?php echo $row['timeTo']; ?>" required>
                </div>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-success" name="updateOneTimeTable">Διόρθωση σε ένα</button>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-success" name="updateTimeTable">Διόρθωση σε όλα</button>
                    </div>
                </div>
            </form>
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

    public function addNoteForm()
    {
    ?>
        <h5>Νέα Σημείωση</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDate(); ?>
                <div class="form-group">
                    <label for="note">Σημείωση</label>
                    <textarea class="form-control" rows="4" name="note" id="note"></textarea>
                </div>
                <button type="submit" class="btn btn-success" name="submitNote">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function editNoteForm($noteResource)
    {
    ?>
        <h5>Διόρθωση Σημείωσης</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">

                <div class="form-group">
                    <label for="note">Σημείωση</label>
                    <textarea class="form-control" rows="4" name="note" id="note">
                        <?php
                        while ($row = $noteResource->fetch_assoc()) {
                            echo $row['note'];
                            $noteId = $row['noteId'];
                        }
                        ?>
                    </textarea>
                    <input type="hidden" id="noteId" name="noteId" value="<?php echo $noteId; ?>">
                </div>
                <button type="submit" class="btn btn-success" name="updateNote">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function addApousiaForm()
    {
    ?>
        <h5>Νέα Απουσία</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDate(); ?>
                <div class="form-group">
                    <label for="note">Αιτιολογία</label>
                    <textarea class="form-control" rows="4" name="reason" id="note"></textarea>
                </div>
                <button type="submit" class="btn btn-success" name="submitApousia">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function deleteLessonForm()
    {
    ?>
        <h5>Διαγραφή μαθήματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Να γίνει η διαγραφή?');">
                <?php $this->selectStudentOnChangeSubmit(); ?>
                <?php //  $this->selectDate();   
                ?>
                <?php $this->selectLesson(); ?>

                <button type="submit" class="btn btn-success">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function deletePaymentForm()
    {
    ?>
        <h5>Διαγραφή πληρωμής</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Να γίνει η διαγραφή?');">
                <?php $this->selectStudentOnChangeSubmit(); ?>
                <?php $this->selectPayment1() ?>
                <button type="submit" class="btn btn-success" name="deletePayment">Υποβολή</button>
            </form>

        </div>
    <?php
    }

    public function addPaymentForm()
    {
    ?>
        <h5>Εισαγωγή πληρωμής</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDate(); ?>
                <div class="form-group">
                    <label for="payment">Ποσό:</label>
                    <input type="number" class="form-control" id="payment" placeholder="Δώστε ποσό" name="payment" required>
                </div>
                <button type="submit" class="btn btn-success" name="submitPayment">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function getStudentLessonsForm()
    {
    ?>
        <h5>Αναζήτηση μαθημάτων</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDate(); ?>
                <?php $this->selectToDateNotRequired(); ?>
                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label" for="lastName   ">
                            <input type="checkbox" class="form-check-input" id="lastName" name="lastName" value="yes">Επώνυμο
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label" for="radio1">
                            <input type="checkbox" class="form-check-input" id="showLocation" name="location" value="yes">Τοποθεσία
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-success" name="getStudentLessons">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function getStudentNotesForm()
    {
    ?>
        <h5>Αναζήτηση Σημειώσεων</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDateNotRequired(); ?>
                <?php // $this->selectToDateNotRequired();  
                ?>
                <button type="submit" class="btn btn-success" name="getStudentNotes">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function displayEditDeleteStudentNotes($studentNotesResource)
    {
    ?>
        <h5>Αναζήτηση Σημειώσεων</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος?');">
                <?php
                $i = 1;
                while ($row = $studentNotesResource->fetch_assoc()) {

                    echo '<div class="form-check">';
                    echo '<label class="form-check-label" for="check1">';
                    $date = date_create($row['date']);
                    echo ' <input type="radio" class="form-check-input" name="noteId" value="' . $row['noteId'] . '">' . date_format($date, "D d/m/y") . ' ' . $row['note'];
                    echo ' </div>';
                    echo '<hr>';
                    $i++;
                }
                ?>
                <button type="submit" class="btn btn-success" name="editStudentNote">Διόρθωση</button>
                <button type="submit" class="btn btn-success" name="deleteNote">Διαγραφή</button>
            </form>
        </div>
    <?php
    }


    public function frontPageForm()
    {
        $db = new DbHandler();
    ?>
        <!--<div class="container">-->
        <h5>Ημερήσιο Πρόγραμμα</h5>
        <!--<div class="table-responsive-sm">-->
        <table class="table table-borderless table-striped">
            <thead class="table-success">
                <tr>
                    <th>Όνομα</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $studentsResource = $db->getStudentsWithLesson();
                while ($row = $studentsResource->fetch_assoc()) {
                ?>
                    <!--<input type="hidden" name="studentId" value="<?php // $row['studentId']  
                                                                        ?>">-->
                    <tr>
                        <td> <?php echo $row['name'] ?> </td>
                        <td><button class="btn btn-success" onclick="document.location = 'index.php?action=lesson&studentId=<?php echo $row['studentId']; ?>'">Μάθημα</button></td>
                        <td><button class="btn btn-danger" onclick="document.location = 'index.php?action=apousia&studentId=<?php echo $row['studentId']; ?>'">Απουσία</button></td>
                    </tr>
                <?php
                }
                ?>
                <!--<button type="submit" class="btn btn-success" name="updateStudent">Υποβολή</button>-->
            </tbody>
        </table>

        <!--</div>-->

        <!--</div>-->
    <?php
    }

    public function getStudentMathimataApousiesForm()
    {
    ?>
        <h5>Μαθήματα - Απουσίες</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDateNotRequired(); ?>
                <?php // $this->selectToDateNotRequired(); 
                ?>
                <button type="submit" class="btn btn-success" name="getStudentMathimataApousies">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function getStudentApousiesForm()
    {
    ?>
        <h5>Αναζήτηση Απουσιών</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDateNotRequired(); ?>
                <?php // $this->selectToDateNotRequired(); 
                ?>
                <button type="submit" class="btn btn-success" name="getStudentApousies">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function getStudentPaymentsForm()
    {
    ?>
        <h5>Αναζήτηση πληρωμών</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDateNotRequired(); ?>
                <button type="submit" class="btn btn-success" name="getStudentPayments">Υποβολή</button>
            </form>
        </div>
    <?php
    }


    public function selectDate()
    {
    ?>
        <div class="form-group">
            <label for="date">Ημερομηνία:</label>
            <input type="date" class="form-control" id="date" value="<?php echo date("Y-m-d"); ?>" name="date" required>
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

    public function getStudentBalanceForm()
    {
    ?>
        <h5>Υπόλοιπο μαθητή</h5>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
            <?php $this->selectStudent(); ?>

            <button type="submit" class="btn btn-success" name="studentBalance">Υποβολή</button>
        </form>
    <?php
    }

    public function getStudentBalanceSheetForm()
    {
    ?>
        <h5>Καρτέλα μαθητή</h5>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark">
            <?php $this->selectStudent(); ?>
            <?php $this->selectDateNotRequired(); ?>
            <button type="submit" class="btn btn-success" name="showStudentBalanceSheet">Υποβολή</button>
        </form>
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
}
