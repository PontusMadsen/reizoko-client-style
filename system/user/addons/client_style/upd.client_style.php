<?php

class Client_style_upd
{
	public $version = '1.1.0';

	public function install()
	{
		// Register module
		ee('Model')->make('Module', array(
			'module_name'        => 'Client_style',
			'module_version'     => $this->version,
			'has_cp_backend'     => 'y',
			'has_publish_fields' => 'n',
		))->save();

		// Register extension hooks
		ee('Model')->make('Extension', array(
			'class'    => 'Client_style_ext',
			'method'   => 'cp_css_end',
			'hook'     => 'cp_css_end',
			'settings' => serialize(array()),
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y',
		))->save();

		ee('Model')->make('Extension', array(
			'class'    => 'Client_style_ext',
			'method'   => 'cp_js_end',
			'hook'     => 'cp_js_end',
			'settings' => serialize(array()),
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y',
		))->save();

		// Create settings table
		ee()->load->dbforge();
		ee()->dbforge->add_field(array(
			'id'         => array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'site_id'    => array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'default' => 1),
			'css_url'    => array('type' => 'TEXT', 'null' => TRUE),
			'custom_css' => array('type' => 'TEXT', 'null' => TRUE),
			'js_url'     => array('type' => 'TEXT', 'null' => TRUE),
			'custom_js'  => array('type' => 'TEXT', 'null' => TRUE),
		));
		ee()->dbforge->add_key('id', TRUE);
		ee()->dbforge->create_table('client_style_settings');

		// Insert default row
		ee()->db->insert('client_style_settings', array(
			'site_id'    => ee()->config->item('site_id'),
			'css_url'    => '',
			'custom_css' => '',
			'js_url'     => '',
			'custom_js'  => '',
		));

		return TRUE;
	}

	public function update($current = '')
	{
		if (version_compare($current, '1.1.0', '<'))
		{
			// Add JS columns
			ee()->load->dbforge();
			ee()->dbforge->add_column('client_style_settings', array(
				'js_url'    => array('type' => 'TEXT', 'null' => TRUE),
				'custom_js' => array('type' => 'TEXT', 'null' => TRUE),
			));

			// Register cp_js_end hook
			ee('Model')->make('Extension', array(
				'class'    => 'Client_style_ext',
				'method'   => 'cp_js_end',
				'hook'     => 'cp_js_end',
				'settings' => serialize(array()),
				'priority' => 10,
				'version'  => $this->version,
				'enabled'  => 'y',
			))->save();
		}

		return TRUE;
	}

	public function uninstall()
	{
		ee()->db->where('module_name', 'Client_style')->delete('modules');
		ee()->db->where('class', 'Client_style_ext')->delete('extensions');

		ee()->load->dbforge();
		ee()->dbforge->drop_table('client_style_settings');

		return TRUE;
	}
}
