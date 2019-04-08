<div class="print-container">
<div style="height: 300px;"></div>
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
        $append = $append . "<td width='12%' style='padding-left: 25px;'>" . $quantity . "</td>";
        $append = $append . "<td width='10%' style='padding-left: 5px;'>units</td>";
        $append = $append . "<td width='54%' style='text-align: justify;   '>" . $itemdescription . "</td>";
        $append = $append . "<td width='12%' style=''>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>

    <table border="0" width="100%">
        <?php
            echo $fin;
        ?>
    </table>

        <table  width="100%" border="0">
            <?php
             $x=0;
             while($x<95) {
                 $x++;
             ?>
            <tr class="nospace-border">
                <td width='12%' style='padding-left: 25px;'></>
                <td width='10%' style='padding-left: 5px;'></td>
                <td width='54%' style='text-align: justify'>    </td>
                <td width="12%" style="padding-left: 50px;"></td>
            </tr>
            <?php }?>
            <tr class="nospace-border">
                <td width='12%' style='padding-left: 25px;'></>
                <td width='10%' style='padding-left: 5px;'></td>
                <td width='54%' style='text-align: justify'>    </td>
                <td width="12%" style="padding-left: 50px;"><?= number_format($totalcost,2); ?></td>
            </tr>
        </table>
    </div>

</div>
