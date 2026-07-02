<?php
// Set execution time limit to 10 minutes
set_time_limit(600);
header('Content-Type: text/plain');

// Enable live output flushing
ob_implicit_flush(true);
ob_end_flush();

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo "Connection failed: " . $db->connect_error . "\n";
    exit;
}

$url_map_json = '{"1": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR", "2": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHw8z3ivjY4i0U6_x1VTWg0xdS8x1mWTzUrIUQmkHRmhyVEcrPN8JvaMi_ms8DdzDoU-NjSd1by_4tMXo-_7jLdG4aktISK_-QrotkAcaxZZEHrn5GM_7uO7ylB5VDIwg==", "3": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR", "4": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHw8z3ivjY4i0U6_x1VTWg0xdS8x1mWTzUrIUQmkHRmhyVEcrPN8JvaMi_ms8DdzDoU-NjSd1by_4tMXo-_7jLdG4aktISK_-QrotkAcaxZZEHrn5GM_7uO7ylB5VDIwg==", "5": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHFvzxWateoc4qsF2DubrBz2W09oD30ebqjTGKmIHXnzs7-sJouI-tkEKQpZjCZ_K0Nz68-HxAh0nPLcZC29_Ga_cF8Y2RJ0oNLGGYZawa4kx6lY8MCFWhyY-hPH9pj6Ywh67_q3n3cGce3vHlGfuaEHf0", "6": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHFvzxWateoc4qsF2DubrBz2W09oD30ebqjTGKmIHXnzs7-sJouI-tkEKQpZjCZ_K0Nz68-HxAh0nPLcZC29_Ga_cF8Y2RJ0oNLGGYZawa4kx6lY8MCFWhyY-hPH9pj6Ywh67_q3n3cGce3vHlGfuaEHf0", "7": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR", "8": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR", "9": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR", "10": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR", "11": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR", "12": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR", "13": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGFBUgt2udfwK83rKp7A2lBBVrqms05Taz7DZrQlUf5KW5EqrlEHpPgG-X941JycvGZkVZejngMaaKJuHLI42nqsJg90uwyKPmdSN2meUjvffi6iZAbRr1MFl5l5TqJSifegQ3sD-1c3bk=", "14": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEk8HQjRehF8iIQqXsCAq-nwgmLVho2OGGDYESll_miMOLIfS-grNqnqzk4rCN3DgW9Bd3MXauXUNbHwJU40YeBEx9qDaHr5BUBdmRbuIXOjQ8qjMenNiJm3AT2LOx1OkYjtnTkNKMjuPKEYJM=", "15": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGwRg0_8mNPDsgrbCxYMCeKLtxEukIb-WjNDiTjhSB6f5CDypi7sLtZ9Trb7sSzjqH22k4uBahZManQQnCqk2WvLkBZacMseIt1C-pkTTvbDUqIyAQhdfRC87bsTFOyY3GeIiyESc_NvmhDVJPJoVlA2Q==", "16": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHZdNwsNoKqiCZ4QXmIioltvHGhe6SXwwSHYB_zvCIRo0R_vr1tKIyGRd86Y0m2nq0WkuoSx0j9kjsSP8ehqq-H3nbFb5x6Jzbhr8rWYjBmVTKvZ0PD4l9hBXltB3tFVsbajLpVM9ZUsruVfvfu6wU1drkJWQtDi1ym0SZAdIk=", "17": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH9oC2WS2vPDYA95e9ktzpxZWfBt9io41Ilbze7RwOSO4rDMSJghMQuwElf-xRXmdf9j8d-_Y6dph95qJvaEZqXBujifXTlShSBqrj5n74souGyPMblJb4j7zsClofVd-EE5Bd8JIX_QamOOiMFZ0CCJ0iqXcdutUqVwPRAFe4Z", "18": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHodAXzn5w-Nuz55sJKLmfU1lb8PATWseNONoZDlSySqKPErmpe-e-8pNotDRlnVZ1BY1ULamj9R69mh2QeTOnpqXMrthESjRZy_cKG0j-6AMRXwp8dEx0-tYFyuVN4lnpeXezl3eDL5jbiYbVX1aDycg==", "19": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH32E7mybPxpOQVxjw7MgAGhOw4QwRgyiS8VWezGrz6kuyh0nGT8rQ264gASFP1PeJm3B8lzV2KcMI3MXtaJd_d_0xd9vFWLLd_ZTWQQoL3n9uCAwmo_oqMpl6flaNjW_bVXit497y15qQB2VezEdvg2aquj6JR7-GUAAlO6BRQBYQ2h6x3py6Ud4Hlf_jf", "20": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEXz2FRj67BYyx5aImdcqeBB3eejRUGTm5gcj7_zZn0wHE8WeoXgQQLqKxgWDwh99j_WKJjSEIOFri7fk_9-dLqDio5iNwtZU8uby-NvPyaM0YW8IepeiWJUBpxD8EXiKKTrZkZyGTp8hKZ-0P29wxlUuxEYIpiyd_H", "21": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHX2nd7H5SyzHtA4Ucrc5PycWoCiTjHW1ogc2mQYvwNMITE3OSKeW87NaTTxzQBGc0yoxJhujGM5ANqagfXQGnfcLL990Uq5A-q0c48AaLFTre88g3CB6_HM9F4mAcempjNJu_j7KQDS7O3JQhcFeJ7TbtWBg==", "22": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGIh-7oRA4nxape0po5SVciecoQ1K2sSH96Uas8iI6AO_pz0utvpQMO0xT6Ugw-LE_GTR5O40LTT9tjNS4O0a8Z4F5es-Fe0CVaw_AITrB7YSEfpoXGTzD2KGzmpV08aN9LaKXbPiHpz6Yx-iiW", "23": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFOzxPcF4Obq6Z6q6QFKY_o4zCmWd5YTbSNQMU4cAj30xNKDJns8JMAfSEDpIQthaU8z03uyyi-7Xnn4mp8HiQ5y9n9734xkDOzuF60hks0_TfGknPHtg7dSZvrFKb3uUAXlLHkidDeGPtdPIoFXn5eQ16uCq8YBpldtQ==", "24": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHbcNX8NmvEAT7fwX5z5Td-CAHsKBQu9o1moCePaZ6KYYSDlGApvBC8DJzFxRKao0e7Tx0XcOVNqg7x5lkxpsvC8q75EKeY7mwj0bPDZUS922Ci6fHxDY9vzoMi7TcXQQy-A9uIejHUEIY17GFX1sLc-3Z2LUkV26rIisv2GGM22edqGuTsiV2Jb3NLDL1Iuv2GXXshdUC5aEQ=", "25": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQExt9eudPvGD9qKtoaeyyM3sZ2QMUlxNkw470xbBMH2xtTY9u8nbA_ts5tdlmI4uRwa62YewDWvNtzW5YIOQe6hnvlomKi1RENe-FbymniuzA3I8XNk_xd7PjS9G7pquTB5dGTwLdCJTOme417WS0n8_EGeJ--2cg==", "26": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHsyfvZhdiRNDWRXVmUAchWc5E8on9Wtyrxhxa9NVji0T1dEb06mDycv0rJMBw38SWpNsjSOeGKYAfRUyEWLfIQBW0JhUcMstnVUbH_-_zS_vpm_zwsSOm0L3uEc-NkH0VKwg0fNls4a4i9kCVMBs1gh54JJQ==", "27": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFgbD2xfMwtJPWwfzRS5Me98ctRb7_Jro8EqpW8GvKESnlYsRYm0_cxOGyaPf_Kby0HnBnM9yxVZM2MDzCu7sb8p2A5-bUiHRfYLWoqWFD1SuCa7ttBM3VnsUNc8_7dhGQxslCyPPZQ", "28": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHYDSnLRr0YSQ90IWLCcpD7fukGUVl8ek_Ga8x7eV7bkTX3tmJmfjaZflCBiaa0v8eOua5y3psFbGMmbn23SKHaZQq35j4UzkyC2kjhPlT-ZH_ZNZTeKQF_WkCG6v1YEi4Kpv_BMDnuWA7BMCgu", "29": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH4uV3EqpOzPwLUqAZ_KnKjLD7RTvTaJx6l7EuEeLkqk-awxg5Qi5sOkrcaNk6I6xLB62etbguSclvo3g_KaUZtZwqdrmE96pRZhR40VzeH7XVy81ZXy7MmHSfMWP5g7Oa_4TQvDIu4nEeWQu7iGw==", "30": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGC-IoCxTpZIVMKtjMEfLyA1sbE7wcihvbEu4TC1w5X92ci-WSYNdTW8FUwcO3s2ecOq4Orkh1Ektd48qFVDWjI7E9ieXCZFYuPeobnni4vLJybvXiWatzrHHCZsmsyKSLTybyuQpfQWasW4K2x114sDB9lSg1w", "31": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEko2Pd5_Mk4fAi-VAq_TjGPWfWNtuwGAOFK9LbqDtDc-edEPedqvdlkIJDvkobE7HQgtpc3MnLh_1ytA52xijsOjn4BVjNz6vhDEo5iFfKaiSzxJHSB3TqZbT5aiKTZ3wUjeC0v-5fFPQ8-RhwEy3Zx8xWeyOPny6ebj9DcQ==", "32": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFbKSKgJME_s_tCb9pRWzNMPRyScgKbwTq1auiqHPUBnkXbKGvmiJrHucUo68vEbUQd7NNG3ox4h3Se_lBhie1dyFz7nDjA0LNsyicSBMgmX4TQsHSTBwzi_1iTaoQV_5UMtL86I-omX_vTwiFEhtYAe93g", "33": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH4VLFuKqvqem1dlmOPkIADSrJpqujiw7C9i244MZaMbZg6GD4msGOXLjEOx4FNJHkYloKj0A28nnh38e2fLaHlA7qHXw5xN57uItOQjADMxH1Thm0WxnoSajuRe5BwRYbnChO2UZSGrIa51PK5kZGtZ802aTA=", "34": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE4mJwZvPUYNdsg-Du_wFOJ_cTZtlh-oqtCxjq41UhlWJc2pNrs7wSk7sB9YEP8jnpLVP2FGl6IRiIQFgbK37pabFOox2fGZgkh5D6wjErkedZcKftfNtNmYXuZgmtiltEeQBuHSif90QNhv3RSft6B", "35": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFXrtsutFv7xw7jhpNnwpqf6IQVPdSQrXH3jbgZQs0ntB84DwKUlAOiqLLBQGP2SyUE3wmM4Lq6wLU98v46dCcJroLdrkxr1o1uHPXDJ1Ckn7xQQPxl1PLfIzawxosTs314WT-JwFrZLsRJO49k0Ksg", "36": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHpDcskSQG_-ZaiEU7s00YBvrCC5EePTbRhhllBx0TMqM87vH-wRrPuYPVxSfg_k4p6HxYgEvVOaPOPHHpnyVuXHWOHjEpequlYI4n_q6GI86d886jbo8tbxQ==", "37": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGK8N-HOA2GjYhC_pgTED5ur0eQVmfLzmIpsiFoJKmNcJwW_LYkNYuGtLEIQIM6EDUlw9xSNxlp2EkffDx-U75usE-vel-BMWNEd96Jv97UzH4iz8Yuyl7zue8duJf4UfOjPM8hgAQFVd70VVg7XBZ5yVz1pMxYTV7jU-fB-47I2n7_VanjFDMjJnO4_Fjb-q-zFBFzHZOqcTE=", "38": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEc6yGScDHc8M5PoBpkgTsvH1tynjwXUU6vTtbGRxb2-aietadm4PwiCyLM3GOXhlBWslMqw708lCJSY9fYj6zcmdry0BDMJKrr4_vOw8mjTHz2L2Ggw14-xoJj3-2p575jZmmJs8Af9J3diizFoa5Yd0kcj0reic9f9KFtaaUmzNXSuP_yyvKK8Q==", "39": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGX6y6I9UIqV0kR0bPrnZz5gNQBr37NhF-eBwgZDstRW1GmcpfYH1lG0d8QsSIxQei66W7h-GHbiFDXB42gRgB1ddYXRHK3ToDiaWe57EmdyGGBcpYDDSqfIlm2o1yJs6nG1aQ2DAvGOW5WyuFjBq3R9yhYtRfUjFmWk-4GTDATRSXLpvlVU8Bs1mBqpBN7dQ==", "40": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGgPVDM7o7k_672HCm3WtHNFOntiM8MrwxSjC8JTEueFVCIH-970WzVE_LbfKTFCXbF0wb3kx5ewDt_A_dXCYIeb4yTfEKs7D0rvkCMIjvjiXr38kflCYlQHQ6z02jYY3AIb1qAY2DQP58efsI=", "41": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQF_nLMa3IzSY_wG868to5GyGPNPVSzY95KVjArKoSDI1fELlFqa6Wp7Pcen1d__w_DqHP33xq25leux26nOPOqad7vtKvQ83VXSlTDppWILgntrGetJq53_7h4pYki1j_2qflcF8q-_R11Q37k3SkXZfA5Bi3c=", "42": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEZWFM7AJLhp84Hmx3TtzDdHQOoFjMFxrXf94GzYTToRJ5n2io5TmeUkCh1maDO3ETLN3h_Td-IKb7-M8YY_0p1i2UXbBR2LblqC0-g2J5K4qSa6g2kh8rBUQfP9T-ejOC1lblPFfk_seO41epE", "43": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFl1EqXO1IORiRJM4ijgc3VxERGfjKof1r0mi-9_Ie5aT9Ayw6yC3oW2AumAfghMmh2d52xq46zC8y16AQlu7pI6pNaDRFCRomm_CFrW2o1bUMCgy88f_PdOjHeLYEL-SfNjMH4pJypgMeLLHYJXR3XYC63Bvs=", "44": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFhsrt_pIZ0AC9PW4Bnta_NZCb-XpJ136bEhtyRQ8hXPEijUGxZOxoor0i8p2AJISSoPpBFvx-kn5R-LTLixq8QbGmx2NM0XLQS7GnXUpCpcEUyE2clBeevysMcpwO4Q8pJIR1bvkbM-0NuQsB8BLSALhxsbLIi2qVyeyBlk0dPp4RNatTh2rLRhGw=", "45": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH47657_kiqppvWxViPpgVnWMUI3ZCoUiIdQSH0PI9L60lXAi9cmxAiG7_ERZiWHq0JPkvRkd3Oj91HLsyOMsitGAW-dTR1uRVYOCUtWrHRTrYHFeD8VT_OdHxuVEaKtzO1PpI5vo2AKxPytfF1JzHkdUWC_cPd_Uo=", "46": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQF0qJuqwzzdovJ4GxhlzwEwh-BQWLmta9olsAJTtOzbJBzCEF_NsJB2hI3bZ7rAV8yOmCGSXgjrN-CjILyPkAxitReCiy8beYfbG8bTF80470H4Q1nVAvpc5pjm268s2y-UEYXGzA6BlApQjDDDNCw3CeVOzVkWGZGT6Fy-DU1pEkI188Svj6Y=", "47": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQF-jvyfEMDSvp7xc1xpoMNYvUJ6fKQrlxWDBa7PKIviBv_nYSPbxotvH16WJ5eq8Fgu2KsTS6H3fpSd8371o2Rw3soCkaSoEYO3D3SFf3W22rAV0uSMYXszVFz3wrUR6FDy-TRmv8JKDIvjjXVAcydv93NIMJnWkdshPQ==", "48": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGhkmZPK6UP_8dPxT9E_UWWXbhrvRDouVO29MQvQru4qu5VWlfmYI3JbbL5pWwqli5BXSbISBMz1N1ZWLgLmJRRhFCZAZeR6icSDjBX9ab7COMA_vi-gSNdSnUmqSak_Q3Ys265t9dw", "49": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHxIJvkPyFXa6xgU9msuSw8Op77KhpcpT3bHnlQ1BnvnU75SoLcIbrSxPTBoFmk4p58xQ0-_WoN_aFD3wZa5K4p_nuhP-Ezkis-e00bGgeEnH3IGpEffAhQMZo6i_914rAMvuIRjYVLWuFDfrVIaKZ58O40oOBLg-QEK0vfDqD0MKM=", "50": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE833I2woiKSWF7eXqv2E7ftnE1P1nk1d0RjqYjmgh757aG1TuxADWb0Dw6WGxrOQjPY82sU0gDoxRMH6A_idPf2mYLiCG0Xe6Scl05OBbGw_5ugNpW82ieQfzutgcHxXhbO1QgMuIoConzP_7Lxgr7CK5WcoeMN2np", "51": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE7BlGyRg0pjdsfnHSFTzPcJeCMs8zc40Y1CkBlsOk618I4qyeiBQole6jvq5Wf0ofmuWcnqGglYuKZS2FN-SNYxiNtTpQJmNkhUWKjz1Bnm86MEtpnTRwMrDeRALbyBbmZ-UdFLBWogncHDM1UJAQdTLCchgmKs8JdhcQgM45rA5dB0uatIshCvvM9vQ==", "52": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHdpGhxagIG1kri0_HetF1UN-baSzd_bcEuuwMhoLt3NIgtIg9e_RQij4r_66X0lAkwT5PV5lOtPaNs6zBHnySt-aVEGi3SQ37OYO2thQDG7TmAdsY2kX5RUKyfXZOEwSEErN5oMfDGciAObpZXN0v10QWcecVPwS2ykuoLUuXn5eIUUA==", "53": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHTEuUJ8vUNozhOpL-aFcmPZjDAu-jymnmpPgZSpXqqap6ER0pmzRycZ7t4gvv7D1uBwVqX26tnRr-kvlN0CWwZXACWbCKjwr5a0KmTfKK6y4uAWBINvpQARM-788SvLWkPID4_EEoLFbiGGccdTXRc9E4=", "54": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFcjwMwrijvjd9w1WAjiA4MOAKWv5hST9ZLy1ubLtkDegfa0KjmBaoKCCYZcLgQULV1Rwkhyy5x2QoF1W4gMzHQ6o37oVGoiJf5Q9qDbdicrvsoGtmrv_-py7xfEeTYv0MRXCthCmU_XEOvRWMQuL3bTqFEiqJni7phrEeffA==", "55": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHm-1w_-5cvmWFxP5SjgQZABF30g0fzuiWsX0xOFEuExzAKhHSqAe5-RG-nk_YesicUGtA7_IgDtzBrTvVba1MPYGz9fDwNIJEvd6uX1YJgBSQ0DL6sTljbXRxXs16fWzLMc29yO-ygtGUNQZgzB6Vhd0F1hKy0yOn9YgYcB9Q=", "56": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQEIzCOaI25S92EKVUBmAly0NZG9aZHHQ2nIXvt4Qwrj2CvZhNOZXhzvHrwjUOXX_Cfyf8CQ8AD993seA10MHay_v5CMjqu9mxRWtcrLH6pYixsdt-IX2so801vvNBQJuR9usECVrRbp3dtWRjDDl471IH9fF8yWcABtYpYsI7eU12R0YBL5URcBt1PK-Zw=", "57": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH_sQTPvlwAJDKrTfv1vA8DV5MScV1rke2f114B-Yom9DCsjsGhWilzX6ftFvPO5XZWuFyG7UzAaClcdgY_AnfeS_3_KP4_GdXAeJfKv1v7b-B31eBRIYaE5FlcBhkly6W8sljvSRBepdLiYbltBdSWPghM-2gw0GTNVwa66vy4FHQJ", "58": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQF5aYGqlNNj5QH-gemFI7uZ7SbuF_Rc8xQI9qTMEsISPH-LlgQU-_cPTNvEGfhFppTfkq36o80noE8GLAcZe8iqiCqh49kRyalWF9-ZFThRWkAiaNdPaxPF8jyNokEtdTPXl2_0PmCumVhvIuTtGQ==", "59": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQFhyZ5IOBk7uQ8Id0iQXP9ZBWkK96VxC5w8pA51f4HAMnM1iqDWgnWRa51WlAb828Xlvq9IbpDiz81HolqLB9G3Vj7Lz6vtFyIsuAPSJtcPTdBckfVyaoy8LIpufx8CG8wXG2snu3fULlNMjIKWVFThmNMeyA==", "60": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGJdW8kAOf2Um7ahjz0j6pgVktDk5JR0H6O0S3YcYbTgDGdMEqznR99AfnPXtZAAZl3F48rwSGwrqukIH-r9yC5Z2N_nszWCp7bNK4HMJKJzf3xkxX_jLbOJi8ijC9L4O_yjWuUaTp151CF32VJJ3w=", "61": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH5VzfDqOp_mxhCWemGZzjBySt60Y_PT6MPkNWX5IIT7dGnH8JaoqxO0VVBfRAketPVmR4dtUV3Zq8-hOZjopPT7QPM_UVWAket6x3JN-_lMN1bhpXeDAVgmJnmRJeydUfyehxxPBLL_Xq7GIMjJhEQvIoN3qGSzLai82Hpr8qpXLm-kxpiJVj08a8s3g==", "62": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHMdRAsThMvObCne-rJ9k1auewknZzgRHm-N1wDRES0ZvkawVKZuY8MqJ2ZR_UTtEuuK_Mvvit35OYK675kdFow42l-s7EOMThZ7saTydOREfuGdqfI-9LjB7HPwL-1oGYl3RW2W2Cz-I1o6ujsi5iEO-S4bOgUUinm3sI62NcFLjazMZS4UcC1w7y_EQ==", "63": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHOMIZXG6UB4SzNe5rLrYuy7mHIP65zRWJytY8hwT_deQKPqarhXHpTSzC2WLV8Fgccptld-W227_eKpgNSZ_nQhh2Y3F2tudnZPKwzwTNTXKZAENqFyNabEp86aPALq0qe95gpMrwWyi_9OzEfN1rTYdIB5jNLjZbGzw==", "64": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQF0ZPBy7j4Ti2odRA-gMN57CaYVPnD77qR0pqZEeqAel00l-a9zi_A5NjWad3-ukBI0b5c9dMTp27JTdtfXlmptHoR9NXQZM2jd4F-t4vwbPSTa-VGwPW2nvciP1Qbg3gO-P6jq-XpLRQ4tYfetVsqUF6r0Nd9a53MCjkHL1AxjH2sh5uKNxUvfPee4wLsa3w6WNCiJaDvZ", "65": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQG3gjcmcW_bdHLcu5ZOJlB67mL28srZMHz9YHKCMmF3b9t9pVJaR6_b7V6-Ba3h5YAg-2r_wCPXNkpOWocHTCta9hjFEWayiFnoiiVPdnTImXV7RQCnQowOYI7dlVMN5N0v-ibArnoIlhyOsf4ROrMysl1CGVFQVkDuNp8MgA0N3QhE", "66": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQECTjX2eOmq8eXiTijVeLW53OXnZRHifI8Lzclb0rp9McabJzg9wDkFN8kuhBCysEHLNP2O3JmXhRqWDjSqmaaNcvqyiN0X1p2nVDJwda4Nh_2gKzesMMvP0ms7XENktBAWmxds99OKU70XGJWy7e5-Vhag7THo1nZoWrFHKMvTe8Ylxgigu4M=", "67": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQF-2xgpo82pwWB7byrfW1DHAISWiUbLF-0H0gohLfA0kD_wG8YkjvgRq49nics2C43WaK_G2NS-F0b7y3Fv-xhDE5Q7QKYcAvCVnkC1La1qauFTmx3NnZeUFJzmg0rxDe-xQ8inoCISKrEgsVQCAv7l8Mui0lBFneuZU_LSaYuilYb8aEkkMQmyZw==", "68": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHqDmyDexUhMq7F-fb42hY0-GKaA1rdu5MU4_9M2Zbacj84SEXg8dQab_hlrv48eJMWEgYBQi2GnRJIHFRmJx7IuMF9Qi9hWdiBKZkRO2Yk0Jmb57s3yBrHN40FML0eofvpjB4IYt64rCEouOXOQGGpURyou0ZO9xt-uJEjXVPPqMoD", "69": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQH7ZR57xNGH5XNoeqlbtoniO1loiuX4FzOfDOFqDFfpvpnSMl9-WyH216xwTW0LPOqZongs0YaGjGZPpCDId4bNBTp_X_AWeYyTofZYAkze5zuKu5t60XNa9qt4ywqgI59dvkSJpgMXdNEJ-IY7tmNBHkE4S_nyzk8u6TR3hiO5p9BL9vz3-bmYNDnmZuWq3Zswe3X_AWjF-g1B9_7cpiinsB5TgYY5BLs=", "70": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE0xJnQgjPtTmdnQeUE5xfwWJP7030bntE1susxqSGqHTmLyTQmVufgh-i4YxW6I6IW9RVIhs0LrtJlp_izKaKHa38ZjtlqFD175lq8kLL3b04Y-h-GC3eM9R61Gx1E4PYA1GLNtdvg1W-pULZXqrTnzGFdZd4JvAtafc-ZwknS", "71": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHCAwcegJ6P8SNMBunv8XtA619hBFXXBtVJIeoYEIctHWdNkcfGzmeNbbJvOWf8tCGe-bnrIwJD3AY0CYApKOuNA8H6mANIUWdbDqPL5zhb9U-0FGEGM4d_3_u4i_kDZ_m0xKJZWj94jV6ZJpWLs7xAcyUpp26p1YEERVO8eA==", "72": "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQE0o7ZSuTlubabC2NsIELiISyIccmjuqXjQLr1TPphXZdxc_iyK1hRGAEtFN-4rS1BReGAngzZeu6wPEH87e0VZQHTFbyg3hvn_86eueJAExNe6fxb3Pb--WkTCjoN1KPyWEOQhlljCJOTv5dZDHEjFwt4TpqTCEreBfFPvxSLNiAlo9j4="}';
$url_map = json_decode($url_map_json, true);

