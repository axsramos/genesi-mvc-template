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
            <form action="<?= $data['FormDesign']['Tabs']['Items'][3]['Link']; ?>" method="post">
                <div class="row">

                    <!-- Cadastro -->
                    <div class="col">
                        <div class="card-body">

                            <!-- General -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppSupFirstName"><?= $data['FormDesign']['Fields']['CasAppSupFirstName']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppSupFirstName" name="CasAppSupFirstName" type="text" placeholder="<?= $data['FormDesign']['Fields']['CasAppSupFirstName']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppSupFirstName']; ?>" autofocus required <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppSupFirstNameHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppSupFirstName']['TextHelp']; ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppSupLastName"><?= $data['FormDesign']['Fields']['CasAppSupLastName']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppSupLastName" name="CasAppSupLastName" type="text" placeholder="<?= $data['FormDesign']['Fields']['CasAppSupLastName']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppSupLastName']; ?>" autofocus required <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppSupLastNameHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppSupLastName']['TextHelp']; ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppSupAccount"><?= $data['FormDesign']['Fields']['CasAppSupAccount']['LongLabel']; ?></label>
                                        <textarea class="form-control" id="CasAppSupAccount" name="CasAppSupAccount" rows="1" placeholder="<?= $data['FormDesign']['Fields']['CasAppSupAccount']['TextPlaceholder']; ?>" <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?>><?= $data['FormData']['CasAppSupAccount']; ?></textarea>
                                        <small id="CasAppSupAccountHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppSupAccount']['TextHelp']; ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <label for="CasAppSupPassword"><?= $data['FormDesign']['Fields']['CasAppSupPassword']['LongLabel']; ?></label>
                                        <input class="form-control" id="CasAppSupPassword" name="CasAppSupPassword" type="password" placeholder="<?= $data['FormDesign']['Fields']['CasAppSupPassword']['TextPlaceholder']; ?>" value="<?= $data['FormData']['CasAppSupPassword']; ?>" <?= ($data['FormDesign']['FormDisable'] ? 'readonly' : ''); ?> />
                                        <small id="CasAppSupPasswordHelp" class="form-text text-muted"><?= $data['FormDesign']['Fields']['CasAppSupPassword']['TextHelp']; ?></small>
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