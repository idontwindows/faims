<div class="print-container">

<?php
    $fin="";
    $x=0;
    $s=0;
    foreach ($prdetails as $pr) {
        $x++;
        $itemdescription = $pr["particulars"];
        $price = $pr["dv_amount"];
        $payee = $pr["payee"];
        $dvno = $pr["dv_no"];
        $dv_date = $pr["dv_date"];
        $prno = $pr["taxable"];
        $dvamount = $pr["dv_amount"];
        $totalcost = $price;
        $xxx = '
        <table border="0" width="100%">
        <tr style="text-align: left;">
            <td>'.$payee.'</td>
            <td style="text-align: right;">'.$dvno.'</td>
        </tr>
        <tr style="text-align: right;">
            <td>Zamboanga City</td>
            <td style="text-align: right;">'.$dv_date.'</td>
        </tr>
        <tr style="text-align: right;">
            <td></td>
            <td style="text-align: right;"></td>
        </tr>
        <tr style="text-align: right;">
            <td></td>
            <td style="text-align: right;">'.$dvno.'</td>
        </tr>
        <tr style="text-align: right;">
            <td></td>
            <td style="text-align: right;">'.$dv_date.'</td>
        </tr>
        <tr style="text-align: right;">
            <td>Department of Science and Technology</td>
            <td style="text-align: right;"></td>
        </tr>
        </table>';
        echo $xxx;
        $append = "<tr class=\"nospace-border\">";
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

</div>
