<table class="codetable">
    <tr>
    <?php
    $all_section_codes = array();
    foreach ( $sections as $section ) {
        $args = array(
            'post_type' => 'code',
            'tax_query' => array(
                array(
                    'taxonomy' => 'section',
                    'field'    => 'slug',
                    'terms'    => $section->slug,
                ),
            ),
        );
        // Get codes by section
        $section_codes = new WP_Query( $args );
        array_push( $all_section_codes, $section_codes );
        wp_reset_postdata();
    ?>
        <th><?php echo $section->name; ?></th>
    <?php } ?>
    </tr>
    <?php
    for ( $i = 0 ; $i <= 1 ; $i++ ) {
        echo '<tr>';
        foreach ( $all_section_codes as $section_codes ) {
    ?>
    <td>
        <?php echo $section_codes->posts[$i]->post_title; ?>
    </td>
    <?php
        }
        echo '</tr>';
    }
    ?>
</table>
