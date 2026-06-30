<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CoreSyncFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $db = \Config\Database::connect();

        // Check table exists
        if (!$db->tableExists('sys_config')) {
            return redirect()->to('/init');
        }

        $row = $db->table('sys_config')->where('cfg_id', 1)->get()->getRowArray();

        if (!$row || $row['cfg_state'] != 1) {
            return redirect()->to('/init');
        }

        $currentDomain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $lastSync      = (int)$row['cfg_sync'];
        $domainMismatch = ($row['cfg_origin'] !== $currentDomain);

        // Force immediate recheck if domain doesn't match stored origin (SQL copy/import attack)
        // Also recheck every 7 days normally
        if ($domainMismatch || (time() - $lastSync) > (7 * 24 * 3600)) {
            $this->_reSyncState($db, $row, $currentDomain);

            // Re-read updated state — if revoked, block immediately
            $row = $db->table('sys_config')->where('cfg_id', 1)->get()->getRowArray();
            if (!$row || $row['cfg_state'] != 1) {
                return redirect()->to('/init');
            }
        }
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
