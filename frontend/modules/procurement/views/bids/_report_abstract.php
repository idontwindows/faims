<?php
?>
<div class="print-container">
<?php
    $fin="";
    $x=0;
    $munit="";
    $tempid ="";
    $headerd = "";
    $itemno = "";
    $qty = "";
    $unit = "";
    $item_decription = "";
    $tablecount=count($columns);
    ?>

    <table border="0" class="table">
        <thead>
            <tr class="nospace-border">
                <?php
                $max = 13;//$tablecount;
                $count = $tablecount;
                $i=3;
                while($i<$max) {
                    if($i<7) {
                        echo '<th></th>';
                    }else{
                        if ($i>$count - 1) {
                                echo '<th style="font-size: 9px;">N/A</th>';
                        }else{
                            if ($i>6) {
                                echo '<th style="font-size: 9px;">'.$columns[$i].'</th>';
                            }else{
                                echo '<th>'.$columns[$i].'</th>';
                            }
                        }
                    }
                    $i++;
                }
                ?>
            </tr>
        </thead>

        <tbody>

                <?php
                $max = $tablecount;
                $i=7;

                foreach ($prdetails as $pr) {
                    $x++;
                    $tempid =  $pr[$columns[0]];
                    $headerid = $pr[$columns[1]];
                    $remarks = $pr[$columns[2]];
                    $itemno = $pr[$columns[3]];
                    $qty = $pr[$columns[4]];
                    $unit = $pr[$columns[5]];
                    $item_decription = $pr[$columns[6]];
                    echo '<tr class="nospace-border">';
                    //echo '<td>'.$tempid.'</td>';
                    //echo '<td>'.$headrid.'</td>';
                    echo '<td style="font-size: 11px; width: 3%; text-align: left;">'.$itemno.'</td>';
                    echo '<td style="font-size: 11px; width: 4%; text-align: left;">'.$qty.'</td>';
                    echo '<td style="font-size: 11px; width: 4%; text-align: left;">'.$unit.'</td>';
                    echo '<td style="font-size: 11px; width: 25%; text-align: left;">'.$item_decription.'</td>';
                    while($i<$max) {
                        $myval = $pr[$columns[$i]];
                        if($myval=='') { $myval='No Bid';}
                        echo '<td style="font-size: 11px; width: 10%;">'.$myval.'</td>';
                        $i++;
                    }
                    $i=7;
                    echo '</tr>';
                }
                ?>
        </tbody>
    </table>

</div>