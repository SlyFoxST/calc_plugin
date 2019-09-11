<?php
$settings = get_field('yf_tif_settings', 'options');
$data = YF_TIF()->getData();
$age = $data['age'];
$age_id2name = [];
foreach($age AS $a){
    $age_id2name[$a['term']->term_id] = $a['term']->name;
}


?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php wp_head(); ?>

    <link rel="stylesheet" href="/wp-includes/css/dashicons.min.css">
    <link rel="stylesheet" href="/wp-includes/css/admin-bar.min.css">
    <link rel="stylesheet" href="<?= plugin_dir_url(__FILE__) ?>assets/css/libs.min.css">
    <link rel="stylesheet" href="<?= plugin_dir_url(__FILE__) ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= plugin_dir_url(__FILE__) ?>assets/css/style.css">
    <script>
        var $age_id2name = JSON.parse('<?= json_encode($age_id2name) ?>');
    </script>

</head>

<body>


<div class="loader" id="loader" style="display: none;">
    <div class="loader-container">
        <div class="loader-circle"></div>
        <div class="loader-circle"></div>
        <div class="loader-circle"></div>
    </div>
</div>


<div class="main">
    <div class="content">
        <div class="content-block content-block_first active">
            <h2 class="content-title">מחשבון<br>
                ביטוח נסיעות לחו”ל</h2>
            <form action="" name="tifrom-1" class="content-form content-form_first">
                <div class="content-form-row">
                    <select name="region" class="content-form-select content-form-select_first" required>
                        <option></option>
                        <option>דרום ומרכז אמריקה</option>
                        <option>צפון אמריקה</option>
                        <option>אוסטרליה / ניו זילנד</option>
                        <option>אסיה כולל נפאל</option>
                        <option>אסיה ללא נפאל</option>
                        <option>אפריקה</option>
                        <option>אירופה</option>
                    </select>
                </div>
                <div class="content-form-row">
                    <div class="content-form-block">
                        <input type="text" name="date_from" min="<?= date('Y-m-d') ?>" class="content-form-date" placeholder="יציאה" autocomplete="off" required>
                    </div>
                    <div class="content-form-block">
                        <input type="text" name="date_to" class="content-form-date" placeholder="חזרה" autocomplete="off" required>
                    </div>
                </div>
                <div class="content-form-row">
                    <p class="content-form-time">תקופת נסיעה: <span class="content-form-days days_txt">0</span> ימים</p>
                    <input type="hidden" name="days" value="0" />
                </div>
                <div class="content-form-row">
                    <a href="#" class="content-form-link js-popup" data-src="http://form.youfuture.com.ua/wp-content/uploads/2019/04/bg2.jpg">למה שווה לרכוש כאן ביטוח <img src="<?= plugin_dir_url(__FILE__) ?>assets/img/arrow.svg" alt="" class="page-left-img"></a>
                </div>
                <button type="submit" class="content-form-btn">המשך</button>
            </form>
        </div>
        <div class="content-block content-block_second">
            <form action="" name="tifrom-2" class="content-form content-form_second">
                <div class="content-form-add">
                    <div class="content-form-row">
                        <select name="passenger_age[]" class="content-form-select content-form-select_second" required>
                            <option></option>
                            <?php /*for($i=1;$i<=120;$i++): */?><!--
                                <option><?/*= $i */?></option>
                            --><?php /*endfor; */?>
                            <?php foreach($age AS $a): ?>
                                <option value="<?= $a['term']->term_id ?>"><?= $a['term']->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="content-form-edit">
                    <a href="" class="content-form-edit-add"><span class="text-bold">+</span> הוסף מבוטח נוסף</a>

                    <a href="" class="content-form-edit-delete"><span class="text-bold">-</span></a>
                </div>
                <button type="submit" class="content-form-btn">המשך</button>
                <button type="button" id="screen-2-back-btn" class="content-form-btn">חזרה</button>
            </form>
        </div>
        <div class="content-block content-block_third">
            <div class="third-first third-item">
                <div class="third-container">
                    <h2 class="content-title"> <span class="content-title-span passengers_txt">1</span> מבוטחים | <span class="content-title-span region_txt">דרום ומרכז אמריקה</span></h2>
                    <div class="content-form-row content-form-row_third">
                        <div class="content-form-block">
                            <!--<p class="content-date date_from_txt"><?/*= date('d/m/Y') */?></p>-->
                            <input type="text" name="date_from_txt" class="content-form-date" value="<?= date('d/m/Y') ?>">
                        </div>
                        <div class="content-form-block">
                            <!--<p class="content-date date_to_txt" ><?/*= date('d/m/Y') */?></p>-->
                            <input type="text" name="date_to_txt" class="content-form-date" value="<?= date('d/m/Y') ?>">
                        </div>
                    </div>
                    <p class="content-form-text">תקופת הנסיעה: <span class="total-day days_txt">0</span> ימים</p>
                    <a href="javascript:;" class="content-form-desc js-text" data-text="text">מידע על מצב רפואי קיים</a>
                    <form action="" class="content-form content-form_third">
                        <p class="content-form-subtitle">הרחבות אפשריות:</p>
                        <div class="content-form-flex">
                            <?php foreach($data['options'] AS $option): ?>
                                <?php if(in_array($option['term']->term_id, $data['exists_options'])): ?>
                            <label class="label">
                                <input type="checkbox" data-hide_date_select="<?= $option['hide_date_select'] ? 1 : 0 ?>" class="label-input option-input" data-option_id="<?= $option['term']->term_id ?>" value="<?= $option['term']->term_id ?>">
                                <span class="label-span"></span>
                                <span class="label-text"><?= $option['term']->name ?></span>
                                <?php if($option['tooltip']): ?>
                                <a href="" class="label-info js-text" data-text="<?= $option['tooltip'] ?>">?</a>
                                <?php endif; ?>
                            </label>
                                    <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <button type="submit" class="content-form-btn content-form-btn_third js-popup" data-src="<?= $settings['companies_button_image']['url'] ?>"><?= $settings['companies_button_text'] ?></button>
                    </form>
                </div>
            </div>
            <div class="third-second third-item">
                <div class="third-container">
                    <div class="result">
                        <?php foreach($data['companies'] AS $company): ?>
                            <?php if(!$company['hide_in_main_list']): ?>
                        <div class="result-item">
                            <div class="result-top">
                                <div class="result-left">
                                    <a href="javascript:;" data-company_id="<?= $company['post']->ID ?>" class="result-link result-link__place_order">לרכישה</a>
                                </div>
                                <div class="result-price">
                                    <p class="result-price-text"><?= $company['post']->post_title ?></p>
                                    <p class="result-price-value" id="company-<?= $company['post']->ID ?>-price"><?= $company['min_price'] ?><?= $company['currency'] ?></p>
                                </div>
                                <div class="result-logo">
                                    <img src="<?= get_the_post_thumbnail_url($company['post']->ID) ?>" alt="" class="result-logo-img">
                                </div>

                            </div>
                            <div class="result-bottom">
                                <div class="result-bottom-item">
                                    <?php foreach($company['left_text'] AS $t): ?>
                                    <p class="result-bottom-text"><?= $t ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <div class="result-bottom-item">
                                    <?php foreach($company['middle_text'] AS $t): ?>
                                        <p class="result-bottom-text"><?= $t ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <div class="result-bottom-item">
                                    <?php foreach($company['right_text'] AS $t): ?>
                                        <p class="result-bottom-text"><?= $t ?></p>
                                    <?php endforeach; ?>
                                    <a href="" class="result-bottom-link js-popup" data-src="<?= $company['popup_image']['url'] ?>">פירוט כיסויים</a>
                                </div>
                            </div>
                        </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="third-third third-item">
                <div class="third-container">
                    <div class="page">
                        <div class="page-left">
                            <?php if(isset($settings['buttons']) && is_array($settings['buttons'])): ?>
                                <?php foreach($settings['buttons'] AS $btn): ?>
                                    <a href="" class="page-left-link js-popup" data-src="<?= $btn['image']['url'] ?>">
                                        <img src="<?= plugin_dir_url(__FILE__) ?>assets/img/arrow.svg" alt="" class="page-left-img">
                                        <span class="page-left-text"><?= $btn['text'] ?></span>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="page-right">
                            <p class="page-right-text">יש לכם שאלה?</p>
                            <p class="page-right-text">לא בטוחים במה לבחור?</p>
                            <h4 class="page-right-title">התקשרו אלינו:</h4>
                            <a href="tel:<?= preg_replace('/\D/', '', $settings['phone']) ?>" class="page-right-phone"><?= $settings['phone'] ?></a>
                            <h6 class="page-right-subtitle">או הזמינו שיחה:</h6>
                            <form action="" name="feedback-form" class="page-right-form">
                                <input type="text" name="user_name" class="page-right-input" placeholder="שם פרטי ומשפחה" required>
                                <input type="tel" name="user_phone" class="page-right-input" placeholder="מספר טלפון" required>
                                <div class="checkbox-popup-flex checkbox-popup-flex_big">
                                    <?php foreach($data['companies'] AS $company): ?>
                                    <label class="label label_sm">
                                        <input type="checkbox" name="company" value="<?= $company['post']->ID ?>" class="label-input">
                                        <span class="label-span"></span>
                                        <span class="label-text"><?= $company['post']->post_title ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>

                                <button type="submit" class="content-form-btn content-form-btn_page">שלח</button>
                                <button type="button" class="content-form-btn content-form-btn_back">חזרה</button>
                            </form>
                            <p class="thank-text">תודה הפרטיים נשלחו בהצלחה ,נתקשר בהקדם</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="popup checkbox-popup">
    <div class="checkbox-popup-content flex-height">
        <a href="" class="checkbox-popup-close option-to-person-close"></a>
        <p class="checkbox-popup-title">בחר תאריכים להרחבה זו</p>
        <div class="checkbox-popup-row">
            <input type="text" id="personal_date_from" class="checkbox-popup-date" required>
            <p class="checkbox-popup-text">מתאריך:</p>
        </div>
        <div class="checkbox-popup-row">
            <input type="text" id="personal_date_to" class="checkbox-popup-date" required>
            <p class="checkbox-popup-text">עד תאריך:</p>
        </div>
        <div id="option-to-person">
            <input type="hidden" value="0" name="option-to-person-id"  style="display: none;"/>
            <p class="checkbox-popup-subtitle">סמן למי מהנוסעים להרחיב:</p>
            <div class="checkbox-popup-flex">
                <!-- Age checkbox here -->
            </div>
        </div>
        <a href="" class="content-form-btn checkbox-popup-btn" id="confirm-option-btn">המשך</a>
    </div>
</div>
<div class="popup popup-img">
    <div class="popup-img-content flex-height">
        <a href="" class="checkbox-popup-close"></a>
        <img src="" alt="" class="popup-img-img">
    </div>
</div>
<div class="popup popup-text">
    <div class="popup-img-content flex-height">
        <a href="" class="checkbox-popup-close"></a>
        <p class="popup-img-text"></p>
    </div>
</div>
<div class="hide">
    <div class="content-form-row">
        <select name="passenger_age[]" class="content-form-select content-form-select_third" required>
            <option></option>
            <?php /*for($i=1;$i<=120;$i++): */?><!--
                <option><?/*= $i */?></option>
            --><?php /*endfor; */?>
            <?php foreach($age AS $a): ?>
                <option value="<?= $a['term']->term_id ?>"><?= $a['term']->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<?php wp_footer(); ?>

<script src="<?= plugin_dir_url(__FILE__) ?>assets/js/libs.min.js"></script>
<script src="<?= plugin_dir_url(__FILE__) ?>assets/js/common.js"></script>
<script src="<?= plugin_dir_url(__FILE__) ?>assets/js/script.js"></script>

</body>
</html>
