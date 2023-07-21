<?php

// function from https://stackoverflow.com/a/5624139/3695983
function hexToRgb($hex)
{
    $shorthandRegex = '/^#?([a-f\d])([a-f\d])([a-f\d])$/i';
    $hex = preg_replace_callback($shorthandRegex, function ($matches) {
        return $matches[1] . $matches[1] . $matches[2] . $matches[2] . $matches[3] . $matches[3];
    }, $hex);

    $result = [];
    $rgbRegex = '/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i';
    preg_match($rgbRegex, $hex, $result);

    return $result ? [
        'r' => hexdec($result[1]),
        'g' => hexdec($result[2]),
        'b' => hexdec($result[3])
    ] : null;
}
;

// function from https://stackoverflow.com/a/9733420/3695983  
function luminance($r, $g, $b)
{
    $a = array_map(function ($v) {
        $v /= 255;
        return $v <= 0.03928
            ? $v / 12.92
            : pow(($v + 0.055) / 1.055, 2.4);
    }, [$r, $g, $b]);

    return $a[0] * 0.2126 + $a[1] * 0.7152 + $a[2] * 0.0722;
}
;

function getContrastColor($hex)
{
    if ($hex == null || $hex == "") {
        return "#000000";
    }

    $red = hexdec(substr($hex, 1, 2));
    $green = hexdec(substr($hex, 3, 2));
    $blue = hexdec(substr($hex, 5, 2));

    if (($red * 0.299 + $green * 0.587 + $blue * 0.114) > 186) {
        return "#000000";
    }

    return "#ffffff";
}
;

function calculateContrastRatio($color1, $color2)
{
    // read the colors and transform them into RGB format
    $color1rgb = hexToRgb($color1);
    $color2rgb = hexToRgb($color2);

    // calculate the relative luminance
    $color1luminance = luminance($color1rgb['r'], $color1rgb['g'], $color1rgb['b']);
    $color2luminance = luminance($color2rgb['r'], $color2rgb['g'], $color2rgb['b']);

    // calculate the color contrast ratio
    $ratio = $color1luminance > $color2luminance
        ? (($color2luminance + 0.05) / ($color1luminance + 0.05))
        : (($color1luminance + 0.05) / ($color2luminance + 0.05));

    $contrast_ratio = 1 / $ratio;

    return $contrast_ratio;
}
;

function check_save_for_color_contrast($id = false, $post = false)
{
    if ($post->post_title == "Auto Draft") {
        return;
    }

    if ($post->post_type == 'merchants') {
        $primary_color = get_field('primary_color', $id);

        if ($primary_color == "") {
            return;
        }

        $contrast_color = getContrastColor($primary_color);
        $ratio = calculateContrastRatio($primary_color, $contrast_color);

        if (true) {
            $error = new WP_Error('invalid_data', 'Invalid component data. Contrast Ratio: ' . $ratio, array('status' => 400));
            add_filter('redirect_post_location', function ($location) use ($error) {
                return add_query_arg('my-plugin-error', $error->get_error_code(), $location);
            });
            return;
        }

        return $ratio;
    }
}

function handle_save_post_error()
{
    if (array_key_exists('my-plugin-error', $_GET)) { ?>
        <div class="error">
            <p>
                <?php
                switch ($_GET['my-plugin-error']) {
                    case 'invalid_data':
                        echo 'Invalid component data. ' . $_GET['my-plugin-error-data'];
                        break;
                    default:
                        echo 'An error occurred when saving the post.';
                        break;
                }
                ?>
            </p>
        </div>
        <?php
    }
}

// Check for contrast on save of a merchant object
// add_action('save_post', 'check_save_for_color_contrast');
// add_action('admin_notices', 'handle_save_post_error');