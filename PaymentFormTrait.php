<?php

trait PaymentFormTrait
{
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
}
