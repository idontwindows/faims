<div class="print-container" autosize="0">
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
        <tr class=\"nospace-border\">";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left:10px;text-align:center;'>".$x.".</td>";
        $append = $append . "<td width='10%' style='vertical-align: top;padding-left:10px;text-align:center;'>".$units."</td>";
        $append = $append . "<td width='40%' style='vertical-align: top;padding-left:10px;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='13%' style='vertical-align: top;padding-left:10px;text-align:center;'>" . $quantity . "</td>";
        $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:10px;text-align:right;padding-right:10px;'>" . $price . "</td>";
        $append = $append . "<td width='13%' style='font-size:12px;vertical-align: top;padding-left:10px;text-align:right;padding-right:10px;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
        /*$summary = $summary + $totalcost;

        $yy = '<tfoot>
        <tr>
            <td style="width: 87%; text-align: left;border:none;border:1px solid black;background:white;" colspan="5">'.strtoupper(Yii::$app->formatter->asSpellout($summary))." PESOS ONLY".'</td>
            <td style="width: 13%; text-align: center;border:1px solid black;">'.number_format($summary,2).'</td>      
        </tr>
        </tfoot>';*/
        $cc = "<tr>";
        $cc = $cc . '<td style="width: 10%;  text-align: center;">&nbsp;</td>';
        $cc = $cc . '<td style="width: 10%;  text-align: center;">&nbsp;</td>';
        $cc = $cc . '<td style="width: 10%;  text-align: center;">&nbsp;</td>';
        $cc = $cc . '<td style="width: 10%;  text-align: center;">&nbsp;</td>';
        $cc = $cc . '<td style="width: 10%;  text-align: center;">&nbsp;</td>';
        $cc = $cc . '<td style="width: 10%;  text-align: center;">&nbsp;</td>';
        $cc = $cc . "</tr>";
    }

    /*$yy = "<tr>";
    $yy = $yy . "<td width='86%' colspan='5'>".strtoupper(Yii::$app->formatter->asSpellout($summary))." PESOS ONLY</td>";
    $yy = $yy . "<td width='13%' style='padding-left: 25px;'>{colsum2}</td>";
    $yy = $yy . "</tr>";
    */

    ?>  

    

    <table border="0" width="100%" style="border-collapse: collapse;">
    
    <tbody style="">
            <?php
                echo $fin;   
            ?>

    </tbody>
    </table>   
</div>
