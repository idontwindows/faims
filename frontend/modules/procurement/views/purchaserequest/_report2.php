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

        if ($price=='0.00' || $price == null) {
            $price = "";
            $totalcost = "";
        }else{
            $totalcost = number_format($totalcost,2) ;
            $price = number_format($price, 2);
        }
        $append = "<tr style='vertical-align: middle;'>";
        $append = $append . "<td width='10%' style='vertical-align: top;'></td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left: 60px;text-align: center;'>".$unit."</td>";
        $append = $append . "<td width='50%' style='text-align: left;padding-left: 50px;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;text-align: center;padding-left: 200px;'>" . $quantity . "</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left: 80px;text-align: left'>" . $price . "</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left: 80px;text-align: left'>" . $totalcost . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }

    ?>

    <table border="0" width="100%">
        <tbody>
        <?php
        echo $fin;
        ?>
        </tbody>
    </table>

</div>
