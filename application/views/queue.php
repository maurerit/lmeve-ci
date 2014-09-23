<?php

//TODO: Move these to a helper as they'll be shared here and with the tasks page
function taskhrefedit($nr, $date) {
    echo("<a href=\"/tasks/char/$date/$nr\" title=\"Click to see tasks for this Character\">");
}

function editqueuehrefedit($nr) {
    echo("<a href=\"/queue/edit/$nr\" title=\"Click to edit this Queue\">");
}

function itemhrefedit($nr) {
    echo("<a href=\"/item/view/$nr\" title=\"Click to open Database\">");
}

$rights = has_permissions($permissions, "Administrator,EditQueue");
?>
<div class="tytul">Corporate Queue <?php echo $year . "-" . $month ?></div>
<?php
switch ($month) {
    case 1:
        $NEXTMONTH = str_pad(2,2,"0",STR_PAD_LEFT);
        $NEXTYEAR = $year;
        $PREVMONTH = 12;
        $PREVYEAR = $year - 1;
        break;
    case 12:
        $NEXTMONTH = str_pad(1,2,"0",STR_PAD_LEFT);
        $NEXTYEAR = $year + 1;
        $PREVMONTH = 11;
        $PREVYEAR = $year;
        break;
    default:
        $NEXTMONTH = str_pad($month + 1,2,"0",STR_PAD_LEFT);
        $NEXTYEAR = $year;
        $PREVMONTH = str_pad($month - 1,2,"0",STR_PAD_LEFT);
        $PREVYEAR = $year;
}
?>
<table>
    <tr>
        <td>
            <?php
            echo form_open("/queue/$PREVYEAR/$PREVMONTH");
            echo form_submit('prev', 'previous month');
            echo form_close();
            ?>
        </td>
        <td>
            <?php
            echo form_open("/queue/$NEXTYEAR/$NEXTMONTH");
            echo form_submit('prev','next month');
            echo form_close();
            ?>
        </td>
        <td>
            <?php
            if ($rights) {
                echo form_open('/queue/newQueueItem');
                echo form_submit('ok', 'Create Queue');
                echo form_close();
            }
            ?>
        </td>
    </tr>
</table>
<table class="lmframework">
    <thead>
    <th>
        Task
    </th>
    <th><!--image column--></th>
    <th>
        Type
    </th>
    <th>
        Done
    </th>
    <th>
        Quantity
    </th>
    <th>
        Progress
    </th>
    <th>
        Success
    </th>
</thead>
<tbody>
    <?php foreach ($queueItems as $queueItem): ?>
        <tr>
            <td>
                <?php
                if ($rights)
                    editqueuehrefedit($queueItem->queueId);
                echo($queueItem->activityName);
                echo("&nbsp;<img src=\"/ccp_icons/38_16_208.png\" style=\"vertical-align: middle;\" />");
                if ($rights)
                    echo('</a>');
                ?>
            </td>
            <td style="padding: 0px; width: 32px;">
                <?php
                itemhrefedit($queueItem->typeID);
                echo("<img src=\"/ccp_img/${$queueItem->typeID}_32.png\" title=\"${$queueItem->typeName}\" />");
                echo('</a>');
                ?>
            </td>
            <td>
                <?php
                itemhrefedit($queueItem->typeID);
                echo($queueItem->typeName);
                echo("&nbsp;<img src=\"/ccp_icons/38_16_208.png\" style=\"vertical-align: middle;\" />");
                echo('</a>');
                ?>
            </td>
            <td>
                <?php echo $queueItem->runsDone; ?>
            </td>
            <td>
                <?php echo $queueItem->runs; ?>
            </td>
            <td>
                <?php
                if ($queueItem->runs > 0) {
                    $percent1 = round(100 * $queueItem->runsDone / $queueItem->runs);
                    $percent2 = round(100 * ($queueItem->runsDone - $queueItem->runsCompleted) / $queueItem->runs);
                } else {
                    $percent1 = 0;
                    $percent2 = 0;
                }
                percentbar2($percent1, $percent2, "Done ${$queueItem->runsDone} of ${$queueItem->runs}");
                ?>
            </td><td>
                <?php
                if (empty($queueItem->jobsCompleted))
                    $queueItem->jobsCompleted = 0;
                if (empty($queueItem->jobsSuccess))
                    $queueItem->jobsSuccess = 0;
                if (($queueItem->activityID == 7) || ($queueItem->activityID == 8)) {
                    if ($queueItem->jobsCompleted > 0)
                        $realperc = round(100 * $queueItem->jobsSuccess / $queueItem->jobsCompleted);
                    else
                        $realperc = 0;
                    percentbar($realperc, "${$queueItem->jobsSuccess} successful in ${$queueItem->jobsCompleted} attempts");
                }
                ?>
            </td>
        </tr>
    <?php endforeach ?>
</tbody>
</table>
