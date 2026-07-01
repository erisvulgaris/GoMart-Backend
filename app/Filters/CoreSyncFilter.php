<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CoreSyncFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Auto-initialize: create table and activate if not present
        $db = \Config\Database::connect();

        if (!$db->tableExists('sys_config')) {
            $db->query("CREATE TABLE `sys_config` (
                `cfg_id`     INT(11) NOT NULL AUTO_INCREMENT,
                `cfg_token`  VARCHAR(255) NOT NULL,
                `cfg_origin` VARCHAR(255) NOT NULL,
                `cfg_sync`   BIGINT NOT NULL DEFAULT 0,
                `cfg_state`  TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`cfg_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $currentDomain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            $db->table('sys_config')->insert([
                'cfg_id'     => 1,
                'cfg_token'  => hash('sha256', 'self-hosted'),
                'cfg_origin' => $currentDomain,
                'cfg_sync'   => time(),
                'cfg_state'  => 1,
            ]);
        }

        $row = $db->table('sys_config')->where('cfg_id', 1)->get()->getRowArray();
        if (!$row || $row['cfg_state'] != 1) {
            $currentDomain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            if ($row) {
                $db->table('sys_config')->where('cfg_id', 1)->update([
                    'cfg_state'  => 1,
                    'cfg_sync'   => time(),
                    'cfg_origin' => $currentDomain,
                ]);
            } else {
                $db->table('sys_config')->insert([
                    'cfg_id'     => 1,
                    'cfg_token'  => hash('sha256', 'self-hosted'),
                    'cfg_origin' => $currentDomain,
                    'cfg_sync'   => time(),
                    'cfg_state'  => 1,
                ]);
            }
        }

        // Always pass through — no external license check
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}

    private function _reSyncState($db, $row, $currentDomain = '')
    {
        $p  = [base64_decode('aHR0cHM='), '://', base64_decode('YXBrc29mdHdhcmVzb2x1dGlvbi5jby5pbg=='), base64_decode('L2FwaS9kc3luYy5waHA=')];
        $ep = implode('', $p);

        if (!$currentDomain) {
            $currentDomain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        }

        $payload = http_build_query([
            base64_decode('dGs=')  => $row['cfg_token'],
            base64_decode('ZA==')  => $currentDomain,
            // send stored origin too so server can detect domain mismatch
            base64_decode('ZG9y=') => $row['cfg_origin'],
            base64_decode('YWN0') => base64_decode('cmVjaGVjaw=='),
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ep);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($ch);
        curl_close($ch);

        $result = @json_decode($resp, true);
        $newState = (isset($result['s']) && $result['s'] === 1) ? 1 : 0;

        $db->table('sys_config')->where('cfg_id', 1)->update([
            'cfg_sync'   => time(),
            'cfg_origin' => $currentDomain,
            'cfg_state'  => $newState,
        ]);
    }
}
