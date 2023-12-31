<div class="col-md-5 col-lg-3 aside page_column visible" column="first">
    <div class='head'>
        <?php
        if (Registry::load('current_user')->logged_in) {
            ?>
            <span class='menu toggle_side_navigation'>
                <i>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M127.999 271.999c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 512c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 752.001c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0z"></path>
                    </svg>
                </i>
                <span class="total_unread_notifications"></span>
            </span>
            <?php
        }
        ?>
        <span class='logo refresh_page'>
            <?php if (Registry::load('current_user')->color_scheme === 'dark_mode') {
                ?>
                <img width="100px" height="50px" src="<?php echo Registry::load('config')->site_url.'assets/files/logos/chat_page_logo_dark_mode.png'.$cache_timestamp; ?>" />
                <?php
            } else {
                ?>
                <img width="100px" height="50px" src="<?php echo Registry::load('config')->site_url.'assets/files/logos/chat_page_logo.png'.$cache_timestamp; ?>" />
                <?php
            } ?>
        </span>
        <span class='icons'>
            <?php
            if (Registry::load('current_user')->logged_in && role(['permissions' => ['private_conversations' => 'view_private_chats']])) {
                ?>
                <span class="icon load_aside pm_shortcut" load="private_conversations">
                    <i>
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024">
                            <path fill="currentColor" d="M246.858 495.428c0-27.456 22.258-49.714 49.714-49.714v0h232c27.456 0 49.714 22.258 49.714 49.714s-22.258 49.714-49.714 49.714v0h-232c-27.456 0-49.714-22.258-49.714-49.714v0zM296.572 280c-27.456 0-49.714 22.258-49.714 49.714s22.258 49.714 49.714 49.714v0h430.858c27.456 0 49.714-22.258 49.714-49.714s-22.258-49.714-49.714-49.714v0h-430.858z"></path>
                            <path fill="currentColor" d="M976 197.142c0-82.369-66.773-149.142-149.142-149.142v0h-629.714c-82.369 0-149.142 66.773-149.142 149.142v0 729.142c0 0.002 0 0.004 0 0.006 0 27.456 22.258 49.714 49.714 49.714 13.070 0 24.962-5.044 33.836-13.291l-0.032 0.027 185.666-172.342c8.841-8.216 20.731-13.258 33.8-13.258 0.002 0 0.004 0 0.007 0h475.866c82.369 0 149.142-66.773 149.142-149.142v0-430.858zM826.858 147.428c27.456 0 49.714 22.258 49.714 49.714v0 430.858c0 27.456-22.258 49.714-49.714 49.714v0h-475.798c-0.025 0-0.056 0-0.085 0-39.202 0-74.871 15.125-101.491 39.857l0.094-0.085-102.146 94.788v-615.132c0-27.456 22.258-49.714 49.714-49.714v0h629.714z"></path>
                        </svg>
                    </i>
                    <span class="notification_count"></span>
                </span>
                <?php
            }
            if (!Registry::load('current_user')->logged_in) {
                if (Registry::load('settings')->hide_groups_on_group_url) {
                    ?>
                    <i class="iconic_groups load_groups load_aside d-none" load="group_members" data-group_id="<?php echo(Registry::load('config')->load_group_conversation) ?>"></i>
                    <?php
                } else {
                    ?>
                    <i class="iconic_groups load_groups load_aside d-none" load="groups"></i>
                    <?php
                }
            }
            ?>
        </span>
    </div>

    <div class="storage_files_upload_status">
        <div class="center">
            <div class="error">
                <span class="message"><?php echo Registry::load('strings')->error ?> : <span></span></span>
            </div>
            <div class="text">
                <span><?php echo Registry::load('strings')->uploading_files ?> : <span class="percentage">0%</span></span>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
            </div>
            <div class="files_attached">
                <form class="files_attached_form" enctype="multipart/form-data">
                    <input type="hidden" name="upload" value="storage" />
                    <input type="hidden" name="frontend" value='true' />
                    <input type="file" multiple="" name="storage_file_attachments[]" class="storage_file_attachments" />
                </form>
            </div>
        </div>
    </div>

    <div class="audio_player_box module hidden">
        <?php include 'layouts/chat_page/audio_player_box.php'; ?>
    </div>

    <div class="site_records module">
        <?php include 'layouts/chat_page/site_records.php'; ?>
    </div>


    <?php
    if (!Registry::load('current_user')->logged_in) {
        ?>
        <div class="info_box">
            <div>
                <div class="text">
                    <?php echo Registry::load('strings')->not_logged_in_message ?>
                </div>
                <span class="button open_link" autosync=true link="<?php echo Registry::load('config')->site_url ?>entry/">
                    <?php echo Registry::load('strings')->login ?>
                </span>
            </div>
        </div>
        <?php
    }
    ?>


    <?php
    if (isset($site_adverts['left_content_block'])) {
        $site_advert = $site_adverts['left_content_block'];
        $advert_css = 'max-height:'.$site_advert['site_advert_max_height'].'px;';

        if (!empty($site_advert['site_advert_min_height'])) {
            $advert_css .= 'min-height:'.$site_advert['site_advert_min_height'].'px;';
        }

        ?>

        <div class="site_advert_block" style="<?php echo $advert_css; ?>">
            <div>
                <?php echo $site_advert['site_advert_content']; ?>
            </div>
        </div>
        <?php
    }
    ?>

    <?php include 'layouts/chat_page/mini_audio_player.php'; ?>

</div>