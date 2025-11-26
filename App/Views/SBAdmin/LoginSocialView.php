<!DOCTYPE html>
<html lang="pt-BR">

<?php
include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/HeadLogin.php');
?>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5 mb-4">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4"><a href="/Home" class="text-dark"><?= App\Core\Config::$APP_NAME; ?></a></h3>
                                </div>
                                <div class="card-body">
                                    <h4 class="text-center font-weight-light mb-4">Login</h4>
                                    <?php
                                    echo '<img src="' . $data['FormData']['Avatar'] . '" class="rounded mx-auto d-block mb-3" alt="avatar">';
                                    if (empty($data['FormDesign']['Message']['Description'])) {
                                        echo '<h5 class="text-center card-title">' . $data['FormData']['LabelUser'] . '</h5>';
                                        echo '<p class="text-center card-text mb-4">' . $data['FormData']['LabelAccount'] . '</p>';
                                        echo '<a type="button" href="' . $data['RedirectLink'] . '" class="btn btn-primary btn-lg btn-block">Continuar</a>';
                                    } else {
                                        echo '<div class="alert alert-danger" role="alert">';
                                        echo $data['FormDesign']['Message']['Description'];
                                        echo '</div>';

                                        // echo '<img src="'. App\Core\Config::$URL_BASE . \App\Core\Config::getPreferences('avatar_user') .'" class="rounded mx-auto d-block mb-3" alt="avatar">';
                                        echo '<a type="button" href="' . $data['RedirectLink'] . '" class="btn btn-secondary btn-lg btn-block">Voltar</a>';
                                    }
                                    ?>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">Autentiação com <?= $data['FormData']['Provider'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <?
            include(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/Footer.php');
            ?>
        </div>
    </div>
    <?
    include(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/BodyScriptsLogin.php');
    ?>
</body>

</html>