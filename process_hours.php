<?php
$split_regex = "/(?:;|\n)+/";
$days_regex = "/(?!AM|PM)[A-Z]{2,8}/";
$inner_split_regex = "/(?:,)+/";
$open_regex = "/([O][\S]*)/";
$closed_regex = "/([C][\S]*)/";
$date_regex = "/(\d{2})[\D](\d{2})[\D](\d{4})/";

function serialize_date($date)
{

    $date_regex = $GLOBALS['date_regex'];

    preg_match_all($date_regex, $date, $parts);

    return $parts[3][0] + $parts[2][0] / 100 + $parts[1][0] / 10000;
}


function distance_formula($x, $y)
{
    [$x1, $x2] = $x;
    [$y1, $y2] = $y;

    $value = 0;
    try {
        $value = sqrt(pow($x1 - $y1, 2) + pow($x2 - $y2, 2));
    } catch (Throwable $e) {
        return 10000;
    }

    return $value;
}



function update_merchants()
{
    $args = [
        'post_type' => 'merchants',
        'numberposts' => -1,
    ];
    $posts = get_posts($args);

    foreach ($posts as $post) {
        setup_postdata($post);

        // Update/save the post without making any changes
        wp_update_post(
            array(
                'ID' => $post->ID,
            )
        );

        // Reset the post data to ensure other functions/processes work correctly
        wp_reset_postdata();
    }

    return "success";
}
