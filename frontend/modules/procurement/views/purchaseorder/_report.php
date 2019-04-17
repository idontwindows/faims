<div class="print-container">
<div style="height: 215px;"></div>
<?php
    $fin="";
    $x=0;
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
    }
    ?>

    <table border="0" width="100%">
        <?php
            echo $fin;
        ?>
    </table>

</div>
