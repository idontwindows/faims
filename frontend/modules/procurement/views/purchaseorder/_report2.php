<div class="print-container" autosize="1">
<?php
    $fin="";
    $x=0;
   // $summary=0;
    $yy="";

    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["bids_item_description"];
        $quantity = $pr["bids_quantity"];
        $price = $pr["bids_price"];
        $units = $pr["bids_unit"];
        $totalcost =  $quantity * $price;
        $append = "
        <tr>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left:0px;text-align:center;'>".$x.".</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left:0px;text-align:center;'>".$units."</td>";
        $append = $append . "<td width='40%' style='vertical-align: top;padding:5px;font-size:9px;word-wrap: break-word;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='13%' style='vertical-align: top;font-size:12px;text-align:center;'>" . $quantity . "</td>";
        $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:10px;text-align:center;padding-right:10px;'>" . $price . "</td>";
        $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:10px;text-align:center;padding-right:10px;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }

    ?>  

    

    <table border="0" width="100%" style="border-collapse: collapse;" autosize="1">
    
    <tbody style="">
            <?php
                echo $fin;   
            ?>

    </tbody>
    </table>   
</div>
