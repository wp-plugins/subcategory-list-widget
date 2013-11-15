<?php

/**
 *
 * @author Flynsarmy
 *
 */
class FlynCW_SubcatWidget extends WP_Widget
{
	public $default_values = array(
		'category_id' => 0,
		'depth' => 1,
		'hide_empty' => 1,
		'show_counts' => 0,
		'exclude' => array(),
	);

	/**
	 * Widget settings
	 *
	 * Simply use the following field examples to create the WordPress Widget options that
	 * will display to administrators. These options can then be found in the $params
	 * variable within the widget method.
	 *
	 *
	 *	array(
	 *		'name' => 'Title',
	 *		'desc' => '',
	 *		'id' => 'title',
	 *		'type' => 'text',
	 *		'std' => 'Your widgets title'
	 *	),
	 *	array(
	 *		'name' => 'Textarea',
	 *		'desc' => 'Enter big text here',
	 *		'id' => 'textarea_id',
	 *		'type' => 'textarea',
	 *		'std' => 'Default value 2'
	 *	),
	 *	array(
	 *	    'name'    => 'Select box',
	 *		'desc' => '',
	 *	    'id'      => 'select_id',
	 *	    'type'    => 'select',
	 *	    'options' => array( 'KEY1' => 'Value 1', 'KEY2' => 'Value 2', 'KEY3' => 'Value 3' )
	 *	),
	 *	array(
	 *		'name' => 'Radio',
	 *		'desc' => '',
	 *		'id' => 'radio_id',
	 *		'type' => 'radio',
	 *		'options' => array(
	 *			array('name' => 'Name 1', 'value' => 'Value 1'),
	 *			array('name' => 'Name 2', 'value' => 'Value 2')
	 *		)
	 *	),
	 *	array(
	 *		'name' => 'Checkbox',
	 *		'desc' => '',
	 *		'id' => 'checkbox_id',
	 *		'type' => 'checkbox'
	 *	),
	 */
	protected $widget = array(
		'name' => 'FlynCW Subcat List',

		// this description will display within the administrative widgets area
		// when a user is deciding which widget to use.
		'description' => 'Displays a HTML list of subcategories of a given category',

		// determines whether or not to use the sidebar _before and _after html
		'do_wrapper' => true,

		// string : if you set a filename here, it will be loaded as the view
		// when using a file the following array will be given to the file :
		// array('widget'=>array(),'params'=>array(),'sidebar'=>array(),
		// alternatively, you can return an html string here that will be used
		'view' => 'views/subcat-widget/frontend/widget.php',
	);

