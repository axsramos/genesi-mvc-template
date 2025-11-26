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
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4"><a href="/Home" class="text-dark"><?= App\Core\Config::$APP_NAME; ?></a></h3>
                                </div>
                                <div class="card-body">
                                    <h4 class="text-center font-weight-light mb-4">Login</h4>
                                    <?php
                                    if (!empty($data['FormDesign']['Message']['Description'])) {
                                        echo '<div class="alert alert-warning" role="alert">';
                                        echo $data['FormDesign']['Message']['Description'];
                                        echo '</div>';
                                    }
                                    ?>
                                    <form action="Login" method="post">
                                        <?php
                                        if (App\Core\Config::$AUTH_SIGN_GOOGLE) {
                                            echo '<!-- Google Button -->';
                                            echo '<div class="card card-body mb-3 text-center">';
                                            echo '    <div class="col-md-12">';
                                            echo '        <button class="btn btn-google ico-left" name="btnLoginWithGoogle">';
                                            echo '            <img src="' . App\Core\Config::$URL_BASE . '/SBAdmin/images/google.png" style="max-height:32px; max-width:32px; margin-right:10px;" alt="google-icon"> Entrar com o Google';
                                            echo '        </button>';
                                            echo '    </div>';
                                            echo '</div>';

                                            echo '<!-- Login Separator -->';
                                            echo '<div class="col-md-12 mb-3 text-center">';
                                            echo '    <div class="separator-line">Ou, entre com seu e-mail</div>';
                                            echo '</div>';
                                        }
                                        ?>
                                        <div class="form-floating mb-3">
                                            <label for="inputEmail">Endereço de E-Mail</label>
                                            <input class="form-control" id="inputEmail" name="inputEmail" type="email" placeholder="name@example.com" required />
                                        </div>
                                        <div class="form-floating mb-3">
                                            <label for="inputPassword">Senha</label>
                                            <input class="form-control" id="inputPassword" name="inputPassword" type="password" placeholder="Password" required />
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="/Auth/Recovery">Esqueceu a senha?</a>
                                            <button class="btn btn-primary" id="btnConfirm" name="btnConfirm">Entrar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">Não tem uma conta? <a href="/Auth/Register">Cadastre-se!</a></div>
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