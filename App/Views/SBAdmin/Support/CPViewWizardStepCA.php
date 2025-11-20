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
            <form action="<?= $data['FormDesign']['Tabs']['Items'][2]['Link']; ?>" method="post">
                <div class="row">

                    <!-- Cadastro -->
                    <div class="col">
                        <div class="card-body">

                            <!-- General -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppAdmFirstName"><?= $data['FormDesign']['Fields']['CasAppAdmFirstName']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppAdmFirstName" name="CasAppAdmFirstName" type="text" placeholder="<?= $data['FormDesign']['Fields']['CasAppAdmFirstName']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppAdmFirstName']; ?>" autofocus required <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppAdmFirstNameHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppAdmFirstName']['TextHelp']; ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppAdmLastName"><?= $data['FormDesign']['Fields']['CasAppAdmLastName']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppAdmLastName" name="CasAppAdmLastName" type="text" placeholder="<?= $data['FormDesign']['Fields']['CasAppAdmLastName']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppAdmLastName']; ?>" required <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppAdmLastNameHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppAdmLastName']['TextHelp']; ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppAdmAccount"><?= $data['FormDesign']['Fields']['CasAppAdmAccount']['LongLabel']; ?></label>
                                        <textarea class="form-control" id="CasAppAdmAccount" name="CasAppAdmAccount" rows="1" placeholder="<?= $data['FormDesign']['Fields']['CasAppAdmAccount']['TextPlaceholder']; ?>" <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> required ><?= $data['FormData']['CasAppAdmAccount']; ?></textarea>
                                        <small id="CasAppAdmAccountHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppAdmAccount']['TextHelp']; ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppAdmPassword"><?= $data['FormDesign']['Fields']['CasAppAdmPassword']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppAdmPassword" name="CasAppAdmPassword" type="password" placeholder="<?= $data['FormDesign']['Fields']['CasAppAdmPassword']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppAdmPassword']; ?>" required <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppAdmPasswordHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppAdmPassword']['TextHelp']; ?></small>
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