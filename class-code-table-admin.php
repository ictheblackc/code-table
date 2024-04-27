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
		add_action( 'init', array( $this, 'add_table_cpt' ) );
		add_action(	'add_meta_boxes', array( $this, 'add_table_cpt_meta' ) );
		add_action( 'save_post', array( $this, 'save_table_meta_box_data' ) );
		add_action( 'admin_menu', array( $this, 'code_custom_admin_menu' ) );
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
	 * Add table custom post type.
	 * 
	 * @since 0.2
	 */
    public function add_table_cpt()
    {
        $labels = array(
            'name'               => 'Таблицы кодов',
            'singular_name'      => 'Таблица кодов',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить новую таблицу кодов',
            'edit_item'          => 'Редактировать таблицу кодов',
            'new_item'           => 'Новая таблица кодов',
            'view_item'          => 'Посмотреть таблицу кодов',
            'search_items'       => 'Найти таблицу кодов',
            'not_found'          => 'Таблиц кодов не найдено',
            'not_found_in_trash' => 'В корзине таблиц кодов не найдено',
            'parent_item_colon'  => '',
            'menu_name'          => 'Таблицы кодов',
        );
        $supports = array(
            'title',
        );
        register_post_type( 'table', array(
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
			'menu_icon'			 => 'dashicons-media-spreadsheet',
        ));
    }
	
	/**
	 * Add meta box to table custom post type.
	 * 
	 * @since 0.2
	 */
	function add_table_cpt_meta()
	{
		add_meta_box(
			'shortcode', // id
			'Шорткод таблицы', // name
			array( $this, 'table_shortcode_meta_callback' ),  // callback
			'table', // post type
			'normal', // context
			'high' // priority
		);
		add_meta_box(
			'section', // id
			'Разделы', // name
			array( $this, 'table_section_meta_callback' ),  // callback
			'table', // post type
			'normal', // context
			'high' // priority
		);
	}
	
	/**
	 * Add callback for shorcode table meta box.
	 * 
	 * @since 0.4
	 */
	function table_shortcode_meta_callback( $post )
	{
		wp_nonce_field( 'table_meta_box_nonce', 'custom_meta_box_nonce' );
		$shortcode =  '[codetable id ="'.$post->ID.'"]';
 		echo '<label>';
 		echo '<p>'.$shortcode.'</p>';
 		echo '</label>';
	}

	/**
	 * Add callback for sections table meta box.
	 * 
	 * @since 0.2
	 */
	function table_section_meta_callback( $post )
	{
		wp_nonce_field( 'table_meta_box_nonce', 'custom_meta_box_nonce' );
		// Get checkbox values.
		$checked_sections = get_post_meta( $post->ID, 'sections', true );
		$sections = get_terms( array(
            'taxonomy' => 'section',
            'hide_empty' => false,
        ) );
		foreach ( $sections as $section ) {
			$is_checked = ( is_array( $checked_sections ) && in_array( $section->name, $checked_sections ) ) ? 'checked' : '';
			echo '<label>';
			echo '<input type="checkbox" name="sections[]" value="' . esc_attr( $section->name ) . '" ' . $is_checked . '> ' . esc_html( $section->name );
			echo '</label><br>';
		}
	}
	
	/**
	 * Add meta box saving.
	 * 
	 * @since 0.2
	 */
	function save_table_meta_box_data( $post_id )
	{
		// Check nonce.
		if ( !isset($_POST['custom_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['custom_meta_box_nonce'], 'table_meta_box_nonce' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// Save or delete values.
		if ( isset( $_POST['sections'] ) ) {
			$sections = (array) $_POST['sections'];
			update_post_meta( $post_id, 'sections', $sections );
		} else {
			delete_post_meta( $post_id, 'sections' );
		}
	}
	
	/**
	 * Add code custom post type.
	 * 
	 * @since 0.2
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
            'menu_name'          => 'Коды',
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
			'menu_icon'			 => 'dashicons-media-code',
        ));
    }
    
    /**
     * Add section custom taxonomy.
     * 
     * @since 0.2
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
	
	/**
	 * 
	 */
	function code_custom_admin_menu() {
		add_menu_page(
			'Импорт кодов', 
			'Импорт кодов', 
			'manage_options', 
			'code_import', 
			array( $this, 'code_import_page' ),
			'dashicons-upload',
			25
		);
	}

	function code_import_page() {
		?>
		<h1>Импорт кодов</h1>
		<form method="post" enctype="multipart/form-data">
			<input type="file" name="code_file" accept=".tsv" required>
			<button type="submit" name="import" class="button button-primary">Импортировать</button>
		</form>
		<?php
		if ( isset($_POST['import'] ) ) {
			if (isset($_FILES['code_file'])) {
				$file = $_FILES['code_file'];
				$file_path = $file['tmp_name'];
				if ($file['type'] != 'text/tab-separated-values') {
					echo '<div class="error"><p>Неправильный формат файла. Пожалуйста, загрузите TSV файл.</p></div>';
					return;
				}
				if (file_exists($file_path)) {
					$handle = fopen($file_path, 'r');
					while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
						for ($i = 0; $i < count($data); $i += 3) {
							$post_title = sanitize_text_field($data[$i]);
							$post_taxonomy = sanitize_text_field($data[$i+1]);
							$post_content = sanitize_text_field($data[$i+2]);
							// Создание записи
							$post_id = wp_insert_post([
								'post_title' => $post_title,
								'post_content' => $post_content,
								'post_status' => 'publish',
								'post_type' => 'code',
							]);
							if ( $post_id ) {
								echo $post_title.'<br>';
							}
							// Назначение термина таксономии
							if (!term_exists($post_taxonomy, 'section')) {
								wp_insert_term($post_taxonomy, 'section');
							}
							wp_set_object_terms($post_id, $post_taxonomy, 'section');
						}
					}
					fclose($handle);
					echo '<div class="updated"><p>Записи успешно импортированы.</p></div>';
				}
			}
		}
	}

}