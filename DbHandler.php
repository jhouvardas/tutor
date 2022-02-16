<?php

class DbHandler {

    public function connect() {
        $servername = "jhouv.eu";
        $username = "jhouvardas";
        $password = "Jhouv@1957";
        $dbname = "tutor";

// Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        mysqli_set_charset($conn, "utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            //echo 'welcome ha';
        }
        return $conn;
    }

    public function connectToFamilyDB() {
        $servername = "jhouv.eu";
        $username = "familyUser";
        $password = "Geo@1994!";
        $dbname = "familyDB";
        $conn = new mysqli($servername, $username, $password, $dbname);
        mysqli_set_charset($conn, "utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            //echo 'welcome ha';
        }
        return $conn;
    }

    public function login($username, $password) {
        $conn = $this->connect();
        $sql = "SELECT * FROM user WHERE username = '" . $username . "' AND password = '" . $password . "'";
        $result = $conn->query($sql);
        //echo 'from function login'.$sql;        
        if ($result->num_rows > 0) {
            // echo 'welcome';
            return true;
        } else {
            throw new Exception('Could not log you in');
        }
    }

    public function getStudentsDetails() {
        $conn = $this->connect();
        $sql = "SELECT * FROM student WHERE status = 1 ORDER BY name ASC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
//            echo '0 results';
        }
    }
    
    public function getGroups($groupType) {
        $conn = $this->connect();
        $panellinies=$groupType;
        $sql = "SELECT * FROM tutor_askiseisGroup WHERE panellinies = $panellinies ORDER BY askiseisGroupName ASC";
        echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
//            echo '0 results';
        }
    }

    public function getOneStudentsDetails() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $sql = "SELECT * FROM student WHERE studentId = $studentId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
