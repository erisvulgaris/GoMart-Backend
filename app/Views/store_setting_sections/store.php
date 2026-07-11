                                            <div class="tab-pane fade <?php echo (!isset($_GET['setting']) || $_GET['setting'] == 'store') ? 'active show' : '' ?>" id="store" role="tabpanel" aria-labelledby="store-tab">
                                                <h5>Store Setting</h5>
                                                <form id="storeSettingForm" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                <input type="text" id="business_name" class="form-control " name="business_name" value="<?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?>" required placeholder="Enter Title">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input type="email" id="email" class="form-control " name="email" value="<?= isset($settings['email']) ? esc($settings['email']) : '' ?>" required placeholder="Enter Email">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Phone</label>
                                                                <input type="text" id="phone" class="form-control " name="phone" value="<?= isset($settings['phone']) ? esc($settings['phone']) : '' ?>" required placeholder="Enter Phone">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Company GST Number</label>
                                                                <input type="text" id="company_gst" class="form-control" name="company_gst" value="<?= isset($settings['company_gst']) ? esc($settings['company_gst']) : '' ?>" placeholder="Enter GST Number (e.g. 22AAAAA0000A1Z5)" maxlength="20" style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase()">
                                                                <small class="form-text text-muted">Printed on customer invoices.</small>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Store Address</label>
                                                                <textarea class="form-control " name="address" id="address" rows="3" required placeholder="Enter Store Address"><?php echo json_decode($settings['address'])->address; ?></textarea>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Latitude</label>
                                                                        <input type="text" id="latitude" class="form-control " name="latitude" value="<?php echo json_decode($settings['address'])->latitude; ?>" required placeholder="Enter Latitude">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Longitude</label>
                                                                        <input type="text" id="longitude" class="form-control " name="longitude" value="<?php echo json_decode($settings['address'])->longitude; ?>" required placeholder="Enter Longitude">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="logo_aspect_ratio">Select Logo Ratio <span class="text-danger text-xs">*</span></label>
                                                                        <select id="logo_aspect_ratio" name="logo_aspect_ratio" required="" class="form-control ">
                                                                                <option value="1:1" <?php if ($settings['logo_aspect_ratio'] == '1:1') { echo 'selected'; } ?>> 1:1 Ratio (Square)</option>
                                                                                <option value="1:3" <?php if ($settings['logo_aspect_ratio'] == '1:3') { echo 'selected'; } ?>> 1:3 Ratio (Rectangle)</option>
                                                                        </select>
                                                                    </div>
                                                            
                                                                    <div class="form-group">
                                                                        <label for="exampleInputBorder">Logo</label>
                                                                        <div class="dropzone custom-dropzone" id="images-dropzone">
                                                                            <div class="dropzone-clickable-area">
                                                                                <div class="icon"><i class="fi fi-br-upload"></i></div>
                                                                                <p>Upload Logo</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputBorder">Old Logo </label>
                                                                        <br>
                                                                        <img src="<?php echo  base_url($settings['logo']) ?>" style="max-width: 200px;" alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Select Store Location</label>
                                                                <div class="form-group">
                                                                    <input type="text" autocomplete="false" id="pac-input" class="custom-form-control" placeholder="Search City">
                                                                </div>
                                                                <div id="map"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>