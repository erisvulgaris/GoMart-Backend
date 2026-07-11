                                            <div class="tab-pane fade <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'delivery-boy' ? 'active show' : '' ?>" id="delivery-boy" role="tabpanel" aria-labelledby="delivery-boy-tab">
                                                <h5>Delivery Boy Setting</h5>
                                                <form id="deliveryBoyForm">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="delivery_boy_show_earning_in_app">Show Earning In App <span class="text-danger text-xs">*(With this feature Deliverymen can see their earnings on a specific order while accepting it.)</span></label>
                                                                <input type="checkbox" name="delivery_boy_show_earning_in_app" id="delivery_boy_show_earning_in_app" <?= isset($settings['delivery_boy_show_earning_in_app']) && $settings['delivery_boy_show_earning_in_app'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="delivery_boy_bonus_setting">Bonus Settings <span class="text-danger text-xs">*</span></label>
                                                                <br>
                                                                <input type="checkbox" name="delivery_boy_bonus_setting" id="delivery_boy_bonus_setting" <?= isset($settings['delivery_boy_bonus_setting']) && $settings['delivery_boy_bonus_setting'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="delivery_boy_cash_in_hand">Delivery Boy Cash In Hand <span class="text-danger text-xs">*</span></label>
                                                                <br>
                                                                <input type="checkbox" name="delivery_boy_cash_in_hand" id="delivery_boy_cash_in_hand" <?= isset($settings['delivery_boy_cash_in_hand']) && $settings['delivery_boy_cash_in_hand'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label for="delivery_boy_maximum_cash_in_hand">Delivery Man Maximum Cash in Hand ($)<span class="text-danger text-xs">*</span></label>
                                                                <input type="number" value="<?= isset($settings['delivery_boy_maximum_cash_in_hand']) ? esc($settings['delivery_boy_maximum_cash_in_hand']) : '' ?>" id="delivery_boy_maximum_cash_in_hand" name="delivery_boy_maximum_cash_in_hand" required="" class="form-control ">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>

                                                </form>
                                            </div>