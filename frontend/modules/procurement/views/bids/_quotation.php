<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\modules\pdfprint;
use common\components\MyPrint;
use yii\helpers\ArrayHelper;

$con =  Yii::$app->db;
$command = $con->createCommand("SELECT `tbl_employee`.`employee_id`,CONCAT(`tbl_employee`.`lastname`,', ',`tbl_employee`.`firstname`,' ',`tbl_employee`.`middlename`) AS employeename 
    FROM `fais-procurement`.`tbl_employee`");
$employees = $command->queryAll();
$command2 = $con->createCommand("SELECT * FROM `fais-procurement`.`tbl_supplier`");
$supplier = $command2->queryAll();

$listSupplier = ArrayHelper::map($supplier,'supplier_id','supplier_name');
$listEmployees = ArrayHelper::map($employees, 'employee_id', 'employeename');

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Department */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="quotation-form">

    <?php $form = ActiveForm::begin();

    ?>
    <div class="form-group">
            <div class="row">
                <div class="col-lg-12-block">
                    <div class="col-lg-6">
                        <label>Supplier </label>
                        <?=
                        Select2::widget([
                            'data'=> $listSupplier,
                            'id' => 'cbosupplier',
                            'name' => 'cbosupplier'
                        ])
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <label>PR Number </label>
                        <?= Html::label($model->purchase_request_number,'',['placeholder'=>'Address','class'=>'form-control','readonly'=>true]);?>
                    </div>
                </div>
            </div>
            <div class="space-10"></div>
            <div class="row">
                <div class="col-lg-12-block">
                    <div class="col-lg-6">
                        <label>Address </label>
                        <?= Html::textarea('txtaddress','',['placeholder'=>'Address','class'=>'form-control']);?>
                    </div>
                    <div class="col-lg-6">
                        <label>Date </label>
                        <?= Html::label( date("M j, Y", strtotime($model->purchase_request_date)),'',['placeholder'=>'Date','class'=>'form-control','readonly'=>true]);?>
                    </div>
                </div>
            </div>
            <div class="space-10"></div>
            <div class="row">
                <div class="col-lg-12-block">
                    <div class="col-lg-6">
                        <label>Submissions not Later than </label>
                        <?= Html::textInput('txtsubmission','',['placeholder'=>'Address','class'=>'form-control','type'=>'date']);?>
                    </div>
                    <div class="col-lg-6">
                        <label>Supply Officer </label>
                        <?=
                        Select2::widget([
                            'data'=> $listEmployees,
                            'id' => 'cboemployees',
                            'name' => 'cboemployees'
                        ])
                        ?>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="lg-12-block">
                <div class="table-scroll">
                    <div id="a_page_loader" data-id="">
                        <div class="control-options false">
                            <div class="control-options-items">
                                <h1 id="tbl-item-selected"> selected</h1>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead id="myHead">
                            <tr>
                                <!--<td><div class="radio-container"><div id="main-radio" class="radio" <input type="radio" name="test" class="radio-ui"></td>-->
                                <td>Units</td>
                                <td>Item Description</td>
                                <td>Quantity</td>
                                <!--<td>Unit Cost</td>-->
                                <!--<td>Total Cost</td>-->
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $fin="";
                            foreach ($prdetails as $pr) {
                                $itemdescription = $pr["purchase_request_details_item_description"];
                                $quantity = $pr["purchase_request_details_quantity"];
                                $price = $pr["purchase_request_details_price"];
                                $totalcost =  $quantity * $price;

                                $append = "<tr>";
                                //$append = $append . "<td><div class=\"radio-container\"><div class=\"radio tbl-tmt\" id=\"myitems\" data-id=\"\" data-radio=\"\"><input type=\"radio\" name=\"test\" class=\"radio-ui\"></div></div></td>";
                                $append = $append . "<td>Unit</td>";
                                $append = $append . "<td>" . $itemdescription . "</td>";
                                $append = $append . "<td>" . $quantity . "</td>";
                                //$append = $append . "<td>" . $price . "</td>";
                                //$append = $append . "<td>" . $totalcost . "</td>";
                                $append = $append . "</tr>";
                                $fin = $fin . $append;
                            }
                            ?>
                            <?= $fin ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
    <div class="row">
        <div class="col-lg-10">
        </div>
        <div class="col-lg-2">
            <a href="report?id=<?=$model->purchase_request_id?>" class="btn-pdfprint btn btn-lg btn-primary btn-block">Print</a>
            <?= pdfprint\Pdfprint::widget([
                'elementClass' => '.btn-pdfprint'
            ]);?>
        </div>
    </div>
</div>


