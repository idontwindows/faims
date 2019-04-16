<div class="print-container">
<?php
    $fin="";
    $x=0;
    foreach ($prdetails as $pr) {
        $x++;
        if ($x==1) { $unit = $pr["name_short"]; } else { $unit = $pr["name_long"]; }
        $itemdescription = $pr["purchase_request_details_item_description"];
        $quantity = $pr["purchase_request_details_quantity"];
        $price = $pr["purchase_request_details_price"];
        $totalcost =  $quantity * $price;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='14%' style='padding-left: 60px;'>".$unit."</td>";
        $append = $append . "<td width='50%' style='text-align: justify;padding-left: 40px;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 35px;'>" . $quantity . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 30px;'>" . $price . "</td>";
        $append = $append . "<td width='12%' style='padding-left: 35px;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>

    <table border="0" width="100%">
        <?php
            echo $fin;
        ?>
    </table>

</div>
