<a name="top"></a>
<div class="tytul">
    Timesheet for <?php echo("$year-$month"); ?><br>
</div>

<div class="tekst">

    <?php
    switch ($month) {
        case 1:
            $NEXTMONTH = 2;
            $NEXTYEAR = $year;
            $PREVMONTH = 12;
            $PREVYEAR = $year - 1;
            break;
        case 12:
            $NEXTMONTH = 1;
            $NEXTYEAR = $year + 1;
            $PREVMONTH = 11;
            $PREVYEAR = $year;
            break;
        default:
            $NEXTMONTH = $month + 1;
            $NEXTYEAR = $year;
            $PREVMONTH = $month - 1;
            $PREVYEAR = $year;
    }
    $pointsDisplayed = false;
    $rights_viewallchars = has_permissions($permissions, "Administrator,ViewAllCharacters");
    $rights_edithours = has_permissions($permissions, "Administrator,EditHoursPerPoint");

    function pointshrefedit($nr) {
        echo("<a href=\"/points/edit/$nr\" title=\"Click to edit this activity\">");
    }

    function charhrefedit($nr) {
        echo("<a href=\"character/view/$nr\" title=\"Click to open character information\">");
    }
    ?>
    <table border="0" cellspacing="3" cellpadding="0">
        <tr><td>
                <form method="get" action="">
                    <input type="hidden" name="id" value="0">
                    <input type="hidden" name="id2" value="0">
                    <input type="hidden" name="date" value="<?php echo(sprintf("%04d", $PREVYEAR) . sprintf("%02d", $PREVMONTH)); ?>">
                    <input type="submit" value="&laquo; previous month">
                </form>
            </td><td>
                <form method="get" action="">
                    <input type="hidden" name="id" value="0">
                    <input type="hidden" name="id2" value="0">
                    <input type="hidden" name="date" value="<?php echo(sprintf("%04d", $NEXTYEAR) . sprintf("%02d", $NEXTMONTH)); ?>">
                    <input type="submit" value="next month &raquo;">
                </form>
            </td></tr></table>
    <?php /*
      <a href="?id=<?php echo($MENUITEM); ?>&date=<?php echo(sprintf("%04d", $PREVYEAR).sprintf("%02d", $PREVMONTH)); ?>">&laquo; previous month</a> |  <a href="?id=<?php echo($MENUITEM); ?>&date=<?php echo(sprintf("%04d", $NEXTYEAR).sprintf("%02d", $NEXTMONTH)); ?>">next month &raquo;</a><br/>
     */ ?>
    <a href="#down">Scroll down</a>
</div>

