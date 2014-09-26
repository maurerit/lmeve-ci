<?php include_once('database_main.php'); ?>

<div class="tytul">
    Item Database
</div>
<?php echo("<em>Static Data schema: $LM_EVEDB</em><br />"); ?>
<table cellpadding="0" cellspacing="2">
    <tr>

        <td>
            <form method="get" action="">
                <input type="text" name="query" value="<?php echo(stripslashes($query)); ?>" size="20">
                <input type="hidden" name="id" value="10">
                <input type="hidden" name="id2" value="2">
                <input type="submit" value="Search">
            </form>
        </td>
    </tr>
</table>
<?php

function hrefedit_item($nr) {
    echo("<a href=\"/database/item/$nr\">");
}

function hrefedit_group($nr) {
    echo("<a href=\"/database/items/$nr\">");
}

//if (!empty($marketGroupID)) {
//    $node = getMarketNode($marketGroupID);
//    $parentGroupID = $node['parentGroupID'];
//    do {
//        $breadcrumbs = "&gt; <a href=\"?id=10&id2=0&marketGroupID=${node['marketGroupID']}\">${node['marketGroupName']}</a> $breadcrumbs";
//        if (!empty($node['parentGroupID'])) {
//            $node = getMarketNode($node['parentGroupID']);
//        } else {
//            break;
//        }
//    } while (TRUE);
//    echo("<a href=\"?id=10&id2=0\"> Start </a> $breadcrumbs");
//}
?>
<table cellspacing="2" cellpadding="0">
    <tr><td class="tab-header">
            <b>Icon</b>
        </td><td class="tab-header">
            <b>Name</b>
        </td>
    </tr>
    <?php if (!empty($marketGroupID)): ?>
        <tr><td class="tab">
                <b><a href="/database/items/<?php echo $parentGroupID; ?>"><img src="ccp_icons/23_64_1.png" style="width: 32px; height: 32px;" title="Parent Group" /></a></b>
            </td><td class="tab">
                <b><a href="/database/items/<?php echo $parentGroupID; ?>">..</a></b>
            </td>
        </tr>
        <?php
    endif;
    if (sizeof($groups) > 0):
        foreach ($groups as $row):
            ?>
            <tr>
                <td class="tab" style="padding: 0px; width: 32px;">
                    <?php hrefedit_group($row->marketGroupID); ?>
                    <img src="ccp_icons/22_32_29.png" title=" <?php echo $row->marketGroupName ?>" />
                    <?php echo('</a>'); ?>
                </td>
                <td class="tab">
                    <?php
                    hrefedit_group($row->marketGroupID);
                    echo($row->marketGroupName);
                    ?>
                    </a>
                </td>
            </tr>
            <?php
        endforeach;
    endif;
    if (sizeof($items) > 0):
        foreach ($items as $row):
            ?>
            <tr>
                <td class="tab" style="padding: 0px; width: 32px;">
                    <?php hrefedit_item($row->typeID); ?>
                    <img src="ccp_img/<?php echo $row->typeID ?>_32.png" title="<?php echo $row->typeName ?>" />
                    </a>
                </td>
                <td class="tab">
                    <?php
                    hrefedit_item($row->typeID);
                    echo($row->typeName);
                    echo('</a>');
                    ?>
                </td>
            </tr>
            <?php
        endforeach;
    endif;
    ?>
</table>