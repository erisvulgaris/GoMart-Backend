                                            <div class="tab-pane fade <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'order' ? 'active show' : '' ?>" id="order" role="tabpanel" aria-labelledby="order-tab">
                                                <h5>Order Setting </h5>
                                                <form id="orderForm">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="order_delivery_verification">Order Delivery Verification <span class="text-danger text-xs">*(It will show 4 digit pin in user app to deliver order)</span></label>
                                                                <input type="checkbox" <?= isset($settings['order_delivery_verification']) && $settings['order_delivery_verification'] == '1' ? 'checked' : ''; ?> name="order_delivery_verification" id="order_delivery_verification"
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="live_tracking">Order Live Tracking</label>
                                                                <br>
                                                                <input type="checkbox" <?= isset($settings['live_tracking']) && $settings['live_tracking'] == '1' ? 'checked' : ''; ?> name="live_tracking" id="live_tracking"
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="minimum_order_amount">Minimum Order Amount <span class="text-danger text-xs">*</span></label>
                                                                <input type="number" value="<?= isset($settings['minimum_order_amount']) ? esc($settings['minimum_order_amount']) : '' ?>" id="minimum_order_amount" name="minimum_order_amount" required="" class="form-control ">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5 class="mt-4">Additional Charge Control</h5>
                                                    <div class="row  ">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="additional_charge_status">Additional Charge</label>
                                                                <br>
                                                                <input type="checkbox" <?= isset($settings['additional_charge_status']) && $settings['additional_charge_status'] == '1' ? 'checked' : ''; ?> name="additional_charge_status" id="additional_charge_status"
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="additional_charge_name">Additional Charge Name <span class="text-danger text-xs">*</span></label>
                                                                <input type="text" value="<?= isset($settings['additional_charge_name']) ? esc($settings['additional_charge_name']) : '' ?>" id="additional_charge_name" name="additional_charge_name" required="" class="form-control ">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="additional_charge">Additional Charge Amount <span class="text-danger text-xs">*</span></label>
                                                                <input type="number" value="<?= isset($settings['additional_charge']) ? esc($settings['additional_charge']) : '' ?>" id="additional_charge" name="additional_charge" required="" class="form-control ">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5 class="mt-4">Delivery Charge Tax</h5>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="delivery_charge_tax_status">Enable Tax on Delivery Charge</label>
                                                                <br>
                                                                <input type="checkbox" <?= isset($settings['delivery_charge_tax_status']) && $settings['delivery_charge_tax_status'] == '1' ? 'checked' : ''; ?> name="delivery_charge_tax_status" id="delivery_charge_tax_status"
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                                <small class="form-text text-muted">Tax is always inclusive in delivery charge.</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>Select Taxes Applied on Delivery Charge</label>
                                                                <div class="row">
                                                                    <?php if (!empty($allTaxes)): ?>
                                                                        <?php foreach ($allTaxes as $tax): ?>
                                                                            <div class="col-md-4">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox" name="delivery_charge_tax_ids[]"
                                                                                        id="dct_<?= $tax['id']; ?>" value="<?= $tax['id']; ?>"
                                                                                        <?= in_array($tax['id'], $selectedDeliveryTaxIds) ? 'checked' : ''; ?>>
                                                                                    <label class="form-check-label" for="dct_<?= $tax['id']; ?>">
                                                                                        <?= esc($tax['tax']); ?> (<?= $tax['percentage']; ?>%)
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <div class="col-12"><small class="text-muted">No active taxes found. Add taxes from the Tax section first.</small></div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5 class="mt-4">Additional Charge Tax</h5>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="additional_charge_tax_status">Enable Tax on Additional Charge</label>
                                                                <br>
                                                                <input type="checkbox" <?= isset($settings['additional_charge_tax_status']) && $settings['additional_charge_tax_status'] == '1' ? 'checked' : ''; ?> name="additional_charge_tax_status" id="additional_charge_tax_status"
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                                <small class="form-text text-muted">Tax is always inclusive in additional charge.</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>Select Taxes Applied on Additional Charge</label>
                                                                <div class="row">
                                                                    <?php if (!empty($allTaxes)): ?>
                                                                        <?php foreach ($allTaxes as $tax): ?>
                                                                            <div class="col-md-4">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox" name="additional_charge_tax_ids[]"
                                                                                        id="act_<?= $tax['id']; ?>" value="<?= $tax['id']; ?>"
                                                                                        <?= in_array($tax['id'], $selectedAdditionalTaxIds) ? 'checked' : ''; ?>>
                                                                                    <label class="form-check-label" for="act_<?= $tax['id']; ?>">
                                                                                        <?= esc($tax['tax']); ?> (<?= $tax['percentage']; ?>%)
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <div class="col-12"><small class="text-muted">No active taxes found. Add taxes from the Tax section first.</small></div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h5 class="mt-4">Delivery Method Control</h5>
                                                    <div class="row">
                                                        <input type="hidden" id="home_delivery_status_id" name="home_delivery_status_id" value="<?= esc(json_decode($settings['home_delivery_status'])->id); ?>">
                                                        <input type="hidden" id="home_delivery_status_image" name="home_delivery_status_image" value="<?= esc(json_decode($settings['home_delivery_status'])->image); ?>">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="home_delivery_status_title">Home Delivery Title</label>
                                                                <br>
                                                                <input type="text" class="form-control" placeholder="Title" name="home_delivery_status_title" id="home_delivery_status_title" value="<?= esc(json_decode($settings['home_delivery_status'])->title); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="home_delivery_status_description">Home Delivery Description</label>
                                                                <br>
                                                                <input type="text" class="form-control" placeholder="Description" name="home_delivery_status_description" id="home_delivery_status_description" value="<?= esc(json_decode($settings['home_delivery_status'])->description); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="home_delivery_status_status">Home Delivery Status</label>
                                                                <br>
                                                                <input type="checkbox" name="home_delivery_status_status" id="home_delivery_status_status" <?= isset(json_decode($settings['home_delivery_status'])->status) && json_decode($settings['home_delivery_status'])->status == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <input type="hidden" id="schedule_delivery_status_id" name="schedule_delivery_status_id" value="<?= esc(json_decode($settings['schedule_delivery_status'])->id); ?>">
                                                        <input type="hidden" id="schedule_delivery_status_image" name="schedule_delivery_status_image" value="<?= esc(json_decode($settings['schedule_delivery_status'])->image); ?>">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="schedule_delivery_status_title">Schedule Delivery Title</label>
                                                                <br>
                                                                <input type="text" class="form-control" placeholder="Title" name="schedule_delivery_status_title" id="schedule_delivery_status_title" value="<?= esc(json_decode($settings['schedule_delivery_status'])->title); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="schedule_delivery_status_description">Schedule Delivery Description</label>
                                                                <br>
                                                                <input type="text" class="form-control" placeholder="Description" name="schedule_delivery_status_description" id="schedule_delivery_status_description" value="<?= esc(json_decode($settings['schedule_delivery_status'])->description); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="schedule_delivery_status_status">Schedule Delivery Status</label>
                                                                <br>
                                                                <input type="checkbox" name="schedule_delivery_status_status" id="schedule_delivery_status_status" <?= isset(json_decode($settings['schedule_delivery_status'])->status) && json_decode($settings['schedule_delivery_status'])->status == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <input type="hidden" id="takeaway_status_id" name="takeaway_status_id" value="<?= esc(json_decode($settings['takeaway_status'])->id); ?>">
                                                        <input type="hidden" id="takeaway_status_image" name="takeaway_status_image" value="<?= esc(json_decode($settings['takeaway_status'])->image); ?>">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="takeaway_status_title">Self Pickup Title</label>
                                                                <br>
                                                                <input type="text" class="form-control" placeholder="Title" name="takeaway_status_title" id="takeaway_status_title" value="<?= esc(json_decode($settings['takeaway_status'])->title); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="takeaway_status_description">Self Pickup Description</label>
                                                                <br>
                                                                <input type="text" class="form-control" placeholder="Description" name="takeaway_status_description" id="takeaway_status_description" value="<?= esc(json_decode($settings['takeaway_status'])->description); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="takeaway_status_status">Self Pickup Status</label>
                                                                <br>
                                                                <input type="checkbox" name="takeaway_status_status" id="takeaway_status_status" <?= isset(json_decode($settings['takeaway_status'])->status) && json_decode($settings['takeaway_status'])->status == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>
