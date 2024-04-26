<?php
$table = get_post( $atts['id'] );
$table_meta = get_post_meta($table->ID);
$table_sections = unserialize( $table_meta['sections'][0] ); 
$sections = array();
foreach ( $table_sections as $table_section ) {
	$table_section = get_term_by( 'name', $table_section, 'section' );
	array_push( $sections, $table_section );
}
?>
<table id="<?php echo $atts['id']; ?>" class="codetable">
    <tr class="codetable__header">
    <?php
	$i = 0;
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
        <th>
			<?php echo $section->name; ?>
			<img src="<?php echo CT_PLUGIN_DIR_URL.'assets/img/down.svg'; ?>" class="dropdown-arrow" data-col="<?php echo $i; ?>" style="width:24px;margin-bottom:-6px;margin-left: 12px;}">
		</th>
    <?php
		$i++;
	}
	?>
    </tr>
	<div id="dropdown" style="display:none;position:absolute;"></div>
    <?php
    for ( $i = 0 ; $i <= 3 ; $i++ ) {
        echo '<tr class="codetable__row">';
        foreach ( $all_section_codes as $section_codes ) {
			$post_content = $section_codes->posts[$i]->post_content;
			if ( $post_content == '' ) $post_content = '#';
			$post_title = $section_codes->posts[$i]->post_title;
    ?>
    <td>
        <a href="<?php echo $post_content; ?>" target="_blank"><?php echo $post_title; ?></a>
    </td>
    <?php
        }
        echo '</tr>';
    }
    ?>
</table>
<style>
	.codetable {
		overflow-x: auto;
		display: block;
	}
	.codetable tbody {
		display: table;
		width: 100%;
	}
	.dropdown-arrow {
		cursor: pointer;
		user-select: none;
	}
	.filter-list {
		display: none;
		position: absolute;
		background: white;
		border: 1px solid #ccc;
		z-index: 100;
		list-style: none;
		padding: 5px;
	}
	ul.filter-list > li[data-active="true"] {
		background: blanchedalmond;
	}
	.filter-list.active {
		display: block;
	}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.dropdown-arrow').forEach(icon => {
		icon.addEventListener('click', function() {
			let column = this.getAttribute('data-col');
			let existingList = document.querySelector('.filter-list[data-col="' + column + '"]');
			if (existingList) {
				existingList.classList.toggle('active');
			} else {
				let values = new Set();
				document.querySelectorAll('.codetable tbody tr.codetable__row').forEach(row => {
					values.add(row.cells[column].textContent);
				});
				let list = document.createElement('ul');
				list.className = 'filter-list active';
				list.setAttribute('data-col', column);
				values.forEach(value => {
					let item = document.createElement('li');
					item.textContent = value;
					item.dataset.active = 'false'; // Инициализируем флаг активности фильтра
					item.addEventListener('click', (event) => {
						event.stopPropagation(); // Остановка всплывания для предотвращения закрытия списка
						toggleFilter(column, value, item);
					});
					list.appendChild(item);
				});
				document.body.appendChild(list);
				let rect = this.getBoundingClientRect();
				list.style.top = rect.bottom + 215 + 'px';
				list.style.left = rect.left - 100 + 'px';
			}
		});
	});
	function toggleFilter(column, value, item) {
		item.dataset.active = item.dataset.active === 'false' ? 'true' : 'false'; // Переключаем флаг
		updateTable();
	}
	function updateTable() {
		document.querySelectorAll('.codetable tbody tr.codetable__row').forEach(row => {
			let showRow = true;
			document.querySelectorAll('.filter-list li').forEach(li => {
				let column = li.parentElement.getAttribute('data-col');
				let isActive = li.dataset.active === 'true';
				let cellValue = row.cells[column].textContent;
				if (isActive && cellValue !== li.textContent) {
					showRow = false;
				}
			});
			row.style.display = showRow ? '' : 'none';
		});
	}
});
</script>
