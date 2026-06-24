<?php

require_once 'StudentDbTrait.php';
require_once 'TimeTableDbTrait.php';
require_once 'PaymentDbTrait.php';
require_once 'LessonsDbTrait.php';
require_once 'GroupsDbTrait.php';

class DbHandler
{
    use StudentDbTrait;
    use TimeTableDbTrait;
    use PaymentDbTrait;
    use LessonsDbTrait;
    use GroupsDbTrait;

    public function connect()
    {
        $servername = "jhouv.eu";
        $username = "jhouvardas";
        $password = "Jhouv@1957";
        $dbname = "tutor";

        $conn = new mysqli($servername, $username, $password, $dbname);
        mysqli_set_charset($conn, "utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    public function connectToFamilyDB()
    {
        $servername = "jhouv.eu";
        $username = "familyUser";
        $password = "Geo@1994!";
        $dbname = "familyDB";
        $conn = new mysqli($servername, $username, $password, $dbname);
        mysqli_set_charset($conn, "utf8");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
