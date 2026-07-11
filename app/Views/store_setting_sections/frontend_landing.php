                                            <div class="tab-pane fade <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'frontend-landing' ? 'active show' : '' ?>" id="frontend-landing" role="tabpanel" aria-labelledby="frontend-landing-tab">
                                                <h5>Frontend Landing Page</h5>
                                                <form id="frontendSettingForm">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="frontend_category_section">Display Category Section in Home </label>
                                                                <br>
                                                                <input type="checkbox" name="frontend_category_section" id="frontend_category_section" <?= isset($settings['frontend_category_section']) && $settings['frontend_category_section'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="frontend_brand_section">Display Brand Section in Home </label>
                                                                <br>
                                                                <input type="checkbox" name="frontend_brand_section" id="frontend_brand_section" <?= isset($settings['frontend_brand_section']) && $settings['frontend_brand_section'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="frontend_seller_section">Display Seller Section in Home</label>
                                                                <br>
                                                                <input type="checkbox" name="frontend_seller_section" id="frontend_seller_section" <?= isset($settings['frontend_seller_section']) && $settings['frontend_seller_section'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="frontend_popular_section">Display Popular Section in Home</label>
                                                                <br>
                                                                <input type="checkbox" name="frontend_popular_section" id="frontend_popular_section" <?= isset($settings['frontend_popular_section']) && $settings['frontend_popular_section'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="popular_product_show_limit">Popular Section's Product Limit <span class="text-danger text-xs">*</span></label>
                                                                <input type="number" value="<?= isset($settings['popular_product_show_limit']) ? esc($settings['popular_product_show_limit']) : '' ?>" id="popular_product_show_limit" name="popular_product_show_limit" required="" class="form-control ">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="popular_product_show_sort_by">Popular Section's Product Sort By<span class="text-danger text-xs">*</span></label>
                                                                <select id="popular_product_show_sort_by" name="popular_product_show_sort_by" required="" class="form-control ">
                                                                    <option value="default" <?php if ($settings['popular_product_show_sort_by'] == 'default') { echo 'selected'; } ?>>Default</option>
                                                                    <option value="best_selling" <?php if ($settings['popular_product_show_sort_by'] == 'best_selling') { echo 'selected'; } ?>>Best Selling</option>
                                                                    <option value="low_to_high" <?php if ($settings['popular_product_show_sort_by'] == 'low_to_high') { echo 'selected'; } ?>>Low To High</option>
                                                                    <option value="high_to_low" <?php if ($settings['popular_product_show_sort_by'] == 'high_to_low') { echo 'selected'; } ?>>High To Low</option>
                                                                    <option value="maximum_discount" <?php if ($settings['popular_product_show_sort_by'] == 'maximum_discount') { echo 'selected'; } ?>>Maximum Discount %</option>
                                                                    <option value="best_rated" <?php if ($settings['popular_product_show_sort_by'] == 'best_rated') { echo 'selected'; } ?>>Best Rated</option>
                                                                    <option value="alphabetical" <?php if ($settings['popular_product_show_sort_by'] == 'alphabetical') { echo 'selected'; } ?>>Alphabetical</option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="frontend_deal_of_the_day_section">Display Deal Of The Day Section in Home</label>
                                                                <br>
                                                                <input type="checkbox" name="frontend_deal_of_the_day_section" id="frontend_deal_of_the_day_section" <?= isset($settings['frontend_deal_of_the_day_section']) && $settings['frontend_deal_of_the_day_section'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="deal_of_the_day_product_show_limit">Deal of The Day Section's Product Limit <span class="text-danger text-xs">*</span></label>
                                                                <input type="number" value="<?= isset($settings['deal_of_the_day_product_show_limit']) ? esc($settings['deal_of_the_day_product_show_limit']) : '' ?>" id="deal_of_the_day_product_show_limit" name="deal_of_the_day_product_show_limit" required="" class="form-control ">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="deal_of_the_day_product_show_sort_by">Deal of The Day Section's Product Sort By<span class="text-danger text-xs">*</span></label>
                                                                <select id="deal_of_the_day_product_show_sort_by" name="deal_of_the_day_product_show_sort_by" required="" class="form-control ">
                                                                    <option value="default" <?php if ($settings['deal_of_the_day_product_show_sort_by'] == 'default') { echo 'selected'; } ?>>Default</option>
                                                                    <option value="best_selling" <?php if ($settings['deal_of_the_day_product_show_sort_by'] == 'best_selling') { echo 'selected'; } ?>>Best Selling</option>
                                                                    <option value="low_to_high" <?php if ($settings['deal_of_the_day_product_show_sort_by'] == 'low_to_high') { echo 'selected'; } ?>>Low To High</option>
                                                                    <option value="high_to_low" <?php if ($settings['deal_of_the_day_product_show_sort_by'] == 'high_to_low') { echo 'selected'; } ?>>High To Low</option>
                                                                    <option value="maximum_discount" <?php if ($settings['deal_of_the_day_product_show_sort_by'] == 'maximum_discount') { echo 'selected'; } ?>>Maximum Discount %</option>
                                                                    <option value="best_rated" <?php if ($settings['deal_of_the_day_product_show_sort_by'] == 'best_rated') { echo 'selected'; } ?>>Best Rated</option>
                                                                    <option value="alphabetical" <?php if ($settings['deal_of_the_day_product_show_sort_by'] == 'alphabetical') { echo 'selected'; } ?>>Alphabetical</option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="qr_code_search_status">QR Code Search (For App)</label>
                                                                <br>
                                                                <input type="checkbox" name="qr_code_search_status" id="qr_code_search_status" <?= isset($settings['qr_code_search_status']) && $settings['qr_code_search_status'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="voice_search_status">Voice Search (For App)</label>
                                                                <br>
                                                                <input type="checkbox" name="voice_search_status" id="voice_search_status" <?= isset($settings['voice_search_status']) && $settings['voice_search_status'] == '1' ? 'checked' : ''; ?>
                                                                    data-bootstrap-switch data-off-color="danger" class='system-users-switch' data-on-color="success">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group ">
                                                                <label for="app_search_bar_content">App Search Bar Placeholder <span class="text-danger text-xs">Multiple values should be <b>Comma ,</b> Separated</span></label>
                                                                <input type="text" value="<?= isset($settings['app_search_bar_content']) ? esc($settings['app_search_bar_content']) : '' ?>" id="app_search_bar_content" name="app_search_bar_content" required="" class="form-control " placeholder="Ex: Mango, Orange, Apple">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>