<div class="print-container">
<div style="height: 215px;"></div>
<?php
    $fin="";
    $x=0;
    $summary=0;
    $yy="";

    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["bids_item_description"];
        $quantity = $pr["bids_quantity"];
        $price = $pr["bids_price"];
        $totalcost =  $quantity * $price;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='20%' style='padding-left: 40px;'>units</td>";
        $append = $append . "<td width='44%' style='text-align: justify;padding-left: 40px;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 60px;'>" . $quantity . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 50px;'>" . $price . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 25px;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
        $summary = $summary + $totalcost;

        $yy = "<tr class=\"nospace-border\">";
        $yy = $yy . "<td width='88%' colspan='4'>".strtoupper(Yii::$app->formatter->asSpellout($summary))." PESOS ONLY</td>";
        $yy = $yy . "<td width='12%' style='padding-left: 25px;'>" . number_format($summary,2) . "</td>";
        $yy = $yy . "</tr>";

        $cc = "<tr class=\"nospace-border\">";
        $cc = $cc . "<td width='20%' style='padding-left:40px;visibility: false;'></td>";
        $cc = $cc . "<td width='44%' style='text-align: justify;padding-left: 40px;display: hidden;'></td>";
        $cc = $cc . "<td width='12%' style='padding-left: 60px;visibility: false;'></td>";
        $cc = $cc . "<td width='12%' style='padding-left: 50px;visibility: false;'></td>";
        $cc = $cc . "<td width='12%' style='padding-left: 25px;'></td>";
        $cc = $cc . "</tr>";
    }

    ?>

    <table border="0" width="100%">
        <?php
            echo $fin;
            //echo $yy;
        ?>
    </table>

</div>
