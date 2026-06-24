<?php

trait StudentFormTrait
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
                <div class="form-group">
                    <label>Τρόπος Χρέωσης</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="paymentType" id="pt_hour_new" value="hour" checked onchange="toggleRateLabel('new')">
                            <label class="form-check-label" for="pt_hour_new">Ωριαία</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="paymentType" id="pt_month_new" value="month" onchange="toggleRateLabel('new')">
                            <label class="form-check-label" for="pt_month_new">Μηνιαία</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label id="rate_label_new">Τιμή ώρας (€)</label>
                    <input type="number" class="form-control" name="rate" min="0" step="0.5" value="10" style="max-width:120px;" required>
                </div>
                <button type="submit" class="btn btn-success">Υποβολή</button>
            </form>
        </div>
        <script>
            function toggleRateLabel(formId) {
                var isMonth = document.querySelector('input[name="paymentType"]:checked').value === 'month';
                document.getElementById('rate_label_' + formId).textContent = isMonth ? 'Τιμή μήνα (€)' : 'Τιμή ώρας (€)';
            }
        </script>
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
                <div class="form-group">
                    <label>Τρόπος Χρέωσης</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="paymentType" id="pt_hour_edit" value="hour" <?php echo ($row['paymentType'] ?? 'hour') !== 'month' ? 'checked' : ''; ?> onchange="toggleRateLabelEdit()">
                            <label class="form-check-label" for="pt_hour_edit">Ωριαία</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="paymentType" id="pt_month_edit" value="month" <?php echo ($row['paymentType'] ?? '') === 'month' ? 'checked' : ''; ?> onchange="toggleRateLabelEdit()">
                            <label class="form-check-label" for="pt_month_edit">Μηνιαία</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label id="rate_label_edit"><?php echo ($row['paymentType'] ?? 'hour') === 'month' ? 'Τιμή μήνα (€)' : 'Τιμή ώρας (€)'; ?></label>
                    <input type="number" class="form-control" name="rate" min="0" step="0.5" value="<?php echo htmlspecialchars($row['rate'] ?? 10); ?>" style="max-width:120px;" required>
                </div>
                <button type="submit" class="btn btn-success" name="updateStudent">Υποβολή</button>
                <script>
                    function toggleRateLabelEdit() {
                        var isMonth = document.getElementById('pt_month_edit').checked;
                        document.getElementById('rate_label_edit').textContent = isMonth ? 'Τιμή μήνα (€)' : 'Τιμή ώρας (€)';
                    }
                </script>
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
}
