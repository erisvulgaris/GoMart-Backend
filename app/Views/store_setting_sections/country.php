                                            <div class="tab-pane fade <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'country' ? 'active show' : '' ?>" id="country" role="tabpanel" aria-labelledby="country-tab">
                                                <h5>Country Setting</h5>
                                                <form id="countryForm">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="country">Country <span class="text-danger text-xs">*</span></label>
                                                                <select id="country" name="country" required="" class="form-control ">
                                                                    <?php
                                                                    foreach ($country as $key => $val) {
                                                                    ?>
                                                                        <option value="<?php echo $val['id'] ?>" <?php if ($val['is_active'] == 1) {
                                                                                                                        echo 'selected';
                                                                                                                    } ?>><?php echo $val['country_name'] . " (Dial Code: " . $val['country_code'] . ", Currency: " . $val['currency'] . ")" ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label for="timezone">Timezone <span class="text-danger text-xs">*</span></label>
                                                                <select id="timezone" name="timezone" required="" class="form-control ">
                                                                    <?php
                                                                    foreach ($timezone as $key => $val) {
                                                                    ?>
                                                                        <option value="<?php echo $val['id'] ?>" <?php if ($val['is_active'] == 1) {
                                                                                                                        echo 'selected';
                                                                                                                    } ?>><?php echo $val['timezone'] . " - GMT: " . $val['gmt'] ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>

                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>