	/**
	 * Constructor
	 *
	 * Registers the widget details with the parent class, based off of the options
	 * that were defined within the widget property. This method does not need to be
	 * changed.
	 */
	function __construct()
	{
		//Initializing
		$classname = sanitize_title(get_class($this));

		$categories = get_categories();

		$this->widget['fields'] = array(
			// You should always offer a widget title
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' => 'Show subcategories of',
				'desc' => '',
				'id' => 'category_id',
				'type' => 'wp_dropdown_categories',
				'options' => array(
					'show_option_all' => 'Top Level',
					'hide_empty' => 0,
					'hierarchical' => 1,
				),
				'std' => ''
			),
			array(
				'name' => 'Depth',
				'desc' => 'How many levels below this one to display',
				'id' => 'depth',
				'type' => "select",
				'options' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5),
			),
			array(
				'name' => 'Show counts?',
				'desc' => 'Show number of posts in each category?',
				'id' => 'show_counts',
				'type' => "checkbox",
			),
			array(
				'name' => 'Hide empty subcategories?',
				'id' => 'hide_empty',
				'type' => "checkbox",
			),
			array(
				'name' => 'Exclude categories',
				'desc' => "Check the categories you don't want to display",
				'id' => 'exclude',
				'type' => "wp_category_checklist",
				'options' => array(

				),
				'std' => ''
			)
		);

		// widget actual processes
		parent::WP_Widget(
			$id = $classname,
			$name = (isset($this->widget['name'])?$this->widget['name']:$classname),
			$options = array( 'description'=>$this->widget['description'] )
		);
	}

	public function set_do_wrapper( $yes_or_no )
	{
		$this->widget['do_wrapper'] = !!$yes_or_no;
	}

	/**
	 * Widget View
	 *
	 * This method determines what view method is being used and gives that view
	 * method the proper parameters to operate. This method does not need to be
	 * changed.
	 *
	 * @param array $sidebar
	 * @param array $params
	 */
	function widget($sidebar, $params)
	{
		$params = wp_parse_args($params, $this->default_values);

		//initializing variables
		$title = apply_filters( get_class($this).'_title', $params['title'] );
		$do_wrapper = (!isset($this->widget['do_wrapper']) || $this->widget['do_wrapper']);

		if ( $do_wrapper )
			echo $sidebar['before_widget'];

		echo $sidebar['before_title'] . htmlspecialchars($title) . $sidebar['after_title'];

		//loading a file that is isolated from other variables
		if (file_exists(dirname(__FILE__).'/'.$this->widget['view']))
			$this->getViewFile($this->widget, $params, $sidebar);

		if ( $do_wrapper )
			echo $sidebar['after_widget'];
	}

	/**
	 * Get the View file
	 *
	 * Isolates the view file from the other variables and loads the view file,
	 * giving it the three parameters that are needed. This method does not
	 * need to be changed.
	 *
	 * @param array $widget
	 * @param array $params
	 * @param array $sidebar
	 */
	function getViewFile($widget, $params, $sidebar) {
		require dirname(__FILE__).'/'.$this->widget['view'];
	}

	/**
	 * Administration Form
	 *
	 * This method is called from within the wp-admin/widgets area when this
	 * widget is placed into a sidebar. The resulting is a widget options form
	 * that allows the administration to modify how the widget operates.
	 *
	 * You do not need to adjust this method what-so-ever, it will parse the array
	 * parameters given to it from the protected widget property of this class.
	 *
	 * @param array $instance
	 * @return boolean
	 */
	function form($instance)
	{
		//reasons to fail
		if (empty($this->widget['fields'])) return false;

		$instance = wp_parse_args($instance, $this->default_values);

		$defaults = array(
			'id' => '',
			'name' => '',
			'desc' => '',
			'type' => '',
			'options' => '',
			'std' => '',
		);

		do_action(get_class($this).'_before');
		foreach ($this->widget['fields'] as $field)
		{
			//making sure we don't throw strict errors
			$field = wp_parse_args($field, $defaults);

			$meta = false;
			if (isset($field['id']) && array_key_exists($field['id'], $instance))
				@$meta = is_array($instance[$field['id']]) ? $instance[$field['id']] : attribute_escape($instance[$field['id']]);

			if ( !in_array($field['type'], array('custom', 'wp_category_checklist')) )
			{
				echo '<p><label for="',$this->get_field_id($field['id']),'">';
			}
			if (isset($field['name']) && $field['name']) echo $field['name'],':';

			switch ($field['type'])
			{
				case 'text':
					echo '<input type="text" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" value="', ($meta ? $meta : @$field['std']), '" class="vibe_text" />',
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'textarea':
					echo '<textarea class="vibe_textarea" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" cols="60" rows="4" style="width:97%">', $meta ? $meta : @$field['std'], '</textarea>',
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'select':
					echo '<select class="vibe_select" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '">';

					foreach ($field['options'] as $value => $option)
					{
						$selected_option = ( $value ) ? $value : $option;
						echo '<option', ($value ? ' value="' . $value . '"' : ''), selected($meta, $selected_option, false), '>', $option, '</option>';
					}

					echo '</select>',
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'radio':
					foreach ($field['options'] as $option)
					{
						echo '<input class="vibe_radio" type="radio" name="', $this->get_field_name($field['id']), '" value="', $option['value'], '"', checked($meta, $option['value'], false), ' />',
						$option['name'];
					}
					echo '<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'checkbox':
					echo '<input type="hidden" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" value="0" /> ',
						 '<input class="vibe_checkbox" type="checkbox" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" value="1" ', checked(!empty($meta), true, false), ' /> ',
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'wp_dropdown_categories':
					$field['options'] = array_merge((array)@$field['options'], array(
						'selected' => intval($meta),
						'name' => $this->get_field_name($field['id']),
						'id' => $field['id'],
					));
					$field['options']['selected'] = intval($meta);
					wp_dropdown_categories( $field['options'] );
					break;
				case 'wp_category_checklist':
					require_once __DIR__.'/includes/walker_named_category_checklist.php';
					$walker = new Walker_Named_Category_Checklist(array(
						'name' => $this->get_field_name($field['id']),
					));

					?>
					<style>
						.wp_category_checklist {overflow:auto;max-height:200px}
						.wp_category_checklist ul ul {margin-left:15px;}
					</style>

					<!--
						This hidden field is required so that when no checklist
						checkboxes below are selected, a dummy will be used and
						it won't just ignore the result
					-->
					<input type="hidden" name="<?= $this->get_field_name($field['id']) ?>[]" value="-1" />

					<div class="wp_category_checklist">
						<span class="description"><?= @$field['desc'] ?></span>'
						<ul>
							<?php wp_category_checklist(0, 0, (array)$meta, false, $walker, false); ?>
						</ul>
					</div>
					<?php
				case 'custom':
					echo $field['std'];
					break;
			}

			if ( !in_array($field['type'], array('custom', 'wp_category_checklist')) )
			{
				echo '</label></p>';
			}
		}
		do_action(get_class($this).'_after');
		return true;
	}

	/**
	 * Update the Administrative parameters
	 *
	 * This function will merge any posted paramters with that of the saved
	 * parameters. This ensures that the widget options never get lost. This
	 * method does not need to be changed.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update($new_instance, $old_instance)
	{
		// processes widget options to be saved
		$instance = wp_parse_args($new_instance, $old_instance);

		return $instance;
	}
}
