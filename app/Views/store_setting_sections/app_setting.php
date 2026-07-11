                                            <div class="tab-pane fade <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'app-setting' ? 'active show' : '' ?>" id="app-setting" role="tabpanel" aria-labelledby="app-setting-tab">
                                                <form id="appSettingForm">
                                                    <h5>App Setting</h5>
                                                    <h5 class=" mt-4"> Customer App Control</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">

                                                            <div class="form-group">
                                                                <label for="app_minimum_version_android" class="form-label">
                                                                    Minimum User App Version (Android)

                                                                </label>
                                                                <input id="app_minimum_version_android" type="text" value="<?= isset($settings['app_minimum_version_android']) ? esc($settings['app_minimum_version_android']) : '' ?>" placeholder="App minimum version" class="form-control" name="app_minimum_version_android">
                                                            </div>
                                                            <div class="form-group ">
                                                                <label for="app_url_android" class="form-label">
                                                                    Download URL for User App (Android)

                                                                </label>
                                                                <input id="app_url_android" type="text" value="<?= isset($settings['app_url_android']) ? esc($settings['app_url_android']) : '' ?>" placeholder="App url" class="form-control" name="app_url_android">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">


                                                            <div class="form-group">
                                                                <label for="app_minimum_version_ios" class="form-label">Minimum User App Version (Ios)

                                                                </label>
                                                                <input id="app_minimum_version_ios" value="<?= isset($settings['app_minimum_version_ios']) ? esc($settings['app_minimum_version_ios']) : '' ?>" type="text" placeholder="App minimum version" class="form-control" name="app_minimum_version_ios">
                                                            </div>
                                                            <div class="form-group ">
                                                                <label for="app_url_ios" class="form-label">
                                                                    Download URL for User App (Ios)

                                                                </label>
                                                                <input id="app_url_ios" type="text" value="<?= isset($settings['app_url_ios']) ? esc($settings['app_url_ios']) : '' ?>" placeholder="App url" class="form-control" name="app_url_ios">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h5 class=" mt-4"> Delivery Boy App Control</h5>

                                                    <div class="row">
                                                        <div class="col-md-6">

                                                            <div class="form-group">
                                                                <label for="app_minimum_version_android_delivery_boy" class="form-label text-capitalize">Minimum Delivery Boy App Version (Android)

                                                                </label>
                                                                <input id="app_minimum_version_android_delivery_boy" type="text" value="<?= isset($settings['app_minimum_version_android_delivery_boy']) ? esc($settings['app_minimum_version_android_delivery_boy']) : '' ?>" placeholder="App minimum version" class="form-control " name="app_minimum_version_android_delivery_boy" min="0">
                                                            </div>
                                                            <div class="form-group ">
                                                                <label for="app_url_android_delivery_boy" class="form-label text-capitalize">
                                                                    Download URL for Delivery Boy App (Android)
                                                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Users will download the latest store app using this URL.">

                                                                    </span>
                                                                </label>
                                                                <input id="app_url_android_delivery_boy" type="text" value="<?= isset($settings['app_url_android_delivery_boy']) ? esc($settings['app_url_android_delivery_boy']) : '' ?>" placeholder="Download Url" class="form-control " name="app_url_android_delivery_boy">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">

                                                            <div class="form-group">
                                                                <label for="app_minimum_version_ios_delivery_boy" class="form-label text-capitalize">Minimum Delivery Boy App Version (Ios)

                                                                </label>
                                                                <input id="app_minimum_version_ios_delivery_boy" type="text" value="<?= isset($settings['app_minimum_version_ios_delivery_boy']) ? esc($settings['app_minimum_version_ios_delivery_boy']) : '' ?>" placeholder="App minimum version" class="form-control " name="app_minimum_version_ios_delivery_boy" min="0">
                                                            </div>
                                                            <div class="form-group ">
                                                                <label for="app_url_ios_delivery_boy" class="form-label text-capitalize">
                                                                    Download URL for Delivery Boy App (Ios)
                                                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Users will download the latest store app version using this URL.">

                                                                    </span>
                                                                </label>
                                                                <input id="app_url_ios_delivery_boy" type="text" value="<?= isset($settings['app_url_ios_delivery_boy']) ? esc($settings['app_url_ios_delivery_boy']) : '' ?>" placeholder="Download Url" class="form-control " name="app_url_ios_delivery_boy">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>