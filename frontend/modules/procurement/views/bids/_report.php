<div class="print-container">
<?php
    $fin="";
    $x=0;
    //echo $model->purchase_request_referrence_no;
    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["purchase_request_details_item_description"];
        $quantity = $pr["purchase_request_details_quantity"];
        $price = 5000; //$pr["purchase_request_details_price"];
        $totalcost = 5000;
        if ($quantity>1) {
            $unit = $pr["name_short"];
        } else {
            $unit = $pr["name_long"];
        }
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='50' style='text-align: center;vertical-align: top;'>".$x."</td>";
        $append = $append . "<td width='400' style='padding-left: 25px;vertical-align: top;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='150' style='text-align: center; padding-left: 55px;vertical-align: top;'>" . $quantity . " " . $unit . "</td>";
        //$append = $append . "<td>" . $price . "</td>";
        //$append = $append . "<td>" . $totalcost . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>

    <div></div>

    <table style="height: 200px;" border="0">
        <?php
            echo $fin;
        ?>
    </table>

</div>