<?php

class Custom_Pies_Plugin {

    public function run() {


        $this->register_post_type();
        $this->register_shortcodes();
        $this->enqueue_styles();
    }

    // Register the "pies" custom post type
    public function register_post_type() {
        add_action('init', function() {
            $labels = array(
                'name'               => _x( 'Pies', 'post type general name', 'custom-pies-plugin' ),
                'singular_name'      => _x( 'Pie', 'post type singular name', 'custom-pies-plugin' ),
                'menu_name'          => _x( 'Pies', 'admin menu', 'custom-pies-plugin' ),
                'name_admin_bar'     => _x( 'Pie', 'add new on admin bar', 'custom-pies-plugin' ),
                'add_new'            => _x( 'Add New', 'pie', 'custom-pies-plugin' ),
                'add_new_item'       => __( 'Add New Pie', 'custom-pies-plugin' ),
                'new_item'           => __( 'New Pie', 'custom-pies-plugin' ),
                'edit_item'          => __( 'Edit Pie', 'custom-pies-plugin' ),
                'view_item'          => __( 'View Pie', 'custom-pies-plugin' ),
                'all_items'          => __( 'All Pies', 'custom-pies-plugin' ),
                'search_items'       => __( 'Search Pies', 'custom-pies-plugin' ),
                'parent_item_colon'  => __( 'Parent Pies:', 'custom-pies-plugin' ),
                'not_found'          => __( 'No pies found.', 'custom-pies-plugin' ),
                'not_found_in_trash' => __( 'No pies found in Trash.', 'custom-pies-plugin' )
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'pies' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array( 'title', 'editor', 'custom-fields' )
            );

            register_post_type( 'pies', $args );
        });
    }

    // Register shortcodes
    public function register_shortcodes() {
        add_shortcode('pies', array($this, 'display_pies'));
    }

    // Shortcode handler function
    public function display_pies($atts) {
        // Set default attributes and merge with user-defined ones
        $atts = shortcode_atts(array(
            'lookup' => '',
            'ingredients' => '',
            'posts_per_page' => 5 // Default pagination setting
        ), $atts);
    
        // Get the current page number for pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
        // Build the query arguments
        $args = array(
            'post_type' => 'pies',
            'posts_per_page' => $atts['posts_per_page'],
            'paged' => $paged, // Pass the current page number
        );
    
        // Add lookup filtering if specified
        if (!empty($atts['lookup'])) {
            $args['meta_query'][] = array(
                'key' => 'pie_type',
                'value' => $atts['lookup'],
                'compare' => 'LIKE'
            );
        }
    
        // Add ingredients filtering if specified
        if (!empty($atts['ingredients'])) {
            $args['meta_query'][] = array(
                'key' => 'ingredients',
                'value' => $atts['ingredients'],
                'compare' => 'LIKE'
            );
        }
    
        // Execute the query
        $query = new WP_Query($args);
    
        // Start output buffering
        ob_start();
    
        // Check if there are pies to display
        if ($query->have_posts()) {
            echo '<div class="pies-list">';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<div class="pie-item">';
                echo '<h3>' . get_the_title() . '</h3>';
                echo '<div>' . get_the_content() . '</div>';
                echo '</div>';
            }
            echo '</div>';
    
            // Pagination
            echo paginate_links(array(
                'total' => $query->max_num_pages,
                'current' => $paged,
                'format' => '?paged=%#%',
                'prev_text' => __('&laquo; Previous'),
                'next_text' => __('Next &raquo;'),
            ));
        } else {
            echo '<p>No pies found.</p>';
        }
    
        // Reset post data
        wp_reset_postdata();
    
        // Return the buffered content
        return ob_get_clean();
    }
    

    // Enqueue styles
    public function enqueue_styles() {
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_style('custom-pies-style', plugin_dir_url(__FILE__) . '../assets/custom-pies-styles.css');
        });
    }
    
}
