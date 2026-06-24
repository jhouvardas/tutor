<?php

trait LessonsFormTrait
{
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
                <?php $this->selectLesson(); ?>
                <button type="submit" class="btn btn-success">Υποβολή</button>
            </form>
        </div>
    <?php
    }

    public function cancelLessonForm()
    {
    ?>
        <h5>Ακύρωση Μαθήματος</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post" class="bg-light text-dark" onSubmit="return confirm('Είσαι σίγουρος ότι θέλεις να ακυρώσεις το μάθημα;');">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDate(); ?>
                <div class="form-group">
                    <label for="reason">Αιτιολογία Ακύρωσης (π.χ. Γιορτή, Προσωπικοί λόγοι)</label>
                    <textarea class="form-control" rows="4" name="reason" id="reason" required></textarea>
                </div>
                <button type="submit" class="btn btn-warning" name="submitCancelLesson">Υποβολή</button>
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

    public function getStudentMathimataApousiesForm()
    {
    ?>
        <h5>Μαθήματα - Απουσίες</h5>
        <div class="container">
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?> " method="post">
                <?php $this->selectStudent(); ?>
                <?php $this->selectDateNotRequired(); ?>
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
                <button type="submit" class="btn btn-success" name="getStudentApousies">Υποβολή</button>
            </form>
        </div>
<?php
    }
}
