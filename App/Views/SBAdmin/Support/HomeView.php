<!DOCTYPE html>
<html lang="pt-BR">

<?php
include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/Head.php');
?>

<body class="sb-nav-fixed">
    <?php
    include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/TopMenu.php');
    ?>
    <div id="layoutSidenav">
        <?php
        include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/SideMenu.php');
        ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <?php
                    include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/Breadcrumb.php');
                    ?>

                    <?php
                    $panel_menu_support = (array) json_decode($data['FormDesign']['PanelMenuSupport']);
                    if ($panel_menu_support['PanelMenuSupport']) {
                        echo '<div class="row">';
                        foreach ($panel_menu_support['PanelMenuSupport'] as $item) {
                            if ($item->Visible) {
                                echo '    <div class="col-sm-3 mb-3">';
                                echo '        <div class="card h-100">';
                                echo '            <div class="card-body">';
                                echo '                <div class="text-center mb-3"><i class="' . $item->Icon . '"></i></div>';
                                echo '                <h5 class="card-title">' . $item->Title . '</h5>';
                                echo '                <p class="card-text">' . $item->Description . '</p>';
                                foreach ($item->Action as $btnItem) {
                                    if ($btnItem->Visible) {
                                        if ($btnItem->Enabled) {
                                            echo '                <a href="' . $btnItem->Link . '" class="' . $btnItem->Class . '">' . ((empty($btnItem->Icon)) ? '' : '<i class="' . $btnItem->Icon . '"></i> ') . $btnItem->Label . '</a>';
                                        } else {
                                            echo '                <button type="button" disabled class="' . $btnItem->Class . '">' . ((empty($btnItem->Icon)) ? '' : '<i class="' . $btnItem->Icon . '"></i> ') . $btnItem->Label . '</button>';
                                        }
                                    }
                                }
                                echo '            </div>';
                                echo '        </div>';
                                echo '    </div>';
                            }
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </main>

            <?php
            include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/Footer.php');
            ?>
        </div>
    </div>

    <?php
    include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/BodyScripts.php');
    ?>
</body>

</html>