<?php foreach ($corps as $corp): ?>
    <!--$days = "";
    $activities = "";-->
    <h1><img src="https://image.eveonline.com/Corporation/<?php echo $corp->corporationID ?>_64.png" style="vertical-align: middle;"> <?php echo $corp->corporationName ?></h1>
    <div id="accrd_<?php echo $corp->corporationID; ?>">
        <div>
            <table cellspacing="2" cellpadding="0" width="100%">
                <tr><td width="40%" style="vertical-align: top;">
                        <?php if (!$pointsDisplayed): ?>
                            <h2>Points
                                <?php if (has_permissions($permissions, "Administrator,EditHoursPerPoint")): ?>
                                    <input type="button" value="Edit hours-per-point" onclick="location.href = '?id=5&id2=10';">
                                <?php endif ?>
                            </h2><table class="lmframework">
                                <tr><th>
                                        Activity
                                    </td><th>
                                        Hours
                                    </td></tr>
                                <?php foreach ($points as $point): ?>
                                    <tr><td>
                                            <?php
                                            if ($rights_edithours) {
                                                pointshrefedit($point->activityID);
                                                echo $point->activityName;
                                                echo "</a>";
                                            }
                                            ?>
                                        </td><td>
                                            <?php
                                            if ($rights_edithours) {
                                                pointshrefedit($point->activityID);
                                                echo $point->hrsPerPoint;
                                                echo "</a>";
                                            }
                                            ?>
                                        </td></tr>
                                <?php endforeach; ?>
                            </table>

                            <strong>1 point = <?php echo number_format($ONEPOINT, 2, $DECIMAL_SEP, $THOUSAND_SEP) ?> ISK</strong>
                            <?php if (has_permissions($permissions, "Administrator")): ?>
                                <input type="button" value="Edit" onclick="location.href = '?id=5&id2=0';">
                            <?php endif; ?>
                            <br/>
                            <?php
                            $pointsDisplayed = true;
                        endif;
                        ?>

                    </td><td width="60%" style="vertical-align: top;">

                        <?php
                        $sumstat = 0.0;
                        $sumjobs = 0;
                        if (count($stats) > 0):
                            ?>
                            <h2>Statistics</h2>
                            <table class="lmframework">
                                <tr><th>
                                        Activity
                                    </th><th>
                                        Jobs
                                    </th><th>
                                        Hours
                                    </th></tr>
                                <?php foreach ($stats[$corp->corporationID] as $stat): ?>
                                    <tr><td>
                                            <?php echo($stat->activityName) ?>
                                        </td><td style="text-align: right;">
                                            <?php
                                            echo(number_format($stat->jobs, 0, $DECIMAL_SEP, $THOUSAND_SEP));
                                            $sumjobs+=$stat->jobs;
                                            ?>
                                        </td><td style="text-align: right;">
                                            <?php
                                            echo(number_format($stat->hours, 0, $DECIMAL_SEP, $THOUSAND_SEP));
                                            $sumstat+=$stat->hours;
                                            ?>
                                        </td></tr>
                                <?php endforeach; ?>
                                <tr><th>
                                        TOTAL:
                                    </th><th style="text-align: right;">
                                        <?php echo(number_format($sumjobs, 0, $DECIMAL_SEP, $THOUSAND_SEP)); ?>
                                    </th><th style="text-align: right;">
                                        <?php echo(number_format($sumstat, 0, $DECIMAL_SEP, $THOUSAND_SEP)); ?>
                                    </th></tr>
                            </table>
                        <?php endif ?>
                    </td></tr></table>
        </div>
    </div>

    <table class="lmframework">
        <tr><th width="32" style="padding: 0px; text-align: center;">
                <b></b>
            </th><th style="text-align: center;">
                <b>Name</b>
            </th><th width="64" style="text-align: center;">
                <b>Copying</b>
            </td><th width="64" style="text-align: center;">
                <b>Invention</b>
            </th><th width="64" style="text-align: center;">
                <b>Manufacturing</b>
            </th><th width="64" style="text-align: center;">
                <b>ME</b>
            </th><th width="64" style="text-align: center;">
                <b>PE</b>
            </th><th width="64" style="text-align: center;">
                <b>Reverse engineering</b>
            </th><th width="48" style="text-align: center;">
                <b>Points</b>
            </th><th width="96" style="text-align: center;">
                <b>ISK</b>
                </td>
        </tr>
        <?php
        $totals['ISK'] = 0.0;
        $totals['Copying'] = 0.0;
        $totals['Invention'] = 0.0;
        $totals['Manufacturing'] = 0.0;
        $totals['Researching Material Efficiency'] = 0.0;
        $totals['Researching Time Efficiency'] = 0.0;
        $totals['Reverse Engineering'] = 0.0;
        $totals['totalpoints'] = 0.0;
        if ($mychars) {
            echo('<tr><th colspan="10" style="text-align: center;">My characters</th></tr>');
        }
        foreach ($rearrange as $row):
            if ($mychars != false && in_array($row['characterID'], $mychars)):
                ?>
                <tr><td style="padding: 0px;">
                        <?php
                        if ($rights_viewallchars) {
                            charhrefedit($row['characterID']);
                        }

                        echo("<img src=\"https://image.eveonline.com/character/${row['characterID']}_32.jpg\" title=\"${row['name']}\" />");

                        if ($rights_viewallchars) {
                            echo "</a>";
                        }
                        ?>
                    </td><td>
                        <?php
                        if ($rights_viewallchars) {
                            charhrefedit($row['characterID']);
                        }

                        echo(stripslashes($row['name']));

                        if ($rights_viewallchars) {
                            echo "</a>";
                        }
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php
                        echo(number_format($row['activities']['Copying']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Copying']+=$row['activities']['Copying']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php
                        echo(number_format($row['activities']['Invention']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Invention']+=$row['activities']['Invention']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php
                        echo(number_format($row['activities']['Manufacturing']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Manufacturing']+=$row['activities']['Manufacturing']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php
                        echo(number_format($row['activities']['Researching Material Efficiency']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Researching Material Efficiency']+=$row['activities']['Researching Material Efficiency']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php
                        echo(number_format($row['activities']['Researching Time Efficiency']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Researching Time Efficiency']+=$row['activities']['Researching Time Efficiency']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php
                        echo(number_format($row['activities']['Reverse Engineering']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Reverse Engineering']+=$row['activities']['Reverse Engineering']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php
                        echo(number_format(stripslashes($row['totalpoints']), 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['totalpoints']+=$row['totalpoints'];
                        ?>
                    </td><td style="text-align: right;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php echo(number_format(stripslashes($row['wage']), 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?>
                    </td>
                </tr>
                <?php
                $totals['ISK']+=stripslashes($row['wage']);
            endif;
        endforeach;
        ?>

        <?php if ($mychars):
            ?>
            <tr><th width="32" style="padding: 0px; text-align: center;">
                    <b></b>
                </th><th style="text-align: left;">
                    <b>My total</b>
                </th><th width="64" style="text-align: center;">
                    <b><?php echo(number_format($totals['Copying'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
                </th><th width="64" style="text-align: center;">
                    <b><?php echo(number_format($totals['Invention'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
                </th><th width="64" style="text-align: center;">
                    <b><?php echo(number_format($totals['Manufacturing'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
                </th><th width="64" style="text-align: center;">
                    <b><?php echo(number_format($totals['Researching Material Efficiency'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
                </th><th width="64" style="text-align: center;">
                    <b><?php echo(number_format($totals['Researching Time Efficiency'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
                </th><th width="64" style="text-align: center;">
                    <b><?php echo(number_format($totals['Reverse Engineering'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
                </th><th width="48" style="text-align: center;">
                    <b><?php echo(number_format($totals['totalpoints'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
                </th><th width="96" style="text-align: right;">
                    <b><?php echo(number_format($totals['ISK'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
                </th>
            </tr>
            <?php
        //echo('<tr><th colspan="10" style="text-align: center;">Other characters</th></tr>');
        endif;
        foreach ($rearrange as $row):
            if ($mychars == false || !in_array($row['characterID'], $mychars)):
                ?>
                <tr><td style="padding: 0px;">
                        <?php
                        if ($rights_viewallchars)
                            charhrefedit($row['characterID']);

                        echo("<img src=\"https://image.eveonline.com/character/${row['characterID']}_32.jpg\" title=\"${row['name']}\" />");
                        if ($rights_viewallchars)
                            echo('</a>');
                        ?>
                    </td><td>
                        <?php
                        if ($rights_viewallchars)
                            charhrefedit($row['characterID']);
                        echo(stripslashes($row['name']));
                        if ($rights_viewallchars)
                            echo('</a>');
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php echo(number_format($row['activities']['Copying']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Copying']+=$row['activities']['Copying']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php echo(number_format($row['activities']['Invention']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Invention']+=$row['activities']['Invention']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
            <?php echo(number_format($row['activities']['Manufacturing']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
            $totals['Manufacturing']+=$row['activities']['Manufacturing']['points'];
            ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php echo(number_format($row['activities']['Researching Material Efficiency']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Researching Material Efficiency']+=$row['activities']['Researching Material Efficiency']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php echo(number_format($row['activities']['Researching Time Efficiency']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Researching Time Efficiency']+=$row['activities']['Researching Time Efficiency']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                        <?php echo(number_format($row['activities']['Reverse Engineering']['points'], 2, $DECIMAL_SEP, $THOUSAND_SEP));
                        $totals['Reverse Engineering']+=$row['activities']['Reverse Engineering']['points'];
                        ?>
                    </td><td style="text-align: center;">
                        <!--charhrefedit($row['characterID']);-->
                <?php echo(number_format(stripslashes($row['totalpoints']), 2, $DECIMAL_SEP, $THOUSAND_SEP));
                $totals['totalpoints']+=$row['totalpoints'];
                ?>
                    </td><td style="text-align: right;">
                        <!--charhrefedit($row['characterID']);-->
            <?php echo(number_format(stripslashes($row['wage']), 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?>
                    </td>
                </tr><?php
            $totals['ISK']+=stripslashes($row['wage']);
        endif;
    endforeach;
    ?>
        <tr><th width="32" style="padding: 0px; text-align: center;">
                <b></b>
            </th><th style="text-align: left;">
                <b>Total</b>
            </th><th width="64" style="text-align: center;">
                <b><?php echo(number_format($totals['Copying'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
            </th><th width="64" style="text-align: center;">
                <b><?php echo(number_format($totals['Invention'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
            </th><th width="64" style="text-align: center;">
                <b><?php echo(number_format($totals['Manufacturing'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
            </th><th width="64" style="text-align: center;">
                <b><?php echo(number_format($totals['Researching Material Efficiency'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
            </th><th width="64" style="text-align: center;">
                <b><?php echo(number_format($totals['Researching Time Efficiency'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
            </th><th width="64" style="text-align: center;">
                <b><?php echo(number_format($totals['Reverse Engineering'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
            </th><th width="48" style="text-align: center;">
                <b><?php echo(number_format($totals['totalpoints'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
            </th><th width="96" style="text-align: right;">
                <b><?php echo(number_format($totals['ISK'], 2, $DECIMAL_SEP, $THOUSAND_SEP)); ?></b>
            </th>
        </tr>
    </table>

    <div class="tekst">
        <a href="#top">Scroll up</a>
        <a name="down"></a>

    </div><br>
<?php endforeach; ?>
