<?php
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaseRequestDetails;
use common\models\procurement\Section;
use common\models\procurement\Division;
use common\models\procurement\Department;
use common\modules\pdfprint;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\db\Query;

$BaseURL = $GLOBALS['frontend_base_uri'];
/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\PurchaserequestSearch */
/* @var $model common\models\procurement\Lineitembudget */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL.'js/custom.js');

$prdetails = new PurchaseRequestDetails();
$departmentmodel = new Department();
$con =  Yii::$app->db;
$section = Section::find()->all();
$divisions = Division::find()->all();
$command = $con->createCommand("SELECT `tbl_employee`.`employee_id`,CONCAT(`tbl_employee`.`lastname`,', ',`tbl_employee`.`firstname`,' ',`tbl_employee`.`middlename`) AS employeename 
    FROM `fais-procurement`.`tbl_employee`");
$command2 = $con->createCommand("SELECT unit_type_id, name_short AS units FROM `tbl_unit_type`
UNION ALL
SELECT unit_type_id, name_long AS units FROM `tbl_unit_type`
ORDER BY units");
$units = $command2->queryAll();
$employees = $command->queryAll();
$listEmployees = ArrayHelper::map($employees, 'employee_id', 'employeename');
$listSection = ArrayHelper::map($section, 'section_id', 'name');
$listDivisions = ArrayHelper::map($divisions,'division_id','name');
$lineItemBudget = ArrayHelper::map($section,'section_id','name');
$listUnits = ArrayHelper::map($units,'unit_type_id','units');
?>

<div class="purchaserequest-modal">
    <div class="col-lg-12">
        <div id="add-container">
            <div class="popup-container">
                <div class="mypopup" style="background: #ffffff;">
                    <div class="col-lg-12">
                        <div class="row">
                            <h5 style="text-align: center">Add Item</h5>
                            <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <?=
                                        Select2::widget([
                                            'name' => 'txtunits',
                                            'id'=> 'txtunits',
                                            'data' => $listUnits,
                                            'options' => ['placeholder' => 'Select Unit Type','tabindex'=>0,],
                                            'pluginEvents' => [
                                                "change" => "function() {
                                                                 var data=$(this).val();
                                                        }",
                                            ],
                                        ]);
                                        ?>

                                      <!--  <input type="text" class="form-control" placeholder="Units" id="txtunits" name="txtunits" required> -->
                                        <span class="one req">* unit is required</span>
                                        <div class="space-20"></div>
                                    </div>
                                    <div class="col-lg-12">
                                        <textarea row="50" class="form-control" placeholder="Item Description" id="txtitemdesc" name="txtitemdesc" required></textarea><span class="two req">* description is required</span>
                                        <div class="space-20"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="number" class="form-control" placeholder="Qty" id="txtqty" name="txtqty" required><span class="three req">* quantity is required</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="number" class="form-control" placeholder="0.00" id="txtcost" required><span class="four req">* cost is required</span>
                                        <div class="space-20"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="btn btn-success btn-block" id="btnAdd">Add <span class="fa fa-plus"></span></button>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="btn btn-danger btn-block" id="btnClose">Close <span class="fa fa-remove"></span></button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-scroll">
    <?php $form = ActiveForm::begin(['id' => 'purchaserequest-form', 'enableClientValidation' => true]);
   echo $form->field($model,'lineitembudgetlist')->hiddenInput(['value'=>'test'])->label(false);
    ?>
        <!-- ********************************************** -->
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
                    'data' => $listDivisions,
                    'id'=> 'cboDivision',
                    'name'=> 'cboDivision',
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Division','tabindex'=>0,],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'purchase_request_number')
                    ->textInput(['placeholder'=>'<AutoGenerated>','readonly'=>'true','tabindex'=>'-1'])?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'purchase_request_date')
                    ->input("date") ?>
            </div>
        </div>
            <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'section_id')->widget(Select2::classname(), [
                    'data' => $listSection,
                    'id'=> 'cboSection',
                    'name'=> 'cboSection',
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Section'],
                    'pluginOptions'=> [
                        'allowClear' => false
                    ],
                ]); ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'purchase_request_sai_number')
                    ->textInput(['placeholder'=>''])?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'purchase_request_saidate')
                    ->input("date") ?>
            </div>
         </div>
        <!-- ********************************************** -->
        <div class="row">
                <div class="col-lg-12">
                    <h4 style="border-bottom: #1c1c1c 2.5px solid;text-transform: uppercase;">Line Item Budget</h4>
                </div>
                <div class="col-lg-4">
                    <?=
                    Select2::widget([
                        'data'=> $listSection,
                        'id' => 'cbolineitembudget',
                        'name' => 'cbolineitembudget'
                    ])
                    ?>
                </div>
                <div class="col-lg-2">
                    <?= Html::button('Insert Item [F2]',[
                            'id'=>'btnInsert',
                            'name'=>'btnInsert',
                            'class'=>'btn btn-sm btn-success btn-block small',
                            'tabindex'=>'-1',
                        ]);
                    ?>
                </div>
                <div class="col-lg-2">
                    <?= Html::button('Add Item [F4]',[
                        'id'=>'btnAddLineItem',
                        'name'=>'btnAddLineItem',
                        'class'=>'btn btn-sm btn-success btn-block small',
                        'tabindex'=>'-1',
                    ]);
                    ?>
                </div>
                <div class="col-lg-6"></div>


            <div class="col-lg-12">
                    <div class="table-scroll">
                        <div id="a_page_loader" data-id="">
                            <div class="control-options false">
                                <div class="control-options-items">
                                    <h1 id="tbl-item-selected"> selected</h1>
                                </div>
                            </div>
                            <span style="float: right;display: inline-block!important;"><a href="#" id="max-scroll"><i class="fa fa-caret-down"></i> <span id="scroll-description"> Maximize </span> </a></span>
                           <div style="clear: both;"></div>
                            <table class="table table-striped" id="pr-table">
                                <thead>
                                <tr class="table-header">
                                    <td><div class="radio-container"><div id="main-radio" class="radio"><input type="radio" name="test" class="radio-ui"></div></div></td>
                                    <td>Detail# </td>
                                    <td>Unit</td>
                                    <td>Item Description</td>
                                    <td>Quantity</td>
                                    <td>Unit Cost</td>
                                    <td>Total Cost</td>
                                </tr>
                                </thead>
                                <tbody class="table-body">
                                <!--
                                <tr class="table-data">
                                        <td><div class="radio-container"><div class="radio tbl-tmt" data-id="1" data-radio="test"><input type="radio" name="test" class="radio-ui"></div></div></td>
                                    <td>Unit</td>
                                    <td>Item Lorem Ipsum</td>
                                    <td>3</td>
                                    <td>300.00</td>
                                    <td>900.00</td>
                                </tr>
                                -->
                                </tbody>
                            </table>

                        </div>
                        <div style="bottom:0;">
                            <button type="button" disabled class="delete-row btn btn-warning">Delete Row</button>
                        </div>
                    </div>
                </div>
        </div>
        <!-- ********************************************** -->
        <div class="row">
            <div class="col-lg-4">
                <div class="col-lg-12">
                <?= $form->field($model, 'purchase_request_purpose')->textarea(['placeholder'=>''])?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="col-lg-12">
                    <?= $form->field($model, 'purchase_request_referrence_no')->textInput(['placeholder'=>''])?>
                </div>
                <div class="col-lg-12">
                    <?= $form->field($model, 'purchase_request_project_name')->textInput(['placeholder'=>''])?>
                </div>
                <div class="col-lg-12">
                    <?= $form->field($model, 'purchase_request_location_project')->textInput(['placeholder'=>''])?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="col-lg-12">
                    <h4 style="border-bottom: #1c1c1c 2px solid;text-transform: uppercase;margin-top: 0px">Assignatory</h4>
                </div>
                <div class="col-lg-12">
                    <?= $form->field($model, 'purchase_request_requestedby_id')->widget(Select2::classname(), [
                        'data' => $listEmployees,
                        'id'=> 'cboEmployee',
                        'name'=> 'cboEmployee',
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Employee'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-lg-12">
                    <?= $form->field($model, 'purchase_request_approvedby_id')->widget(Select2::classname(), [
                        'data' => $listEmployees,
                        'id'=> 'cboApproved',
                        'name'=> 'cboApproved',
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Employee'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
        <!-- ********************************************** -->
        <div class="row">
            <div class="col-lg-12">
                <h4 style="border-bottom: #1c1c1c 2px solid;text-transform: uppercase;"></h4>
            </div>
                <div class="col-lg-2">
                    <div class="space-10"></div>
                    <div id="removesubmit">
                        <?= Html::submitButton($model->isNewRecord ? 'Create Request' : 'Update Request' , ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary','id'=> 'btnSubmit','name'=>'btnSubmit']) ?>
                    </div>
                </div>
                <div class="col-lg-10">
                </div>
        </div>

    <?php ActiveForm::end(); ?>

        <div class="row">
            <div class="col-lg-10">
            </div>
            <div class="col-lg-2">
                <a href="reportpr?id=<?=$model->purchase_request_id?>" class="btn-pdfprint btn btn-lg btn-primary btn-block">Print</a>
                <?= pdfprint\Pdfprint::widget([
                    'elementClass' => '.btn-pdfprint'
                ]); ?>
            </div>
        </div>


    </div>

</div>

<?php $this->registerJsFile($BaseURL.'js/procurement/purchaserequest/purchaserequest.js'); ?>