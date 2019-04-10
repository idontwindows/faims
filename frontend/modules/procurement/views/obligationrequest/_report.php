<div class="print-container">
<div style="height: 80px;"></div>
<?php //$model = new \common\models\procurement\Obligationrequest() ?>
<table align="right">
        <tr class="nospace-border">
            <td><?= $model->os_no ?></td>
        </tr>
        <tr class="nospace-border">
            <td><?= $model->os_date ?></td>
        </tr>
</table>
<div style="height: 25px;"></div>
<table>
        <tr class="nospace-border">
            <td style="padding-left: 110px;padding-bottom: 10px;"><?= $model->payee ?></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding-left: 110px;padding-bottom: 10px;"><?= $model->office ?></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding-left: 110px;padding-bottom: 10px;"><?= $model->address ?></td>
        </tr>
</table>
<div style="height:25px;"></div>
<table width="100%">
        <tr class="nospace-border">
            <td style="padding-left: 110px;text-align:justify;" width="40%"><?= $model->particulars ?></td>
            <td width="20%" style="text-align: center;"><?= $model->ppa ?></td>
            <td width="20%" style="text-align: center;"><?= $model->account_code ?></td>
            <td width="20%" style="text-align: right;padding-right: 40px;"><?= $model->amount ?></td>
        </tr>
</table>
<div style="height:215px;"></div>
    <table align="right">
        <tr class="nospace-border">
            <td style="text-align: right;padding-right: 40px;"><?= $model->amount ?></td>
        </tr>
</table>
<div style="height:50px;"></div>
    <?php
    $fin="";
    $x=0;
    foreach ($assig as $ob) {
        $requestedby = $ob["RequestedBy"];
        $requestedposition = $ob["RequestedPosition"];
        $fundsavailable = $ob["FundsAvailable"];
        $fundsposition = $ob["FundsAvailablePosition"];
    }
    ?>
<table width="100%">
    <tr class="nospace-border">
        <td width="50%" style="text-align: center;"><?= $requestedby; ?></td>
        <td width="50%" style="text-align: center;"><?= $fundsavailable; ?></td>
    </tr>
    <tr class="nospace-border">
        <td width="50%" style="text-align: center;"><?= $requestedposition; ?></td>
        <td width="50%" style="text-align: center;"><?= $fundsposition; ?></td>
    </tr>
</table>

</div>