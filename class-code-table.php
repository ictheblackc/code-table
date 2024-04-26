<?php
class CodeTable
{
    public $id;
    
    /**
	 * Constructor.
	 *
	 * @since 0.1
	 */
    public function __construct()
    {
        $this->id = null;
        add_action( 'init', array( $this, 'init' ) );
    }
    
    /**
	 * Register settings, add filters and hooks.
	 *
	 * @since 0.1
	 */
	public function init()
	{
	    add_shortcode( 'codetable', array( $this, 'add_codetable_shortcode' ) );
	}
	
	/**
     * Add "codetable" shortcode.
     * 
     * @since 0.1
     */
    public function add_codetable_shortcode( $atts )
    {
		$atts = shortcode_atts(
			array(
				'id' => 1,
			),
			$atts,
			'codetable',
		);
        $sections = get_terms( array(
            'taxonomy' => 'section',
            'hide_empty' => false,
        ) );
    	require_once( CT_PLUGIN_DIR . 'templates/global/shortcode.php' );
    }
}
