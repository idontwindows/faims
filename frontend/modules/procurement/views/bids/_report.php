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
        $append = "<tr class=\"nospace-border\">";
        $append = $append . "<td width='50' style='text-align: center;'>".$x."</td>";
        $append = $append . "<td width='400' style='padding-left: 25px;'>" . $itemdescription . "</td>";
        $append = $append . "<td width='150' style='text-align: center; padding-left: 55px;'>" . $quantity . " pack</td>";
        //$append = $append . "<td>" . $price . "</td>";
        //$append = $append . "<td>" . $totalcost . "</td>";
        $append = $append . "</tr>";
        $fin = $fin . $append;
    }
    ?>
<div style="height: 68px;"></div>

   <table class="table table-responsive">
       <tbody>
        <tr class="nospace-border">
            <td width="70%" style="padding: 0px;"></td>
            <td width="30%" style="padding: 0px; padding-left: 15px; padding-top: 0px;"><?= $model->purchase_request_referrence_no ?></td>
        </tr>
        <tr class="nospace-border">
            <td width="70%" style="padding: 0px;"></td>
            <td width="30%" style="padding: 0px;padding-left: 15px;"><?= $model->purchase_request_project_name ?></td>
        </tr>
        <tr class="nospace-border">
            <td width="70%" style="padding: 0px;"></td>
            <td width="30%" style="padding: 0px;padding-left: 15px;"><?= $model->purchase_request_location_project ?></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding: 0px; padding-top: 34px" width="">City Mart Z.C</td>
        </tr>
        <tr class="nospace-border">
            <td style="padding: 0px;" width="">Zamboanga City.</td>
        </tr>
        <tr class="nospace-border">
            <td style="height: 50px;"></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding: 0px; padding-top:4px; padding-left:50px;" width="">May 5, 2018</td>
        </tr>

        <tr class="nospace-border">
            <td style="height: 50px;"></td>
        </tr>

        <tr class="nospace-border">
            <td width="80%"></td>
            <td style="padding: -10px; padding-left: -20px; text-align: center; text-decoration: underline;" width="20%">THELMA E. DIEGO</td>
        </tr>
        <tr class="nospace-border">
            <td width="80%"></td>
            <td style="padding: 0px; padding-left: -20px; text-align: center;" width="20%">Supply Officer</td>
        </tr>

       </tbody>
    </table>

    <div style="height: 152px;"></div>

    <table border="0">
        <?php
            echo $fin;
        ?>
    </table>

</div>