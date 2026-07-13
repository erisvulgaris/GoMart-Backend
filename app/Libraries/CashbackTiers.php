<?php

namespace App\Libraries;

/**
 * CityLoop wallet cashback tiers (T24).
 * Pure helpers + wallet credit used by CustomerAppAPI_1_6 placeCODOrder / verifyRazorpayPayment.
 * Extracted for unit testing with a fake wallet sink (no CI4 bootstrap required for pure math).
 */
class CashbackTiers
{
    public static function defaultConfig(): array
    {
        return [
            'free_delivery_min' => 199,
            'tiers' => [
                ['min' => 599, 'cashback' => 50, 'label' => 'Silver'],
                ['min' => 999, 'cashback' => 100, 'label' => 'Gold'],
                ['min' => 1500, 'cashback' => 150, 'label' => 'Platinum'],
                ['min' => 2500, 'cashback' => 250, 'label' => 'Diamond'],
            ],
            'currency' => 'INR',
            'wallet_name' => 'CityLoop Wallet',
        ];
    }

    public static function loadConfig(?array $paths = null): array
    {
        $paths = $paths ?? [];
        foreach ($paths as $path) {
            if (is_string($path) && is_file($path)) {
                $decoded = json_decode((string) file_get_contents($path), true);
                if (is_array($decoded) && !empty($decoded['tiers'])) {
                    return $decoded;
                }
            }
        }
        return self::defaultConfig();
    }

    /** Highest unlocked cashback for cart subtotal (sorted descending by min). */
    public static function unlockedCashback(float $itemSubtotal, array $tiers): float
    {
        $sorted = $tiers;
        usort($sorted, static function ($a, $b) {
            return ((float) ($b['min'] ?? 0)) <=> ((float) ($a['min'] ?? 0));
        });
        foreach ($sorted as $tier) {
            $min = (float) ($tier['min'] ?? 0);
            $amt = (float) ($tier['cashback'] ?? 0);
            if ($itemSubtotal >= $min && $amt > 0) {
                return $amt;
            }
        }
        return 0.0;
    }

    public static function unlockedLabel(float $itemSubtotal, array $tiers): string
    {
        $sorted = $tiers;
        usort($sorted, static function ($a, $b) {
            return ((float) ($b['min'] ?? 0)) <=> ((float) ($a['min'] ?? 0));
        });
        foreach ($sorted as $tier) {
            $min = (float) ($tier['min'] ?? 0);
            $amt = (float) ($tier['cashback'] ?? 0);
            if ($itemSubtotal >= $min && $amt > 0) {
                return (string) ($tier['label'] ?? 'Tier');
            }
        }
        return '';
    }

    /**
     * Credit wallet for order cashback. $wallet is duck-typed:
     *   where(...)->like(...)->first()  or  select(...)->where(...)->orderBy(...)->first()
     *   insert(array)
     * $userModel: set(...)->where(...)->update()
     *
     * @param object $wallet Fake or CI model with chainable query + insert
     * @param object $userModel Fake or CI UserModel
     */
    public static function creditOrderCashback(
        int $userId,
        float $itemSubtotal,
        int $orderId,
        object $wallet,
        object $userModel,
        ?array $config = null
    ): float {
        $cfg = $config ?? self::defaultConfig();
        $tiers = $cfg['tiers'] ?? [];
        $cashback = self::unlockedCashback($itemSubtotal, $tiers);
        if ($cashback <= 0) {
            return 0.0;
        }
        $label = self::unlockedLabel($itemSubtotal, $tiers);
        $remark = 'CityLoop cashback (' . $label . ') Order Id: ' . $orderId;

        // Idempotent: skip if already credited for this order
        $existing = $wallet
            ->where('user_id', $userId)
            ->where('flag', 'credit')
            ->like('remark', 'Order Id: ' . $orderId)
            ->like('remark', 'CityLoop cashback')
            ->first();
        if ($existing) {
            return (float) ($existing['amount'] ?? 0);
        }

        $lastWalletEntry = $wallet
            ->select('closing_amount')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->first();
        $prevClose = $lastWalletEntry ? (float) $lastWalletEntry['closing_amount'] : 0.0;
        $closingAmount = $prevClose + $cashback;

        $wallet->insert([
            'user_id' => $userId,
            'ref_user_id' => 0,
            'amount' => $cashback,
            'closing_amount' => $closingAmount,
            'flag' => 'credit',
            'remark' => $remark,
            'date' => date('Y-m-d H:i:s'),
        ]);
        $userModel->set('wallet', $closingAmount)->where('id', $userId)->update();

        return $cashback;
    }
}