$success_count = 0;
$log = [];

// Create products folder if not exists
$dir = __DIR__ . '/uploads/products';
if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
}

$res = $db->query("SELECT id, product_name FROM product WHERE is_delete = 0");
$products = [];
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
}

echo "Starting download for " . count($products) . " products...\n";

foreach ($products as $p) {
    $p_id = $p['id'];
    $p_name = $p['product_name'];
    
    if (!isset($url_map[$p_id])) {
        echo "No redirect URL mapped for ID $p_id: $p_name\n";
        continue;
    }
    
    $redirect_url = $url_map[$p_id];
    
    // Fetch product page following location redirects
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $redirect_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    $prod_html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code != 200 || !$prod_html) {
         echo "Failed to fetch page for $p_name (HTTP $http_code)\n";
         continue;
    }
    
    // Find og:image
    preg_match('/<meta[^>]*property="og:image"[^>]*content="([^"]+)"/i', $prod_html, $og_matches);
    $img_url = null;
    if (!empty($og_matches[1])) {
        $img_url = html_entity_decode($og_matches[1]);
    } else {
        // Try twitter:image
        preg_match('/<meta[^>]*name="twitter:image"[^>]*content="([^"]+)"/i', $prod_html, $tw_matches);
        if (!empty($tw_matches[1])) {
            $img_url = html_entity_decode($tw_matches[1]);
        }
    }
    
    if ($img_url) {
        // Create clean slug
        $slug = preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($p_name)));
        $slug = trim($slug, '_');
        $local_path = $dir . '/' . $slug . '.jpg';
        $db_path = 'uploads/products/' . $slug . '.jpg';
        
        // Download image
        $ch3 = curl_init();
        curl_setopt($ch3, CURLOPT_URL, $img_url);
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch3, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch3, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $img_data = curl_exec($ch3);
        $img_code = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
        curl_close($ch3);
        
        if ($img_code == 200 && $img_data) {
            file_put_contents($local_path, $img_data);
            $db_path_esc = $db->real_escape_string($db_path);
            $db->query("UPDATE product SET main_img = '$db_path_esc' WHERE id = $p_id");
            $success_count++;
            echo "Downloaded: " . $p_name . " -> " . $db_path . "\n";
        } else {
            echo "Failed to download image for $p_name from $img_url (HTTP $img_code)\n";
        }
    } else {
         echo "No image tag found on product page for $p_name\n";
    }
    
    // Sleep 1 second to be polite to Blinkit
    sleep(1);
}

echo "Finished. Total successfully updated: $success_count\n";
$db->close();
