<section>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card-body">
                <h5 class="card-title text-center mb-3"><?= $data['FormDesign']['Card']['Title'];?></h5>
                <div class="text-center mb-3"><i class="<?= $data['FormDesign']['Card']['Icon'];?>"></i></div>
                <p class="card-text"><?= $data['FormDesign']['Card']['Description'];?></p>
            </div>
        </div>

        <div class="col-md-8 mb-3">
            <form action="<?= $data['FormDesign']['Tabs']['Items'][1]['Link']; ?>" method="post">
                <div class="row">

                    <!-- Cadastro -->
                    <div class="col">
                        <div class="card-body">

                            <!-- General -->
                            <div class="form-floating mb-3">
                                <label for="CasAppEnv"><?= $data['FormDesign']['Fields']['CasAppEnv']['LongLabel']; ?></label>
                                <select class="form-control" id="CasAppEnv" name="CasAppEnv" type="text" <?= ($data['FormDesign']['FormDisable'] ? 'disabled' : ''); ?>>
                                    <option value='LOCAL' <?= ($data['FormData']['CasAppEnv'] == 'LOCAL' ? 'selected' : '') ?>>LOCAL</option>
                                    <option value='DEVELOPMENT' <?= ($data['FormData']['CasAppEnv'] == 'DEVELOPMENT' ? 'selected' : '') ?>>DEVELOPMENT</option>
                                    <option value='STAGING' <?= ($data['FormData']['CasAppEnv'] == 'STAGING' ? 'selected' : '') ?>>STAGING</option>
                                    <option value='PRODUCTION' <?= ($data['FormData']['CasAppEnv'] == 'PRODUCTION' ? 'selected' : '') ?>>PRODUCTION</option>
                                </select>
                                <small id="CasAppEnvHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppEnv']['TextHelp']; ?></small>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppName"><?= $data['FormDesign']['Fields']['CasAppName']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppName" name="CasAppName" type="text" placeholder="<?= $data['FormDesign']['Fields']['CasAppName']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppName']; ?>" autofocus required <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppNameHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppName']['TextHelp']; ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppUrl"><?= $data['FormDesign']['Fields']['CasAppUrl']['LongLabel']; ?></label>
                                        <textarea class="form-control" id="CasAppUrl" name="CasAppUrl" rows="1" placeholder="<?= $data['FormDesign']['Fields']['CasAppUrl']['TextPlaceholder']; ?>" <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> required><?= $data['FormData']['CasAppUrl']; ?></textarea>
                                        <small id="CasAppUrlHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppUrl']['TextHelp']; ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppKey"><?= $data['FormDesign']['Fields']['CasAppKey']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppKey" name="CasAppKey" type="text" placeholder="<?= $data['FormDesign']['Fields']['CasAppKey']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppKey']; ?>" required <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppKeyHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppKey']['TextHelp']; ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppToken"><?= $data['FormDesign']['Fields']['CasAppToken']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppToken" name="CasAppToken" type="text" placeholder="<?= $data['FormDesign']['Fields']['CasAppToken']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppToken']; ?>" required <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppTokenHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppToken']['TextHelp']; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Form Button Controls -->
                <?php
                include_once(App\Core\Config::$DIR_BASE . '/App/Views/SBAdmin/Partials/FormButtonControls.php');
                ?>
            </form>
        </div>
    </div>

</section>