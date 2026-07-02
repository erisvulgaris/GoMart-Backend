<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$updates = [[1, "uploads/products/fresh_tomato_hybrid.jpg"], [2, "uploads/products/potato_jyoti.jpg"], [3, "uploads/products/fresh_onion.jpg"], [4, "uploads/products/banana_robusta.jpg"], [5, "uploads/products/apple_royal_gala.jpg"], [6, "uploads/products/pomegranate_kesar.jpg"], [7, "uploads/products/fresh_coriander_leaves.jpg"], [8, "uploads/products/ginger_adrak.jpg"], [9, "uploads/products/garlic_lahsun.jpg"], [10, "uploads/products/amul_taaza_toned_milk.jpg"], [11, "uploads/products/amul_gold_full_cream_milk.jpg"], [12, "uploads/products/mother_dairy_double_toned_milk.jpg"], [13, "uploads/products/amul_butter.jpg"], [14, "uploads/products/amul_cheese_slices.jpg"], [15, "uploads/products/amul_paneer_fresh.jpg"], [16, "uploads/products/white_farm_eggs.jpg"], [17, "uploads/products/english_oven_white_bread.jpg"], [18, "uploads/products/english_oven_brown_bread.jpg"], [19, "uploads/products/lay_s_american_style_cream_onion.jpg"], [20, "uploads/products/lay_s_classic_salted.jpg"], [21, "uploads/products/kurkure_masala_munch.jpg"], [22, "uploads/products/parle_g_gold_biscuits.jpg"], [23, "uploads/products/britannia_good_day_cashew_cookies.jpg"], [24, "uploads/products/oreo_chocolate_sandwich_cookies.jpg"], [25, "uploads/products/haldiram_s_aloo_bhujia.jpg"], [26, "uploads/products/haldiram_s_bhujia_sev.jpg"], [27, "uploads/products/cadbury_dairy_milk_silk.jpg"], [28, "uploads/products/coca_cola_aerated_drink.jpg"], [29, "uploads/products/pepsi_soft_drink.jpg"], [30, "uploads/products/sprite_lemon_lime_drink.jpg"], [31, "uploads/products/real_fruit_power_mixed_fruit.jpg"], [32, "uploads/products/real_fruit_power_guava.jpg"], [33, "uploads/products/paper_boat_mango_juice.jpg"], [34, "uploads/products/bisleri_packaged_water.jpg"], [35, "uploads/products/kinley_club_soda.jpg"], [36, "uploads/products/red_bull_energy_drink.jpg"], [37, "uploads/products/maggi_2_min_masala_noodles.jpg"], [38, "uploads/products/maggi_masala_noodles_pack_of_4.jpg"], [39, "uploads/products/yippee_magic_masala_noodles.jpg"], [40, "uploads/products/mccain_french_fries.jpg"], [41, "uploads/products/mccain_veggie_fingers.jpg"], [42, "uploads/products/mccain_smiles.jpg"], [43, "uploads/products/kissan_fresh_tomato_ketchup.jpg"], [44, "uploads/products/funfoods_veg_mayonnaise.jpg"], [45, "uploads/products/amul_dark_chocolate_spread.jpg"], [46, "uploads/products/aashirvaad_shudh_chakki_atta.jpg"], [47, "uploads/products/fortune_chakki_fresh_atta.jpg"], [48, "uploads/products/rajdhani_besan.jpg"], [49, "uploads/products/tata_sampann_toor_dal.jpg"], [50, "uploads/products/tata_sampann_chana_dal.jpg"], [51, "uploads/products/tata_sampann_moong_dal.jpg"], [52, "uploads/products/india_gate_basmati_rice_mogra.jpg"], [53, "uploads/products/india_gate_basmati_rice_super.jpg"], [54, "uploads/products/fortune_everyday_basmati_rice.jpg"], [55, "uploads/products/surf_excel_easy_wash.jpg"], [56, "uploads/products/surf_excel_matic_liquid.jpg"], [57, "uploads/products/comfort_fabric_conditioner.jpg"], [58, "uploads/products/vim_dishwash_gel_lemon.jpg"], [59, "uploads/products/vim_dishwash_bar.jpg"], [60, "uploads/products/pril_dishwash_liquid.jpg"], [61, "uploads/products/harpic_toilet_cleaner_liquid.jpg"], [62, "uploads/products/lizol_floor_cleaner_floral.jpg"], [63, "uploads/products/colin_glass_cleaner_spray.jpg"], [64, "uploads/products/dettol_liquid_handwash_refill.jpg"], [65, "uploads/products/dove_cream_beauty_bathing_bar.jpg"], [66, "uploads/products/dettol_bathing_soap_original.jpg"], [67, "uploads/products/colgate_strong_teeth_toothpaste.jpg"], [68, "uploads/products/sensodyne_fresh_mint_toothpaste.jpg"], [69, "uploads/products/colgate_zig_zag_toothbrush_medium.jpg"], [70, "uploads/products/clinic_plus_strong_long_shampoo.jpg"], [71, "uploads/products/head_shoulders_anti_dandruff.jpg"], [72, "uploads/products/parachute_coconut_hair_oil.jpg"]];

$success = 0;
foreach ($updates as $update) {
    $p_id = (int)$update[0];
    $img_path = $db->real_escape_string($update[1]);
    
    $q = "UPDATE product SET main_img = '$img_path' WHERE id = $p_id";
    if ($db->query($q)) {
        $success++;
    }
}

echo json_encode([
    "status" => "success",
    "message" => "Database main_img records updated.",
    "updated_count" => $success
]);
$db->close();
