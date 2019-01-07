<div class="print-container">
<?php
    $fin="";
    $x=0;
    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["purchase_request_details_item_description"];
        $quantity = $pr["purchase_request_details_quantity"];
        $price = $pr["purchase_request_details_price"];
        $totalcost =  $quantity * $price;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='10%' style='padding-left: 25px;'>units</td>";
        $append = $append . "<td width='60%' style=''>" . $itemdescription . "</td>";
        $append = $append . "<td width='6%' style=''>" . $quantity . "</td>";
        $append = $append . "<td width='12%' style=''>" . $price . "</td>";
        $append = $append . "<td width='12%' style=''>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>
<div style="height: 90px;"></div>
<table width="100%">
    <tr class="nospace-border">
        <td width="60%" style="padding-left: 25px;">Department of Science And Technology</td>
        <td width="30%" style=""><?= $model->purchase_request_number ?></td>
        <td width="10%"><?= $model->purchase_request_date ?></td>
    </tr>
</table>

<div style="height: 90px;"></div>

    <table border="0" width="100%">
        <?php
            echo $fin;
        ?>
    </table>

<div style="height:400px;"></div>

<table>
    <tr class="nospace-border">
        <td><?= $model->purchase_request_purpose ?></td>
    </tr>
    <tr class="nospace-border">
        <td>Project Reference No. : <?= $model->purchase_request_referrence_no ?></td>
    </tr>
    <tr class="nospace-border">
        <td>Project Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?= $model->purchase_request_project_name ?></td>
    </tr>
    <tr class="nospace-border">
        <td>Project Location &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?= $model->purchase_request_location_project ?></td>
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