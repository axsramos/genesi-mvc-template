<section>
    <form action="<?= $data['FormDesign']['Tabs']['Items'][0]['Link']; ?>" method="post">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="container mb-3">
                    <div class="row justify-content-center">
                        <h2 class="display-4 mt-3 mb-3">Em 3 passos</h2>
                        <p class="lead mb-4">Informe os dados mais essenciais e tudo estará pronto.</p>
                    </div>
                </div>

                <div class="container mb-3">
                    <div class="row">
                        <div class="col mb-3 text-center"><i class="<?= $data['FormDesign']['Card']['VA']['Icon'];?>"></i></div>
                        <div class="col mb-3 text-center"><i class="<?= $data['FormDesign']['Card']['CA']['Icon'];?>"></i></div>
                        <div class="col mb-3 text-center"><i class="<?= $data['FormDesign']['Card']['CS']['Icon'];?>"></i></div>
                    </div>
                    <div class="row">
                        <div class="col mb-3 text-center">
                            <?= $data['FormDesign']['Card']['VA']['Title'];?>
                        </div>
                        <div class="col mb-3 text-center">
                            <?= $data['FormDesign']['Card']['CA']['Title'];?>
                        </div>
                        <div class="col mb-3 text-center">
                            <?= $data['FormDesign']['Card']['CS']['Title'];?>
                        </div>
                    </div>
                </div>

                <!-- Form Button Controls -->
                <?php
                include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/FormButtonControls.php');
                ?>
            </div>
        </div>
    </form>
</section>