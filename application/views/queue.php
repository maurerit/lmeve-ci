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
        $NEXTMONTH = str_pad(2, 2, "0", STR_PAD_LEFT);
        $NEXTYEAR = $year;
        $PREVMONTH = 12;
        $PREVYEAR = $year - 1;
        break;
    case 12:
        $NEXTMONTH = str_pad(1, 2, "0", STR_PAD_LEFT);
        $NEXTYEAR = $year + 1;
        $PREVMONTH = 11;
        $PREVYEAR = $year;
        break;
    default:
        $NEXTMONTH = str_pad($month + 1, 2, "0", STR_PAD_LEFT);
        $NEXTYEAR = $year;
        $PREVMONTH = str_pad($month - 1, 2, "0", STR_PAD_LEFT);
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
            echo form_submit('prev', 'next month');
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
    <th>
        Kit
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
                ?>
                &nbsp;<img src="/ccp_icons/38_16_208.png" style="vertical-align: middle;" />
                <?php
                if ($rights)
                    echo('</a>');
                ?>
            </td>
            <td style="padding: 0px; width: 32px;">
                <?php
                itemhrefedit($queueItem->typeID);
                ?>
                <img src="/ccp_img/' <?php echo $queueItem->typeID ?> '_32.png" title="' <?php echo $queueItem->typeName ?> '" /></a>
            </td>
            <td>
                <?php
                itemhrefedit($queueItem->typeID);
                echo($queueItem->typeName);
                ?>
                &nbsp;<img src="/ccp_icons/38_16_208.png" style="vertical-align: middle;" /></a>

            </td>
            <td>
                <?php
                if (!$queueItem->runsDone) {
                    echo 0;
                } else {
                    echo $queueItem->runsDone;
                }
                ?>
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
            <td>
                <?php
                $remainingRuns = $queueItem->runs - $queueItem->runsDone;
                if ($remainingRuns < 0)
                    $remainingRuns = 0;
                ?>
                <span title="FULL kit for this task" onclick="getQueueKit('kit_row_<?php echo $queueItem->queueId ?>', 'kit_<?php echo $queueItem->queueId ?>',<?php echo $queueItem->queueId ?>,<?php echo $queueItem->runs ?>)">
                    <img src="ccp_icons/12_64_3.png" style="width: 24px; height: 24px;" /></span>
                <span title="REMAINING kit for this task" onclick="getQueueKit('kit_row_<?php echo $queueItem->queueId ?>', 'kit_<?php echo $queueItem->queueId ?>',<?php echo $queueItem->queueId ?>, <?php echo $remainingRuns ?>)">
                    <img src="ccp_icons/7_64_16.png" style="width: 24px; height: 24px;" /></span>
            </td>
        </tr>
        <tr id="kit_row_<?php echo $queueItem->queueId ?>" style="display: none">
            <td colspan="8">
                <div id="kit_<?php echo $queueItem->queueId ?>"><em>Loading kit data...</em></div>
            </td>
        </tr>
    <?php endforeach ?>
</tbody>
</table>