//            echo '0 results';
        }
    }

    public function getStudents() {
        $conn = $this->connect();
        $sql = "SELECT studentId,name,lastName,(SELECT SUM(duration) FROM lesson WHERE lesson.studentId = student.studentId)AS dur,(SELECT SUM(payment) FROM lesson WHERE lesson.studentId = student.studentId)AS pay FROM student WHERE status = 1 ORDER BY name";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
//            echo '0 results';
        }
    }

    public function getAllStudents() {
        $conn = $this->connect();
        $sql = "SELECT * FROM student ORDER BY name";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
//            echo '0 results';
        }
    }

    public function getStudentAllLessonsCost($studentId) {
        $conn = $this->connect();
        $sql = "SELECT sum(duration) as total FROM lesson WHERE studentId = $studentId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $totalLessonsDurationToDate = $row['total'];
            //echo $totalDebitToDate . 'debit';
            return $totalLessonsDurationToDate * 10;
        } else {
//            echo '0 results';
        }
    }

    public function getStudentAllPaymentsTotal($studentId) {
        $conn = $this->connect();
        $sql = "SELECT sum(payment) as total FROM lesson WHERE studentId = $studentId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $totalPaymentsToDate = $row['total'];
            //echo $totalDebitToDate . 'debit';
            return $totalPaymentsToDate;
        } else {
//            echo '0 results';
        }
    }

    public function getStudentsWithLesson() {
        $conn = $this->connect();
        $sql = "SELECT * FROM student INNER JOIN tutor_timeTable ON student.studentId=tutor_timeTable.studentId WHERE date = CURDATE() AND student.studentId NOT IN(SELECT studentId FROM lesson WHERE date = CURDATE()) AND student.studentId NOT IN (SELECT studentId from apousia WHERE date = CURDATE()) ORDER BY tutor_timeTable.timeTo;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            echo 'Δεν υπάρχουν μαθήματα σήμερα';
        }
    }

    public function getOneStudentTimeTable() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if (isset($_POST['findTimeTable'])) {
            $sql = "SELECT * FROM student INNER JOIN tutor_timeTable ON student.studentId = tutor_timeTable.studentId WHERE student.studentId = $studentId AND tutor_timeTable.studentId=$studentId AND tutor_timeTable.date >= CURDATE() ORDER BY tutor_timeTable.date;"; //DAYOFWEEK(tutor_timeTable.date) = DAYOFWEEK('$date')
//            echo $sql;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                return $result;
            } else {
                echo 'Δεν υπάρχουν προγραμματισμένα μαθήματα';
            }
        }
    }

    public function getOneDayTimeTable() {
        $conn = $this->connect();
        $date = $_POST['date'];
        $sql = "SELECT name,lastName,tutor_timeTable.timeFrom FROM student INNER JOIN tutor_timeTable ON student.studentId = tutor_timeTable.studentId  WHERE tutor_timeTable.date ='$date' ORDER BY tutor_timeTable.timeFrom";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            echo 'Δεν υπάρχουν προγραμματισμένα μαθήματα στις ' . $date;
        }
    }

    public function getOneLessonTimeTable() {
        $conn = $this->connect();
        $timeTableId = $_POST['timeTableId'];
        $sql = "SELECT * FROM tutor_timeTable WHERE timeTableId = '$timeTableId' ";
//        echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
//            echo $sql;
            return $result;
        } else {
            echo 'Δεν υπάρχουν προγραμματισμένα μαθήματα ';
        }
    }
    
    public function updateOneDayTimeTable(){
        $conn= $this->connect();
        $timeTableId = $_POST['timeTableId'];
        $date = $_POST['date'];
        $timeFrom = $_POST['timeFrom'];
        $timeTo = $_POST['timeTo'];
        $sql = "UPDATE tutor_timeTable SET date = '$date', timeFrom = '$timeFrom', timeTo ='$timeTo' WHERE timeTableId = $timeTableId ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo 'Η αλλαγή έγινε';
        } else {
//            echo 'Δεν έγινε αλλαγή ';
        }
    }
    
    public function deleteOneDayTimeTable(){
        $conn= $this->connect();
        $timeTableId = $_POST['timeTableId'];
        $date = $_POST['date'];
        $timeFrom = $_POST['timeFrom'];
        $timeTo = $_POST['timeTo'];
        $sql = "DELETE FROM tutor_timeTable WHERE date = '$date' AND timeFrom = '$timeFrom' AND timeTo ='$timeTo' AND timeTableId = $timeTableId ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo 'Η αλλαγή έγινε';
        } else {
//            echo 'Δεν έγινε αλλαγή ';
        }
    }
    
    public function updateTimeTable(){
        $conn= $this->connect();
        session_start();        
        $studentId = $_SESSION['studentId'];
        $date = $_POST['date'];
        $timeFrom = $_POST['timeFrom'];
        $timeTo = $_POST['timeTo'];
        $sql = "UPDATE tutor_timeTable SET timeFrom='$timeFrom',timeTo='$timeTo' WHERE studentId = $studentId AND DAYOFWEEK(date) = DAYOFWEEK('$date') AND date >= '$date';";
        $result = $conn->query($sql);
        echo $sql;
        if ($result->num_rows > 0) {
            echo 'Η αλλαγή έγινε';
        } else {
//            echo 'Δεν έγινε αλλαγή ';
        }
    }
    
    public function deleteTimeTable($timeTableResource){
        $conn= $this->connect();
        $row=$timeTableResource->fetch_assoc();
        session_start();        
        $studentId = $row['studentId'];
        $date = $row['date'];
        $timeFrom = $row['timeFrom'];
        $timeTo = $row['timeTo'];
        $sql = "DELETE FROM tutor_timeTable WHERE timeFrom='$timeFrom' AND timeTo='$timeTo' AND studentId = $studentId AND DAYOFWEEK(date) = DAYOFWEEK('$date') AND date >= '$date';";
        echo $sql;
        $result = $conn->query($sql);
        echo $sql;
        if ($result->num_rows > 0) {
            echo 'Η διαγραφή έγινε';
        } else {
//            echo 'Δεν έγινε αλλαγή ';
        }
    }


    public function addPhone() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $telephone = htmlspecialchars($_POST['telephone']);
        if (isset($telephone) && $telephone != '') {
            $sql = "UPDATE student SET telephone = '$telephone' WHERE studentId = $studentId";
            
            if ($conn->query($sql) === TRUE) {
                echo "Προστέθηκε το τηλέφωνο ";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addNewStudent() {
        $conn = $this->connect();
        $name = htmlspecialchars($_POST['name']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $address = htmlspecialchars($_POST['address']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $email = htmlspecialchars($_POST['email']);
        $school = $_POST['school'];
        $birthday = $_POST['birhtday'];
        if (isset($name) && $name != '') {
            $sql = "INSERT INTO student (name,lastName,address,birthday,school,telephone,email) VALUES ('$name','$lastName ',' $address ',' $birthday ','$school','$telephone','$email')";
            if ($conn->query($sql) === TRUE) {
                echo "Προστέθηκε ο Μαθητής";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function updateStudent() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $name = htmlspecialchars($_POST['name']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $address = htmlspecialchars($_POST['address']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $email = htmlspecialchars($_POST['email']);
//        $school = $_POST['school'];
        $birthday = $_POST['birhtday'];
        if (isset($name) && $name != '') {
            $sql = "UPDATE student SET name ='$name',lastName='$lastName ',address=' $address ',birthday=' $birthday ',telephone='$telephone',email='$email'  WHERE studentId = $studentId";
            if ($conn->query($sql) === TRUE) {
                echo "Διορθώθηκε ο Μαθητής";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addLesson() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $duration = $_POST['duration'];
        $location = $_POST['location'];
        $date = $_POST['date'];
        if (isset($duration) && $duration != '' && isset($studentId) && $studentId != '' && isset($date) && $date != '') {
            $sql = "INSERT INTO lesson (studentId,duration,date,type,location) VALUES ('" . $studentId . "','" . $duration . "','" . $date . "','lesson','$location')";
            if ($conn->query($sql) === TRUE) {
                $sql = "SELECT lastName FROM student WHERE studentId = $studentId";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "Προστέθηκε μάθημα στον/ην μαθητή/τρια " . $row['lastName'] . ' στις ' . $date;
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addNote() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $note = $_POST['note'];
        $date = $_POST['date'];

        if (isset($_POST['submitNote'])) {
            //echo 'eeeeeeeeeeeeeeeeeeee';
            $sql = "INSERT INTO note (studentId,note,date) VALUES ('" . $studentId . "','" . $note . "','" . $date . "')";
            if ($conn->query($sql) === TRUE) {
                echo "Η σημείωση αποθηκεύτηκε";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addTheoria() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $book = $_POST['book'];
        $chapter = $_POST['chapter'];
        $comment = $_POST['comment'];
        $date = $_POST['date'];
        if (isset($_POST['theoria'])) {
            //echo 'eeeeeeeeeeeeeeeeeeee';
            $sql = "INSERT INTO theoria (studentId,book,chapter,comment,date) VALUES ($studentId,'$book','$chapter','$comment','$date')";
            if ($conn->query($sql) === TRUE) {
                echo "Η Θεωρία αποθηκεύτηκε";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addTimeTable() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $date = $_POST['dateFrom'];
        $toDate = $_POST['toDate'];
        $timeFrom = $_POST['timeFrom'];
        $timeTo = $_POST['timeTo'];
        $i = 1;
        while ($date <= $toDate) {
            $sql = "INSERT INTO tutor_timeTable (studentId,date,timeFrom,timeTo) VALUES ($studentId,'$date','$timeFrom','$timeTo')";
            $conn->query($sql);
            $date = date('Y-m-d', strtotime($date . ' +7 day'));
        }
    }
    
    public function addAskiseisGroup() {
        $conn = $this->connect();
        $askiseisGroupName = $_POST['askiseisGroupName'];             
            $sql = "INSERT INTO tutor_askiseisGroup (askiseisGroupName) VALUES ('$askiseisGroupName')";
            if ($conn->query($sql) === TRUE) {
                echo "Δημιουργήθηκε η ".$askiseisGroupName;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }        
    }


    public function updateNote() {
        $conn = $this->connect();
        $noteId = $_POST['noteId'];
        $note = $_POST['note'];
        $date = $_POST['date'];
        if (isset($_POST['updateNote'])) {
            $sql = "UPDATE note SET note= '$note'  WHERE noteId = $noteId";
            if ($conn->query($sql) === TRUE) {
                echo "Η σημείωση διορθώθηκε";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function deleteNote() {
        $conn = $this->connect();
        $noteId = $_POST['noteId'];
        if (isset($_POST['deleteNote'])) {
            $sql = "DELETE FROM note  WHERE noteId = $noteId";
            if ($conn->query($sql) === TRUE) {
                echo "Η σημείωση διαγράφηκε";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addApousia() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $reason = $_POST['reason'];
        $date = $_POST['date'];

        if (isset($_POST['submitApousia'])) {
            //echo 'eeeeeeeeeeeeeeeeeeee';
            $sql = "INSERT INTO apousia (studentId,reason,date) VALUES ('" . $studentId . "','" . $reason . "','" . $date . "')";
            if ($conn->query($sql) === TRUE) {
                echo "Η απουσία καταχωρήθηκε";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addPayment() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $payment = $_POST['payment'];
        $date = $_POST['date'];

        if (isset($_POST['submitPayment'])) {
            //echo 'eeeeeeeeeeeeeeeeeeee';
            $sql = "INSERT INTO lesson (studentId,payment,date,type) VALUES ('" . $studentId . "','" . $payment . "','" . $date . "','payment')";
            if ($conn->query($sql) === TRUE) {
                echo "Η πληρωμή καταχωρήθηκε <br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addPaymentToTransactions() {
        $conn = $this->connectToFamilyDB();
        $credit = $_POST['payment'];
        $date = $_POST['date'];
        $studentId = $_POST['studentId'];

        if (isset($_POST['submitPayment'])) {
            $sql = "INSERT INTO trans_transact (credit,date,transactionDescriptionId,methodId) VALUES ( $credit ,' $date ','14','5')";
            if ($conn->query($sql) === TRUE) {
                echo "Ενημερώθηκαν τα έσοδα";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function addAskiseisFromErgasia() {
        $conn = $this->connect();
        $studentId = $_SESSION['studentId'];
        $location = $_SESSION['location'];
        $date = $_SESSION['date'];
        $askisisSource = $_SESSION['askiseisSource'];
        $arrlength = count($_SESSION['askiseis']);
        for ($i = 0; $i < $arrlength; $i++) {
            $askisi = $_SESSION['askiseis'][$i];
            $sql = "INSERT INTO askiseis (studentId,location,date,askiseisSource,askisi) VALUES ($studentId,'$location','$date','$askisisSource',$askisi)";
//            echo $sql;
            if ($conn->query($sql) === TRUE) {
                $added = 1;
            } else {
                echo "Error " . $sql;
            }
        }
        if ($added == 1) {
//            echo 'Προστέθηκαν οι ασκήσεις';
        }
        $conn->close();
    }
    
    public function addAskiseisFromGroup() {
        $conn = $this->connect();
        $askiseisGroupId = $_SESSION['askiseisGroupId'];   
        $askisisSource = $_SESSION['askiseisSource'];
        $arrlength = count($_SESSION['askiseis']);
        for ($i = 0; $i < $arrlength; $i++) {
            $askisi = $_SESSION['askiseis'][$i];
            $sql = "INSERT INTO tutor_askiseisInGroup (askiseisGroupId,askiseisSource,askisi) VALUES ($askiseisGroupId,'$askisisSource',$askisi)";
//            echo $sql;
            if ($conn->query($sql) === TRUE) {
                $added = 1;
            } else {
                echo "Error " . $sql;
            }
        }
        if ($added == 1) {
//            echo 'Προστέθηκαν οι ασκήσεις';
        }
        $conn->close();
    }

    public function addPanellinies() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $location = $_POST['location'];
        $date = $_POST['date'];
        $panelliniesYear = $_POST['panelliniesYear'];
        $thema = $_POST['thema'];
        $erotima = $_POST['erotima'];
        $period = $_POST['period'];
        $lykeio = $_POST['lykeio'];
        if (isset($studentId) && isset($_POST['panellinies'])) {
            $sql = "INSERT INTO askiseis(studentId,location,date,panelliniesYear,thema,erotima,period,lykeio,askiseisSource) VALUES ($studentId,'$location','$date',$panelliniesYear,'$thema','$erotima','$period','$lykeio','Πανελλήνιες')";
            if ($conn->query($sql) === TRUE) {
                echo "Προστέθηκαν οι Πανελλήνιες";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    
    public function addPanelliniesToGroup() {
        $conn = $this->connect();        
        $askiseisGroupId = $_POST['askiseisGroupId'];       
        $panelliniesYear = $_POST['panelliniesYear'];
        $thema = $_POST['thema'];
        $erotima = $_POST['erotima'];
        $period = $_POST['period'];
        $lykeio = $_POST['lykeio'];
        if (isset($askiseisGroupId) && isset($_POST['panelliniesToGroup'])) {
            $sql = "INSERT INTO askiseis(studentId,location,date,panelliniesYear,thema,erotima,period,lykeio,askiseisSource) VALUES (50,'',CURDATE(),$panelliniesYear,'$thema','$erotima','$period','$lykeio','Πανελλήνιες')";
            echo $sql;
            if ($conn->query($sql) === TRUE) {
                echo "Προστέθηκαν οι Πανελλήνιες";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function deletePaymentAtTransactions($date, $lastName) {
        $conn = $this->connectToFamilyDB();
        if (isset($_POST['deletePayment'])) {
            $sql = "DELETE FROM trans_transact  WHERE trans_transact.date = '$date'  AND transactionDescriptionId = 14 and methodId = 5";
            if ($conn->query($sql) === TRUE) {
                echo "Ενημερώθηκαν τα έσοδα";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function deletePayment() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $lessonId = $_POST['lessonId'];
        $date = $_POST['date'];
        $sql = "DELETE FROM lesson WHERE studentId = " . $studentId . " AND lessonId = " . $lessonId . " AND type = 'payment'";
        if (isset($studentId) && $studentId != '' && isset($_POST['deletePayment'])) {
            $result = $conn->query($sql);
            if ($conn->query($sql) === TRUE) {
                echo "Record " . $lessonId . " deleted successfully" . $sql;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    public function deleteLesson() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $lessonId = $_POST['lessonId'];
        $sql = "DELETE FROM lesson WHERE studentId = '" . $studentId . "' AND lessonId = $lessonId AND type = 'lesson'";
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            echo "<meta http-equiv='refresh' content='0'>"; //ξαναφορτώνει την σελίδα για να καθαριστεί το select
            if ($conn->query($sql) === TRUE) {
                echo "Έγινε διαγραφή του μαθήματος";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            return $result;
        }
    }

    public function deleteAllStudentLessonsAndPayments() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $sql = "DELETE FROM lesson WHERE studentId = $studentId";
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            if ($conn->query($sql) === TRUE) {
                echo "Records deleted successfully" . $sql;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            return $result;
        }
    }

    public function deleteAllStudentTelephones() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $sql = "DELETE FROM telephone WHERE studentId = $studentId";
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            if ($conn->query($sql) === TRUE) {
                echo "Records deleted successfully" . $sql;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            return $result;
        }
    }

    public function deleteStudent() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $sql = "DELETE FROM student WHERE studentId = $studentId";
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            if ($conn->query($sql) === TRUE) {
                echo "Records deleted successfully" . $sql;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            return $result;
        }
    }

    public function getStudentLessons() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
//        echo 'heeereeeeeeee'.$studentId;
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        } else {
            $date = '2020-01-01';
        }
        if (isset($studentId) && $studentId != 0) {
            if ($studentId == 6974004099) {
                $sql = "SELECT * FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE lesson.date >= '" . $date . "' ORDER BY lesson.date,lesson.type";
            } else {
                $sql = "SELECT * FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE student.studentId = $studentId AND lesson.date >= '$date' ORDER BY lesson.date,lesson.type";
//                echo 'αααααααααααααααααααααααααααααααααααααααα'.$sql;
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
//                 echo 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee' . $sql;
                return $result;
            } else {
//                echo '0 results';
            }
        }
    }

    public function getStudentNotes() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        } else {
            $date = '2020-01-01';
        }
        if (isset($studentId) && $studentId != 0) {
            if ($studentId == 6974004099) {
                $sql = "SELECT * FROM student INNER JOIN note ON student.studentId = note.studentId WHERE note.date >= '" . $date . "' ORDER BY note.date";
            } else {
                $sql = "SELECT * FROM student INNER JOIN note ON student.studentId = note.studentId WHERE student.studentId = $studentId AND note.date >= '$date' ORDER BY note.date";
//                echo 'αααααααααααααααααααααααααααααααααααααααα'.$sql;
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
//                 echo 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee' . $sql;
                return $result;
            } else {
//                echo '0 results';
            }
        }
    }

    public function getNote($noteId) {
        $conn = $this->connect();
        $sql = "SELECT * FROM note WHERE noteId = $noteId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo 'μπράβο';
            return $result;
        } else {
//            echo '0 results '.$sql;
        }
    }

    public function getStudentName($studentId) {
        $conn = $this->connect();
        $sql = "SELECT * FROM student WHERE studentId = $studentId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            return $name;
        } else {
//            echo '0 results '.$sql;
        }
    }
    
     public function getAskiseisGroupName($askiseisGroupId) {
        $conn = $this->connect();
        $sql = "SELECT * FROM tutor_askiseisGroup WHERE askiseisGroupId = $askiseisGroupId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['askiseisGroupName'];
            return $name;
        } else {
//            echo '0 results '.$sql;
        }
    }
    
    public function getAskisisGroupName($askiseisGroupId) {
        $conn = $this->connect();
        $sql = "SELECT * FROM tutor_askiseisGroup WHERE askiseisGroupId = $askiseisGroupId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            return $name;
        } else {
//            echo '0 results '.$sql;
        }
    }
    
    public function getGroupAskiseis(){
        $conn = $this->connect();
        $groupId = $_POST['askiseisGroupId'];
        $sql = "SELECT * FROM tutor_askiseisInGroup WHERE askiseisGroupId = $groupId";
        echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {            
            return $result;
        } else {
//            echo '0 results '.$sql;
        }
    }

    public function getStudentEmail($studentId) {
        $conn = $this->connect();
        $sql = "SELECT * FROM student WHERE studentId = $studentId";
//        echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $email = $row['email'];
            return $email;
        } else {
//            echo '0 results '.$sql;
        }
    }

    public function getStudentApousies() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        } else {
            $date = '2020-01-01';
        }
        if (isset($studentId) && $studentId != 0) {
            if ($studentId == 6974004099) {
                $sql = "SELECT * FROM student INNER JOIN apousia ON student.studentId = apousia.studentId WHERE apousia.date >= '" . $date . "' ORDER BY lastName,date";
            } else {
                $sql = "SELECT * FROM student INNER JOIN apousia ON student.studentId = apousia.studentId WHERE student.studentId = $studentId AND apousia.date >= '$date' ORDER BY lastName,date";
//                echo 'αααααααααααααααααααααααααααααααααααααααα'.$sql;
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
//                 echo 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee' . $sql;
                return $result;
            } else {
//                echo '0 results';
            }
        }
    }

    public function getStudentMathimataApousies() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        } else {
            $date = '2020-01-01';
        }
        if (isset($studentId) && $studentId != 0) {
            $sql = "SELECT date,type FROM lesson WHERE lesson.type='lesson' and studentId = $studentId AND date >= '$date' 
                      UNION
                    SELECT date,reason FROM apousia WHERE studentId = $studentId AND date >= '$date'
                    ORDER BY date";
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
//            echo '0 results';
        }
    }

    public function getStudentMathimataApousiesTest() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        } else {
            $date = '2020-01-01';
        }
        if (isset($studentId) && $studentId != 0) {
            $sql = "SELECT date,type FROM lesson WHERE lesson.type='lesson' and studentId = $studentId AND date >= '$date' 
                      UNION
                    SELECT date,reason FROM apousia WHERE studentId = $studentId AND date >= '$date'
                    ORDER BY date";
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
//            echo '0 results';
        }
    }

    public function getStudentsDayLessons() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if (isset($studentId) && $studentId != 0 && $studentId != '6974004099') {
            $student = "student.studentId = $studentId AND";
        }
        $date = $_POST['date'];
        $toDate = $_POST['toDate'];
        if (isset($toDate) && $toDate != 0) {
            $sql = "SELECT * FROM lesson INNER JOIN student ON lesson.studentId=student.studentId WHERE $student lesson.duration > 0 AND lesson.date >= '$date' AND lesson.date <= '$toDate'  ORDER BY lesson.date";
//            echo $sql;
        } else {
            $sql = "SELECT * FROM lesson INNER JOIN student ON lesson.studentId=student.studentId WHERE $student lesson.duration > 0 AND lesson.date = '$date'";
//             echo $sql;
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
//            echo 'success';
            return $result;
        } else {
            echo 'fail';
        }
    }

    public function getStudentPayments1() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        } else {
            $date = '2020-01-01';
        }
        if (isset($studentId) && $studentId > 0) {
            if ($studentId == 6974004099) {
                $sql = "SELECT student.name, student.lastName, lesson.date,lesson.lessonId ,lesson.payment, lesson.date"
                        . " FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE lesson.date >= '" . $date . "' AND lesson.payment > 0 ORDER BY lesson.date";
            } else {
                $sql = "SELECT student.name, student.lastName, lesson.date,lesson.lessonId, lesson.payment, lesson.date"
                        . " FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE student.studentId = '" . $studentId . "'"
                        . "AND lesson.date >= '" . $date . "' AND lesson.payment > 0 ORDER BY lesson.date";
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                //echo 'great';
            } else {
                echo "0 results";
            }
            return $result;
        }
    }

    public function getLessons() {

        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $sql = "SELECT date,lessonId FROM lesson INNER JOIN student ON student.studentId = lesson.studentId WHERE student.studentId =  $studentId AND lesson.type = 'lesson' ORDER BY date DESC";

//        if (isset($studentId) && $studentId != '0') {
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo 'great' . $sql;
            return $result;
        } else {
            echo "0 results" . $sql;
        }

//        }
    }

    public function getLessonDate($lessonId) {
        if (isset($_POST['deletePayment'])) {
            $conn = $this->connect();
            $sql = "SELECT date FROM lesson WHERE lessonid = $lessonId";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $date = $row['date'];
                }
                return $date;
            } else {
                echo "0 results";
            }
            $conn->close();
        }
    }

    public function getLessonLastName($lessonId) {
        if (isset($_POST['deletePayment'])) {
            $conn = $this->connect();
            $sql = "SELECT lastName FROM student INNER JOIN lesson ON student.studentId = lesson.studentId WHERE lessonId = $lessonId";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $lastName = $row['lastName'];
                }
                return $lastName;
            } else {
                echo "0 results";
            }
            $conn->close();
        }
    }

    public function getDurationOfLessons() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $date = $_POST['date'];
        if ($studentId == 6974004099) {
            $sql = "SELECT SUM(duration) FROM lesson WHERE date < '" . $date . "'";
        } else {
            $sql = "SELECT SUM(duration) FROM lesson  WHERE studentId = '" . $studentId . "' AND date < '" . $date . "'";
        }
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $studentDurationOfLessons = $row['SUM(duration)'];
                }
            } else {
                echo "0 results";
            }
        }
        return $studentDurationOfLessons;
    }

    public function getStudentPaymentsTotal() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        $date = $_POST['date'];
        if ($studentId == 6974004099) {
            $sql = "SELECT SUM(payment) FROM lesson WHERE date < '" . $date . "'";
        } else {
            $sql = "SELECT SUM(payment) FROM lesson WHERE studentId = '" . $studentId . "' AND date < '" . $date . "'";
        }
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $studentPaymentsTotal = $row['SUM(payment)'];
                }
//                echo "0 results";
            }
        }
        return $studentPaymentsTotal;
    }

    public function getStudentAskiseis() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if ($studentId != '6974004099') {
            $student = " WHERE askiseis.studentId = $studentId ";
        } else {
            $student = "";
        }
        $date = $_POST['date'];
        if ($date != '') {
            $date2 = " AND date >= '$date' ";
        } else {
            $date2 = "";
        }
        $toDate = $_POST['toDate'];
        if ($toDate != '') {
            $date3 = " AND date <= '$toDate' ";
        } else {
            $date3 = "";
        }
        $location = $_POST['location'];
        if ($location != '') {
            $location2 = " AND location = '$location'";
        } else {
            $location2 = "";
        }
        $sql = "SELECT * FROM askiseis INNER JOIN student ON askiseis.studentId = student.studentId" . $student . $location2 . $date2 . $date3 . " AND askiseis.askiseisSource != 'Πανελλήνιες' ORDER BY name,date,location,askisi ASC";
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                return $result;
            } else {
                echo "Δεν έχουν ανατεθεί ασκήσεις ";
            }
        }
    }

    public function getStudentPanellinies() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if ($studentId != '6974004099') {
            $student = " WHERE askiseis.studentId = $studentId ";
        } else {
            $student = "";
        }
        $date = $_POST['date'];
        if ($date != '') {
            $date2 = " AND date >= '$date' ";
        } else {
            $date2 = "";
        }
        $location = $_POST['location'];
        if ($location != '') {
            $location2 = " AND location = '$location'";
        } else {
            $location2 = "";
        }
        $sql = "SELECT * FROM askiseis INNER JOIN student ON askiseis.studentId = student.studentId" . $student . $location2 . $date2 . " AND askiseisSource = 'Πανελλήνιες' ORDER BY name,date,location ASC";
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                return $result;
//                echo $sql;
            } else {
                echo "Δεν έχουν ανατεθεί πανελλήνιες";
            }
        }
    }

    public function getStudentTheoria() {
        $conn = $this->connect();
        $studentId = $_POST['studentId'];
        if ($studentId != '6974004099') {
            $student = " WHERE theoria.studentId = $studentId ";
        } else {
            $student = "";
        }
        $date = $_POST['date'];
        if ($date != '') {
            $date2 = " AND date >= '$date' ";
        } else {
            $date2 = "";
        }

        $sql = "SELECT * FROM theoria INNER JOIN student ON theoria.studentId = student.studentId" . $student . $date2 . " ORDER BY name,date ASC";
        if (isset($studentId) && $studentId != '') {
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
//                echo $sql;
                return $result;
            } else {
                echo "Δεν έχει ανατεθεί θεωρία ";
            }
        }
    }

}
