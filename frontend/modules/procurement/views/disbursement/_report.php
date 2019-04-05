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
        $dv_type = $pr["dv_type"];
        $assig1 =  $pr["Assig1"];
        $assig2 =  $pr["Assig2"];
        $assig3 =  $pr["Assig3"];
        $Assig1Position =  $pr["Assig1Position"];
        $Assig2Position =  $pr["Assig2Position"];
        $Assig3Position =  $pr["Assig3Position"];
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

    <div style="height: 25px;"></div>
<?php if($dv_type=='MDS') { ?>

    <table border="0" width="100%">
        <tr style="text-align: left;">
            <td style="text-align: center;"><?= $assig2; ?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?= $assig3; ?></td>
        </tr>
        <tr style="text-align: right;">
            <td style="text-align: center;"><?= $Assig2Position; ?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?= $Assig3Position; ?></td>
        </tr>
    </table>
    <div style="height: 35px;"></div>
    <table border="0" width="100%">
        <tr style="text-align: left;">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">LAND BANK OF THE PHILIPPINES</td>
            <td style="text-align: center;"></td>
        </tr>
        <tr style="text-align: right;">
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?= $payee; ?></td>
            <td style="text-align: center;"></td>
        </tr>
    </table>

<?php }else{ ?>


<table border="0" width="100%">
    <tr style="text-align: left;">
        <td style="text-align: center;"><?= $assig1; ?></td>
        <td style="text-align: center;"><?= $assig2; ?></td>
        <td style="text-align: center;"><?= $assig3; ?></td>
    </tr>
    <tr style="text-align: right;">
        <td style="text-align: center;"><?= $Assig1Position; ?></td>
        <td style="text-align: center;"><?= $Assig2Position; ?></td>
        <td style="text-align: center;"><?= $Assig3Position; ?></td>
    </tr>
</table>
    <div style="height: 35px;"></div>
    <table border="0" width="100%">
        <tr style="text-align: left;">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">LAND BANK OF THE PHILIPPINES</td>
            <td style="text-align: center;"></td>
        </tr>
        <tr style="text-align: right;">
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?= $payee; ?></td>
            <td style="text-align: center;"></td>
        </tr>
    </table>

<?php } ?>


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

    <div style="height: 25px;"></div>

    <?php if($dv_type=='MDS') { ?>

        <table border="0" width="100%">
            <tr style="text-align: left;">
                <td style="text-align: center;"><?= $assig2; ?></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"><?= $assig3; ?></td>
            </tr>
            <tr style="text-align: right;">
                <td style="text-align: center;"><?= $Assig2Position; ?></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"><?= $Assig3Position; ?></td>
            </tr>
        </table>
        <div style="height: 35px;"></div>
        <table border="0" width="100%">
            <tr style="text-align: left;">
                <td style="text-align: center;"></td>
                <td style="text-align: center;">LAND BANK OF THE PHILIPPINES</td>
                <td style="text-align: center;"></td>
            </tr>
            <tr style="text-align: right;">
                <td style="text-align: center;"></td>
                <td style="text-align: center;"><?= $payee; ?></td>
                <td style="text-align: center;"></td>
            </tr>
        </table>

    <?php }else{ ?>


        <table border="0" width="100%">
            <tr style="text-align: left;">
                <td style="text-align: center;"><?= $assig1; ?></td>
                <td style="text-align: center;"><?= $assig2; ?></td>
                <td style="text-align: center;"><?= $assig3; ?></td>
            </tr>
            <tr style="text-align: right;">
                <td style="text-align: center;"><?= $Assig1Position; ?></td>
                <td style="text-align: center;"><?= $Assig2Position; ?></td>
                <td style="text-align: center;"><?= $Assig3Position; ?></td>
            </tr>
        </table>
        <div style="height: 35px;"></div>
        <table border="0" width="100%">
            <tr style="text-align: left;">
                <td style="text-align: center;"></td>
                <td style="text-align: center;">LAND BANK OF THE PHILIPPINES</td>
                <td style="text-align: center;"></td>
            </tr>
            <tr style="text-align: right;">
                <td style="text-align: center;"></td>
                <td style="text-align: center;"><?= $payee; ?></td>
                <td style="text-align: center;"></td>
            </tr>
        </table>

    <?php } ?>

</div>
