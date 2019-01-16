<div class="print-container">
<div style="height: 25px;"></div>
<?php //$model = new \common\models\procurement\Obligationrequest() ?>
<table align="right">
        <tr class="nospace-border">
            <td><?= $model->os_no ?></td>
        </tr>
        <tr class="nospace-border">
            <td><?= $model->os_date ?></td>
        </tr>
</table>
<div style="height: 20px;"></div>
<table>
        <tr class="nospace-border">
            <td style="padding-left: 75px;padding-bottom: 10px;"><?= $model->payee ?></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding-left: 75px;padding-bottom: 10px;"><?= $model->office ?></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding-left: 75px;padding-bottom: 10px;"><?= $model->address ?></td>
        </tr>
</table>
<div style="height:75px;"></div>
<table>
        <tr class="nospace-border">
            <td style="padding-left: 75px;text-align:justify;" width="55%"><?= $model->particulars ?></td>
            <td width="15%" style="text-align: center;"><?= $model->ppa ?></td>
            <td width="10%" style="text-align: center;"><?= $model->account_code ?></td>
            <td width="20%" style="text-align: center;"><?= $model->amount ?></td>
        </tr>
</table>
<div style="height:175px;"></div>
    <table align="right">
        <tr class="nospace-border">
            <td style="text-align: center;padding-right: 40px;"><?= $model->amount ?></td>
        </tr>
</table>
<div style="height:75px;"></div>
<table width="100%">
    <tr class="nospace-border">
        <td width="50%" style="text-align: center;">Rosemarie Salazar</td>
        <td width="50%" style="text-align: center;">Martin A. Wee</td>
    </tr>
    <tr class="nospace-border">
        <td width="50%" style="text-align: center;">ARD-FASTS</td>
        <td width="50%" style="text-align: center;">Regional Director</td>
    </tr>
</table>

</div>