<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <META http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php">
        <title><?php echo("$LM_APP_NAME $lmver"); ?></title>
        <link type="text/css" href="<?php echo $stylesheet ?>" rel="stylesheet">
        <!--<link rel="stylesheet" href="jquery-ui/css/ui-darkness/jquery-ui-1.10.3.custom.min.css" />-->
        <link rel="icon" href="/favicon.ico" type="image/ico">
        <script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="/js/Chart.min.js"></script>
        <script type="text/javascript" src="/js/ajax.js"></script>
        <script type="text/javascript" src="/js/skrypty.js"></script>
        <script type="text/javascript">
            function logoff() {
                logout = window.open("/main/logoff.html", "logoff_window", "toolbar=0,location=0,scrollbars=0,resizable=0,height=64,width=64,top=100,left=100");
                logout.close();
            }</script>
    </head>
    <body text="#000000" bgcolor="#FFFFFF">
        <center>
            <table class="tab-container">
                <tr><td width="100%" class="tab-horizbar">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr><td width="50%" align="left"><div class="top">Logged in as:<b> <?php
                                            echo $username
                                            ?></b><br></div></td>
                                <td width="50%"><div class="top2"><a href="?id=5"><img src="/img/settings.gif" alt="Settings" style="vertical-align: middle;"></a><a href="/main/logout.html"><img src="/img/log.gif" alt="Log off" style="vertical-align: middle;"> <b>Log off</b></a>
                                        <br></div></div></td></tr>
                        </table>
                    </td></tr>
                <tr><td width="100%" class="tab-logo">
                        <img src="/img/LMeve.png" alt="Logo">
                        <?php
//                        //draw messages notify
//                        include("msgchk.php");
//                        include("custom_notifications.php");
//                        if ($LM_READONLY == 1)
//                            echo($LANG['READONLY']);
                        ?>
                    </td></tr>
                <tr><td width="100%" style="padding: 0;">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>

                                <?php
                                echo $menu;
                                ?>
                            </tr>
                        </table>
                    </td></tr>
                <tr><td width="100%" class="tab-horizbar">
                        <br>
                    </td></tr>
                <tr><td width="100%" style="padding: 0;">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="tab-links" style="width: 20%; vertical-align: top; padding: 5px;">
                                    <?php
                                    echo $sidebar;
                                    if (has_permission($permissions, "Administrator")) {
                                        ?>
                                        <div style="text-align: center;"><hr><form method="get" action="">
                                                <input type="hidden" name="id" value="5">
                                                <input type="hidden" name="id2" value="6">
                                                <input type="hidden" name="nr" value="new">
                                                <input type="submit" value="Edit">
                                            </form></div>
                                    <?php } ?>
                                </td>
                                <td width="80%" class="tab-main" id="tab-main" valign="top">
                                    <?php echo $body ?>
                                </td>
                            </tr>
                        </table>

                    </td></tr>
                <tr><td width="100%" class="tab-horizbar">
                        <a href="/about.html">About</a><br>
                    </td></tr>
            </table>
            <?php
            include("copyright.php");
            ?>
            <script type="text/javascript" src="/js/resizer.js"></script>
        </center>
    </body>
</html>
