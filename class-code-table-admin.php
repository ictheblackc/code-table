<?php
class CodeTableAmin
{
    /**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct()
	{
	    add_action( 'admin_init', array( $this, 'init' ) );
	    add_action( 'init', array( $this, 'add_code_cpt' ) );
	    add_action( 'init', array( $this, 'add_section_tax' ) );
	}
	
    /**
	 * Registers settings, add filters and hooks.
	 *
	 * @since 0.1
	 */
	public function init()
	{
	    return;
	}
	
	/**
	 * Add code custom post type.
	 */
    public function add_code_cpt()
    {
        $labels = array(
            'name'               => 'Коды',
            'singular_name'      => 'Код',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить новый код',
            'edit_item'          => 'Редактировать код',
            'new_item'           => 'Новый код',
            'view_item'          => 'Посмотреть код',
            'search_items'       => 'Найти код',
            'not_found'          => 'Кодов не найдено',
            'not_found_in_trash' => 'В корзине кодов не найдено',
            'parent_item_colon'  => '',
            'menu_name'          => 'Коды'
        );
        $supports = array(
            'title',
            'editor',
        );
        register_post_type( 'code', array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
        ));
    }
    
    /**
     * Add section custom taxonomy.
     */
    public function add_section_tax()
    {
        $labels = array(
    		'name'              => 'Разделы',
    		'singular_name'     => 'Раздел',
    		'search_items'      => 'Искать разделы',
    		'all_items'         => 'Все разделы',
    		'view_item '        => 'Смотреть раздел',
    		'edit_item'         => 'Редактировать раздел',
    		'update_item'       => 'Обновить раздел',
    		'add_new_item'      => 'Добавить новый раздел',
    		'new_item_name'     => 'Новое название раздела',
    		'menu_name'         => 'Разделы',
    		'back_to_items'     => '← Назад к разделам',
    	);
	    register_taxonomy( 'section', 'code', array(
        	'labels'               => $labels,
        	'hierarchical'         => true,
    	));
    }
}