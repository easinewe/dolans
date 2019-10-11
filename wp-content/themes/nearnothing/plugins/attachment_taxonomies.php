<?php
/**
 * Plugin Name: Attachment Taxonomies
 * Plugin URI:  
 * Text Domain: attachment_taxonomies
 * Domain Path: /languages
 * Description: 
 * Version:     1.0.0
 * Author:      Frank Bültge
 * Author URI:  http://bueltge.de
 * License:     GPLv3
 */

if ( function_exists( 'add_filter' ) )
    add_action( 'plugins_loaded', array( 'Fb_Attachment_Taxonomies', 'get_object' ) );
/**
 * Add Tags and Categories taxonmies to Attachment with WP 3.5
 */
class Fb_Attachment_Taxonomies {

    static private $classobj;

    /**
     * Constructor, init the functions inside WP
     *
     * @since   1.0.0
     * @return  void
     */
    public function __construct() {

        // load translation files
        add_action( 'admin_init', array( $this, 'localize_plugin' ) );
        // add taxonmies
        add_action( 'init', array( $this, 'setup_taxonomies' ) );
    }

    /**
     * Handler for the action 'init'. Instantiates this class.
     *
     * @since   1.0.0
     * @access  public
     * @return  $classobj
     */
    public function get_object() {

        if ( NULL === self::$classobj ) {
            self::$classobj = new self;
        }

        return self::$classobj;
    }

    /**
     * Localize plugin function.
     *
     * @uses    load_plugin_textdomain, plugin_basename
     * @since   2.0.0
     * @return  void
     */
    public function localize_plugin() {

        load_plugin_textdomain(
            'attachment_taxonomies',
            FALSE,
            dirname( plugin_basename( __FILE__ ) ) . '/languages/'
        );
    }

    /**
     * Setup Taxonomies
     * Creates 'attachment_tag' and 'attachment_category' taxonomies.
     * Enhance via filter `fb_attachment_taxonomies`
     * 
     * @uses    register_taxonomy, apply_filters
     * @since   1.0.0
     * @return  void
     */
    public function setup_taxonomies() {

        $attachment_taxonomies = array();

        // Tags
        $labels = array(
            'name'              => _x( 'Media Tags', 'taxonomy general name', 'attachment_taxonomies' ),
            'singular_name'     => _x( 'Media Tag', 'taxonomy singular name', 'attachment_taxonomies' ),
            'search_items'      => __( 'Search Media Tags', 'attachment_taxonomies' ),
            'all_items'         => __( 'All Media Tags', 'attachment_taxonomies' ),
            'parent_item'       => __( 'Parent Media Tag', 'attachment_taxonomies' ),
            'parent_item_colon' => __( 'Parent Media Tag:', 'attachment_taxonomies' ),
            'edit_item'         => __( 'Edit Media Tag', 'attachment_taxonomies' ), 
            'update_item'       => __( 'Update Media Tag', 'attachment_taxonomies' ),
            'add_new_item'      => __( 'Add New Media Tag', 'attachment_taxonomies' ),
            'new_item_name'     => __( 'New Media Tag Name', 'attachment_taxonomies' ),
            'menu_name'         => __( 'Media Tags', 'attachment_taxonomies' ),
        );

        $args = array(
            'hierarchical' => FALSE,
            'labels'       => $labels,
            'show_ui'      => TRUE,
            'show_admin_column' => TRUE,
            'query_var'    => TRUE,
            'rewrite'      => TRUE,
        );

        $attachment_taxonomies[] = array(
            'taxonomy'  => 'attachment_tag',
            'post_type' => 'attachment',
            'args'      => $args
        );

        // Categories
        $labels = array(
            'name'              => _x( 'Media Categories', 'taxonomy general name', 'attachment_taxonomies' ),
            'singular_name'     => _x( 'Media Category', 'taxonomy singular name', 'attachment_taxonomies' ),
            'search_items'      => __( 'Search Media Categories', 'attachment_taxonomies' ),
            'all_items'         => __( 'All Media Categories', 'attachment_taxonomies' ),
            'parent_item'       => __( 'Parent Media Category', 'attachment_taxonomies' ),
            'parent_item_colon' => __( 'Parent Media Category:', 'attachment_taxonomies' ),
            'edit_item'         => __( 'Edit Media Category', 'attachment_taxonomies' ), 
            'update_item'       => __( 'Update Media Category', 'attachment_taxonomies' ),
            'add_new_item'      => __( 'Add New Media Category', 'attachment_taxonomies' ),
            'new_item_name'     => __( 'New Media Category Name', 'attachment_taxonomies' ),
            'menu_name'         => __( 'Media Categories', 'attachment_taxonomies' ),
        );

        $args = array(
            'hierarchical' => TRUE,
            'labels'       => $labels,
            'show_ui'      => TRUE,
            'query_var'    => TRUE,
            'rewrite'      => TRUE,
        );

        $attachment_taxonomies[] = array(
            'taxonomy'  => 'attachment_category',
            'post_type' => 'attachment',
            'args'      => $args
        );

        $attachment_taxonomies = apply_filters( 'fb_attachment_taxonomies', $attachment_taxonomies );

        foreach ( $attachment_taxonomies as $attachment_taxonomy ) {
            register_taxonomy(
                $attachment_taxonomy['taxonomy'],
                $attachment_taxonomy['post_type'],
                $attachment_taxonomy['args']
            );
        }

    }

} // end class