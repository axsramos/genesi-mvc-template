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
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4"><a href="/Home" class="text-dark"><?=App\Core\Config::$APP_NAME;?></a></h3>
                                </div>
                                <div class="card-body">
                                    <h4 class="text-center font-weight-light mb-4">Cadastrar-se</h4>
                                    <?php
                                    if (!empty($data['FormDesign']['Message']['Description'])) {
                                        echo '<div class="alert alert-warning" role="alert">';
                                        echo $data['FormDesign']['Message']['Description'];
                                        echo '</div>';
                                    }
                                    ?>
                                    <form action="Register" method="post">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <label for="inputFirstName">Primeiro Nome</label>
                                                    <input class="form-control" id="inputFirstName" name="inputFirstName" type="text" placeholder="Digite seu primeiro nome" required/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <label for="inputLastName">Sobrenome</label>
                                                    <input class="form-control" id="inputLastName" name="inputLastName" type="text" placeholder="Digite seu último nome" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <label for="inputEmail">Endereço de E-Mail</label>
                                            <input class="form-control" id="inputEmail" name="inputEmail" type="email" placeholder="name@example.com" required/>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <label for="inputPassword">Crie uma Senha</label>
                                                    <input class="form-control" id="inputPassword" name="inputPassword" type="password" placeholder="Crie uma senha" required/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <label for="inputPasswordConfirm">Confirme a Senha</label>
                                                    <input class="form-control" id="inputPasswordConfirm" name="inputPasswordConfirm" type="password" placeholder="Confirme a senha" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid"><button class="btn btn-primary btn-block" id="btnConfirm" name="btnConfirm">Criar Conta</button></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">Já tem uma conta? <a href="/Auth/Login">Entrar</a></div>
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