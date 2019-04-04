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
            <td style="text-align: right;"></td>
        </tr>
        </table>
        <div style="height: 100px;"></div>';
        echo $xxx;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='75%' style='text-align: justify;   '>" . $itemdescription . "</td>";
        $append = $append . "<td width='25%' style='text-align: center;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>

    <table border="0" width="100%">
        <?php
            echo $fin;
        ?>

    </table>

    <div style="height: 100px;"></div>

<table border="0" width="100%">
    <tr style="text-align: left;">
        <td>ROBERTO B. ABELLA</td>
        <td style="text-align: right;">MARTIN A. WEE</td>
    </tr>
    <tr style="text-align: right;">
        <td>Accountant III</td>
        <td style="text-align: right;">Regional Director</td>
    </tr>
</table>





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
         <div style="height: 125px;"></div>
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
            <td style="text-align: right;"></td>
        </tr>
        </table>
        <div style="height: 100px;"></div>';
        echo $xxx;
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='75%' style='text-align: justify;   '>" . $itemdescription . "</td>";
        $append = $append . "<td width='25%' style='text-align: center;'>" . number_format($totalcost,2) . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>
    <table border="0" width="100%">
        <?php
        echo $fin;
        ?>
    </table>
    <div style="height: 100px;"></div>
    <table border="0" width="100%">
        <tr style="text-align: left;">
            <td>ROBERTO B. ABELLA</td>
            <td style="text-align: right;">MARTIN A. WEE</td>
        </tr>
        <tr style="text-align: right;">
            <td>Accountant III</td>
            <td style="text-align: right;">Regional Director</td>
        </tr>
    </table>


</div>
