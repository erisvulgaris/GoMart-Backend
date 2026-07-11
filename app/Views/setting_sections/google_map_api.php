                                toastr.error(response.message, "Admin says");
                            }
                        },
                        error: function(e) {
                            toastr.error("Error while testing mail", "Admin says");
                        },
                    });
                }
            });
        }

    </script>

    <script>
        // System Info tab logic
        <?php
        $db       = \Config\Database::connect();
        $cfgRow   = $db->tableExists('sys_config') ? $db->table('sys_config')->where('cfg_id',1)->get()->getRowArray() : null;
        $cfgState = ($cfgRow && $cfgRow['cfg_state'] == 1) ? 1 : 0;
        $cfgSync  = ($cfgRow && $cfgRow['cfg_sync']) ? date('d M Y, h:i A', $cfgRow['cfg_sync']) : 'Never';
        $cfgDomain = ($cfgRow && $cfgRow['cfg_origin']) ? esc($cfgRow['cfg_origin']) : '-';
        ?>
        var _sysState  = <?= $cfgState ?>;
        var _sysSync   = "<?= $cfgSync ?>";
        var _sysDomain = "<?= $cfgDomain ?>";

        function submitNewCode() {
            var code = document.getElementById('newPurchaseCode').value.trim();
            if (!code) { toastr.error('Enter a purchase code'); return; }
            var btn = document.getElementById('activateBtn');
            btn.disabled = true;
            btn.innerText = 'Verifying...';
            $.ajax({
                url: '/admin/setting/sys-activate',
                type: 'POST',
                data: { rc: code },
                dataType: 'json',
                success: function(r) {
                    if (r.success) {
                        toastr.success(r.message, 'System');
                        setTimeout(function(){ location.reload(); }, 1200);
                    } else {
                        toastr.error(r.message, 'System');
                        btn.disabled = false;