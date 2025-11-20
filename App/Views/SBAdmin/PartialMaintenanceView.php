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
                <div class="container-fluid px-4">
                    <?php
                    // include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/Breadcrumb.php');
                    ?>

                    <div class="row justify-content-center mt-4 mt-md-5">
                        <div class="col-lg-8 col-md-10">
                            <div class="card shadow-lg border-0 rounded-lg">
                                <div class="card-header bg-warning text-dark"> <!-- Cor diferente para manutenção -->
                                    <h3 class="text-center font-weight-light my-3">
                                        <i class="fas fa-cogs me-2"></i>&nbsp;Melhorias em Andamento
                                    </h3>
                                </div>
                                <div class="card-body text-center p-4">
                                    <div class="my-3">
                                        <i class="fas fa-tools fa-5x text-info mb-3"></i>
                                    </div>
                                    <p class="lead">
                                        Estamos trabalhando para tornar nosso sistema ainda melhor para você!
                                    </p>
                                    <p class="text-muted">
                                        Alguns recursos podem estar temporariamente indisponíveis ou apresentar lentidão enquanto realizamos manutenções e atualizações. As funcionalidades essenciais e páginas informativas devem continuar acessíveis.
                                    </p>
                                    <p class="mt-4">
                                        Agradecemos imensamente sua paciência e compreensão. Por favor, tente acessar os recursos desejados novamente em alguns instantes.
                                    </p>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <a href="/" class="btn btn-primary">
                                        <i class="fas fa-home me-1"></i> Voltar para a Página Inicial
                                    </a>
                                    <button onclick="window.location.reload();" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-sync-alt me-1"></i> Tentar Novamente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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