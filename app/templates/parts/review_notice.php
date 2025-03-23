<?php
/**
 * Displays review notice.
 *
 * @package ElastiForm
 */

// If direct access than exit the file.
defined('ABSPATH') || exit;

?>
<style>
.ElastiForm-review-notice p {
    font-size: 17px;
}

/* .ElastiForm-review-notice strong>a {
    color: #2ecc40;
} */

.ElastiForm-review-notice .notice-actions {
    display: flex;
    flex-direction: column;
}

.ElastiForm-review-notice .notice-overlay {
    position: absolute;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    padding: 15px 70px 15px 15px;
    background: #fff;
    border-radius: 4px;
    opacity: 0;
    transition: all 0.5s ease;
}

.ElastiForm-review-notice .notice-overlay.active {
    opacity: 1;
    z-index: 111;
}

.ElastiForm-review-notice .notice-overlay-wrap {
    transition: all 0.5s ease;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    pointer-events: none;
    z-index: 99;
}

.ElastiForm-review-notice .notice-overlay-wrap.active {
    background: #000000a6;
    opacity: 1;
    pointer-events: all;
}

.ElastiForm-review-notice .notice-overlay-actions {
    display: flex;
    flex-direction: column;
}

.ElastiForm-review-notice .promo_close_btn {
    position: absolute;
    top: 0;
    right: 0;
    margin: 5px 5px 0 0;
    cursor: pointer;
}
</style>
<div class="notice notice-large is-dismissible notice-info ElastiForm-review-notice">
    <p><?php esc_html_e('Hi there, it seems like', 'elastiform'); ?>
        <a href="https://wordpress.org/plugins/simple-form/" target="_blank">
            <?php echo esc_html(ELASTIFORMPLUGIN_NAME); ?>
        </a>
        <?php esc_html_e('is bringing you some value, and that is pretty awesome! Can you please show us some love and rate', 'elastiform'); ?>
        <a href="https://wordpress.org/plugins/simple-form/" target="_blank">
            <?php echo esc_html(ELASTIFORMPLUGIN_NAME); ?>
        </a>
        <?php esc_html_e('on WordPress? It will take two minutes of your time, and will really help us spread the world.', 'elastiform'); ?>
    </p>

    <div class="notice-actions">
        <a href="https://wordpress.org/support/plugin/simple-form/reviews/?filter=5#new-post"
            target="_blank"><?php esc_html_e('I\'d love to help', 'elastiform'); ?> :)</a>
        <a href="#" class="remind_later"><?php esc_html_e('Not this time', 'elastiform'); ?></a>
        <a href="#" class="hide_notice"
            data-value="hide_notice"><?php esc_html_e('I\'ve already rated you', 'elastiform'); ?></a>
    </div>

    <div class="notice-overlay-wrap">
        <div class="notice-overlay">
            <h4><?php esc_html_e('Would you like us to remind you about this later?', 'elastiform'); ?></h4>

            <div class="notice-overlay-actions">
                <a href="#" data-value="3"><?php esc_html_e('Remind me in 3 days', 'elastiform'); ?></a>
                <a href="#" data-value="10"><?php esc_html_e('Remind me in 10 days', 'elastiform'); ?></a>
                <a href="#"
                    data-value="hide_notice"><?php esc_html_e('Don\'t remind me about this', 'elastiform'); ?></a>
            </div>

            <span class="promo_close_btn">
                <?php require ELASTIFORMBASE_PATH . 'assets/public/icons/times-circle-solid.svg'; ?>
            </span>
        </div>
    </div>
</div>


<script>
jQuery(document).ready(function($) {
    $('.ElastiForm-review-notice .notice-actions > a').click(e => {

        let target = $(e.currentTarget);

        if (target.hasClass('hide_notice') || target.hasClass('remind_later')) {
            e.preventDefault();
        }

        if (target.hasClass('remind_later')) {
            $('.ElastiForm-review-notice .notice-overlay-wrap').addClass('active')
            $('.ElastiForm-review-notice .notice-overlay').addClass('active')
        }

        if (target.hasClass('hide_notice')) {

            $.ajax({
                type: "POST",
                url: "<?php echo esc_url(admin_url('admin-ajax.php')); ?>",
                data: {
                    action: 'ElastiForm_notice_action',
                    nonce: '<?php echo esc_attr(wp_create_nonce('ELASTIFORMnotices_nonce')); ?>',
                    info: {
                        type: 'hide_notice'
                    },
                    actionType: 'review_notice'
                },
                success: response => {
                    if (response.data.response_type === 'success') {
                        $('.ElastiForm-review-notice').slideUp();
                    }
                }
            });
        }
    })
    $('.ElastiForm-review-notice .promo_close_btn').click(e => {
        e.preventDefault();
        $('.ElastiForm-review-notice .notice-overlay').removeClass('active')
        $('.ElastiForm-review-notice .notice-overlay-wrap').removeClass('active')
    })

    $('.ElastiForm-review-notice .notice-overlay-actions > a').click(e => {
        e.preventDefault();
        $('.ElastiForm-review-notice .notice-overlay').removeClass('active')
        $('.ElastiForm-review-notice .notice-overlay-wrap').removeClass('active')

        let target = $(e.currentTarget);
        let dataValue = target.attr('data-value');

        $.ajax({
            type: "POST",
            url: "<?php echo esc_url(admin_url('admin-ajax.php')); ?>",
            data: {
                action: 'ElastiForm_notice_action',
                nonce: '<?php echo esc_attr(wp_create_nonce('ELASTIFORMnotices_nonce')); ?>',
                info: {
                    type: 'reminder',
                    value: dataValue
                },
                actionType: 'review_notice'
            },
            success: response => {
                if (res.data.response_type === 'success') {
                    $('.ElastiForm-review-notice').slideUp();
                }
            }
        });

    })
});
</script>
