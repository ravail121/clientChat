<?php
$columns = $where = null;
$columns = ['custom_fields.string_constant(field_name)', 'custom_fields.field_type', 'custom_fields.required'];
$where['AND'] = ['custom_fields.field_category' => 'profile', 'custom_fields.disabled' => 0, 'custom_fields.show_on_signup' => 1];
$where["ORDER"] = ["custom_fields.field_id" => "ASC"];
$custom_fields = DB::connect()->select('custom_fields', $columns, $where);
?>

<form class="signup_form form_element d-none" id="signup_form">

    <div class="d-none">
        <input type="hidden" name="add" value="site_users" />
        <input type="hidden" name="signup_page" value="true" />

        <?php if (isset($_GET['redirect'])) {
            ?>
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']) ?>" />
            <?php
        } ?>

    </div>

    <?php if (Registry::load('settings')->hide_name_field_in_registration_page !== 'yes') {
        ?>

        <div class="field">
            <label><?php echo Registry::load('strings')->full_name ?> <i class="required">*</i></label>
            <input type="text" name="full_name" />
        </div>
        <?php
    } ?>

    <?php if (Registry::load('settings')->hide_email_address_field_in_registration_page !== 'yes') {
        ?>
        <div class="field">
            <label><?php echo Registry::load('strings')->email_address ?> <i class="required">*</i></label>
            <input type="text" name="email_address" />
        </div>
        <?php
    } ?>

    <?php if (Registry::load('settings')->hide_phone_number_field_in_registration_page !== 'yes') {
        ?>
        <div class="field">
            <label><?php echo Registry::load('strings')->phone_number ?> <i class="required">*</i></label>
            <input type="text" class="phone_number_field" name="phone_number" />
        </div>
        <?php
    } ?>

    <?php if (Registry::load('settings')->hide_username_field_in_registration_page !== 'yes') {
        ?>
        <div class="field">
            <label><?php echo Registry::load('strings')->username ?> <i class="required">*</i></label>
            <input type="text" name="username" />
        </div>
        <?php
    } ?>

    <div class="field">
        <label><?php echo Registry::load('strings')->password ?> <i class="required">*</i></label>
        <input type="password" name="password" />
    </div>
    <div class="field">
        <label><?php echo Registry::load('strings')->confirm_password ?> <i class="required">*</i></label>
        <input type="password" name="confirm_password" />
    </div>

    <?php

    foreach ($custom_fields as $custom_field) {
        $field_name = $custom_field['field_name'];

        if ($custom_field['field_type'] === 'short_text' || $custom_field['field_type'] === 'link') {

            ?>
            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <input type="text" name="<?php echo $field_name; ?>" />
            </div>

            <?php
        } else if ($custom_field['field_type'] === 'long_text') {
            ?>

            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <textarea rows="6" name="<?php echo $field_name; ?>"></textarea>
            </div>

            <?php
        } else if ($custom_field['field_type'] === 'date') {

            ?>
            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <input type="date" name="<?php echo $field_name; ?>" class="icon-calendar" />
            </div>

            <?php

        } else if ($custom_field['field_type'] === 'number') {

            ?>
            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <input type="number" name="<?php echo $field_name; ?>" />
            </div>

            <?php

        } else if ($custom_field['field_type'] === 'dropdown') {

            $dropdownoptions = $field_name.'_options';

            if (isset(Registry::load('strings')->$dropdownoptions)) {
                $field_options = json_decode(Registry::load('strings')->$dropdownoptions);
            }
            ?>
            <div class="field">
                <label>
                    <?php echo Registry::load('strings')->$field_name ?>
                    <?php if (!empty($custom_field['required'])) {
                        ?>
                        <i class="required">*</i><?php
                    } ?>
                </label>
                <select name="<?php echo $field_name; ?>">
                    <option value=''>--------</option>
                    <?php foreach ($field_options as $field_option_value => $field_option) {
                        ?>
                        <option value='<?php echo $field_option_value ?>'><?php echo $field_option ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <?php

        }
    }
    ?>

    <?php if (!Registry::load('current_user')->logged_in && Registry::load('settings')->enable_photo_upload_on_signup !== 'no') {
        ?>

        <div class="field">
            <label><?php echo Registry::load('strings')->profile_image ?> </label>
            <input type="file" name="custom_avatar" />
        </div>
        <?php
    }
    ?>



    <div class="field checkbox">
        <label>
            <input type="checkbox" name="terms_agreement" value="agreed">
            <span class="checkmark"></span>
            <span class="text"><?php echo Registry::load('strings')->signup_agreement ?>
                <span class="load_page" page_id="<?php echo Registry::load('settings')->site_terms_conditions ?>">[<?php echo Registry::load('strings')->read_terms ?>]</span>
            </span>
        </label>
    </div>



    <div class="captcha_validation">
        <?php include 'layouts/entry_page/captcha_validation.php'; ?>
    </div>


</form>