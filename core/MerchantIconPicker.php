<?php

namespace StiavaMerchantsApp\Core;

/**
 * @copyright https://stackoverflow.com/questions/62860289/how-to-create-a-second-featured-image-box-for-posts-in-wordpress
 */
class MerchantIconPicker
{
    // Add second featured image

    public static function merchant_icon_add_metabox()
    {
        add_meta_box('listingimagediv', __('Merchant Icon', 'text-domain'), [self::class, 'merchant_icon_metabox'], 'merchants', 'side', 'high');
    }

    public static function merchant_icon_metabox($post)
    {
        global $content_width, $_wp_additional_image_sizes;

        $image_id = get_post_meta($post->ID, 'icon_id', true);
        $icon_type = get_post_meta($post->ID, 'icon_type', true);
        if (!$icon_type) {
            $icon_type = 'text';
        }

        $old_content_width = $content_width;
        $content_width = 254;

        if ($image_id && get_post($image_id)) {

            if (!isset($_wp_additional_image_sizes['post-thumbnail'])) {
                $thumbnail_html = wp_get_attachment_image($image_id, array($content_width, $content_width));
            } else {
                $thumbnail_html = wp_get_attachment_image($image_id, 'post-thumbnail');
            }

            if (!empty($thumbnail_html)) {
                $content = $thumbnail_html;
                $content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_listing_image_button" >' . esc_html__('Remove merchant icon', 'text-domain') . '</a></p>';
                $content .= '<input type="hidden" id="upload_listing_image" name="_merchant_icon_image" value="' . esc_attr($image_id) . '" />';
                $content .= '<label for="_merchant_icon_type">Icon Type</label>  
                <select name="_merchant_icon_type">
                    <option value="text"' . ($icon_type === 'text' ? 'selected' : '') . '>Use Text Only</option>
                    <option value="wordmark"' . ($icon_type === 'wordmark' ? 'selected' : '') . '>Wordmark Icon</option>
                    <option value="seal"' . ($icon_type === 'seal' ? 'selected' : '') . '>Seal/Circular Icon</option>
                </select>';
            }

            $content_width = $old_content_width;
        } else {

            $content = '<img src="" style="width:' . esc_attr($content_width) . 'px;height:auto;border:0;display:none;" />';
            $content .= '<div class="editor-post-featured-image__container"><button type="button" class="components-button editor-post-featured-image__toggle" title="' . esc_attr__('Set Merchant Icon', 'text-domain') . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Set listing image', 'text-domain') . '">' . esc_html__('Set Merchant Icon', 'text-domain') . '</button><div class="components-drop-zone" data-is-drop-zone="true"></div></div>';
            $content .= '<input type="hidden" id="upload_listing_image" name="_merchant_icon_image" value=""';

        }

        echo $content;
    }

    public static function merchant_icon_image_save($post_id)
    {
        if (isset($_POST['_merchant_icon_image'])) {
            $image_id = (int) $_POST['_merchant_icon_image'];
            update_post_meta($post_id, 'icon_id', $image_id);
        }

        if (isset($_POST['_merchant_icon_type'])) {
            $type = (string) $_POST['_merchant_icon_type'];
            update_post_meta($post_id, 'icon_type', $type);
        }
        else {
            update_post_meta($post_id, 'icon_type', 'text');
        }


    }


    /**
     * Enqueue jquery functionality
     */
    public static function wpdocs_selectively_enqueue_admin_script($hook)
    {
        if ('post.php' != $hook && 'post-new.php' != $hook) {
            return;
        }
        wp_enqueue_script('secondimage', plugins_url('../js/secondimage.js', __FILE__), array('jquery'), '1.0');
    }


    public static function run()
    {
        add_action('admin_enqueue_scripts', [self::class, 'wpdocs_selectively_enqueue_admin_script']);
        add_action('add_meta_boxes', [self::class, 'merchant_icon_add_metabox']);
        add_action('save_post', [self::class, 'merchant_icon_image_save'], 10, 1);

    }

}