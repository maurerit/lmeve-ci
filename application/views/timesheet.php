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
    </div><!--line 198 of 00.php from original lmeve.-->


<?php endforeach; ?>
