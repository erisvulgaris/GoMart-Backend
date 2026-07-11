                                            <div class="tab-pane fade <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'seller' ? 'active show' : '' ?>" id="seller" role="tabpanel" aria-labelledby="seller-tab">
                                                <h5>Seller Setting</h5>
                                                <form id="sellerSettingForm">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="seller_can_cancel_order">Can a Store Cancel Order <span class="text-danger text-xs">*</span></label>
                                                                <br>
                                                                <input type="checkbox" name="seller_can_cancel_order" id="seller_can_cancel_order" <?= isset($settings['seller_can_cancel_order']) && $settings['seller_can_cancel_order'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="seller_can_complete_order">Can a Store Complete Order <span class="text-danger text-xs">*</span></label>
                                                                <br>
                                                                <input type="checkbox" name="seller_can_complete_order" id="seller_can_complete_order" <?= isset($settings['seller_can_complete_order']) && $settings['seller_can_complete_order'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="seller_only_one_seller_cart">One Seller Cart<span class="text-danger text-xs">*</span></label>
                                                                <br>
                                                                <input type="checkbox" name="seller_only_one_seller_cart" id="seller_only_one_seller_cart" <?= isset($settings['seller_only_one_seller_cart']) && $settings['seller_only_one_seller_cart'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="seller_approval_product">Need Approval for Publishing Products <span class="text-danger text-xs">*</span></label>
                                                                <br>
                                                                <input type="checkbox" name="seller_approval_product" id="seller_approval_product" <?= isset($settings['seller_approval_product']) && $settings['seller_approval_product'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>