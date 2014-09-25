<table border="0" cellpadding="0" cellspacing="2">
    <tr>
        <?php if (has_permissions($permissions, "Administrator,ViewDatabase")): ?>
            <td>
                <form method="get" action="/database/items.html">
                    <input type="submit" value="Item Database">
                </form>
            </td>
        <?php endif ?>
        <?php if (has_permissions($permissions, "Administrator,ViewOreValues")): ?>
            <td>
                <form method="get" action="/database/orechart.html">
                    <input type="submit" value="Ore Chart" />
                </form>
            </td>
        <?php endif ?>
        <?php if (has_permissions($permissions, "Administrator,ViewProfitCalc")): ?>
            <td>
                <form method="get" action="/database/profitexplorer.html">
                    <input type="submit" value="Profit Explorer" />
                </form>
            </td>
        <?php endif ?>
        <?php if (has_permissions($permissions, "Administrator,ViewProfitCalc")): ?>
            <td>
                <form method="get" action="/database/profitchart.html">
                    <input type="submit" value="Profit Chart" title="WARNING: it can take a very long time to load!"/>
                </form>
            </td>
        <?php endif ?>
    </tr>
</table>