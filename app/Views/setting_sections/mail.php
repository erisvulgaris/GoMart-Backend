                                            <?= $this->include('setting_sections/mail') ?>
                                            <?= $this->include('setting_sections/google_map_api') ?>
                                            <?= $this->include('setting_sections/login') ?>
                                            <?= $this->include('setting_sections/notification') ?>
                                            <?= $this->include('setting_sections/social_links') ?>
                                            <?= $this->include('setting_sections/firebase') ?>
                                            <?= $this->include('setting_sections/external_api') ?>
                                            <?= $this->include('setting_sections/other') ?>
                                            <?= $this->include('setting_sections/language') ?>
                                            <?= $this->include('setting_sections/app_main_header') ?>
                                            <?= $this->include('setting_sections/sys_info') ?>
                                            <!-- /System Info Tab -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        <?= $this->include('template/footer') ?>

        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->

    <?= $this->include('template/script') ?>
    <script src="<?= base_url('/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= isset($settings['map_api_key']) ? esc($settings['map_api_key']) : '' ?>&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
    <script src="<?= base_url('/assets/page-script/setting.js') ?>"></script>
    <script>
        function testMail() {
            Swal.fire({
                title: "Confirm?",
                text: "Make sure mail setting is update done successfully!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, setting updated",
            }).then((result) => {
                if (result.isConfirmed) {
                    var test_mail_id = $("#test_mail_id").val();
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (!emailRegex.test(test_mail_id)) {
                        toastr.error("Please enter a valid email address", "Admin says");
                        return;
                    }
                    $.ajax({
                        url: "/admin/setting/mail/test",
                        type: "POST",
                        data: {
                            test_mail_id
                        },
                        dataType: "json",
                        beforeSend: function() {
                            toastr.info('Sending test mail', "Admin says");

                        },
                        success: function(response) {
                            if (response.success == true) {
                                toastr.success(response.message, "Admin says");
                            } else {