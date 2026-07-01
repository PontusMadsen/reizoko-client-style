<?php

class Client_style_upd
{
    public $version = '1.1.0';

    public function install()
    {
        // Register module
        ee('Model')->make('Module', [
            'module_name'    => 'Client_style',
            'module_version' => $this->version,
            'has_cp_backend' => 'y',
            'has_publish_fields' => 'n',
        ])->save();

        // Register extension hooks
        ee('Model')->make('Extension', [
            'class'    => 'Client_style_ext',
            'method'   => 'cp_css_end',
            'hook'     => 'cp_css_end',
            'settings' => serialize([]),
            'priority' => 10,
            'version'  => $this->version,
            'enabled'  => 'y',
        ])->save();

        ee('Model')->make('Extension', [
            'class'    => 'Client_style_ext',
            'method'   => 'cp_js_end',
            'hook'     => 'cp_js_end',
            'settings' => serialize([]),
            'priority' => 10,
            'version'  => $this->version,
            'enabled'  => 'y',
        ])->save();

        // Create settings table
        ee()->load->dbforge();
        ee()->dbforge->add_field([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'site_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'default' => 1],
            'css_url' => ['type' => 'TEXT', 'null' => true],
            'custom_css' => ['type' => 'TEXT', 'null' => true],
            'js_url' => ['type' => 'TEXT', 'null' => true],
            'custom_js' => ['type' => 'TEXT', 'null' => true],
        ]);
        ee()->dbforge->add_key('id', true);
        ee()->dbforge->create_table('client_style_settings');

        // Insert default row for site 1
        ee()->db->insert('client_style_settings', [
            'site_id'    => ee()->config->item('site_id'),
            'css_url'    => '',
            'custom_css' => '',
            'js_url'     => '',
            'custom_js'  => '',
        ]);

        return true;
    }

    public function update($current = '')
    {
        if (version_compare($current, '1.1.0', '<')) {
            // Add JS columns
            ee()->load->dbforge();
            ee()->dbforge->add_column('client_style_settings', [
                'js_url' => ['type' => 'TEXT', 'null' => true],
                'custom_js' => ['type' => 'TEXT', 'null' => true],
            ]);

            // Register cp_js_end hook
            ee('Model')->make('Extension', [
                'class'    => 'Client_style_ext',
                'method'   => 'cp_js_end',
                'hook'     => 'cp_js_end',
                'settings' => serialize([]),
                'priority' => 10,
                'version'  => $this->version,
                'enabled'  => 'y',
            ])->save();
        }

        return true;
    }

    public function uninstall()
    {
        // Remove module
        ee()->db->where('module_name', 'Client_style')->delete('modules');

        // Remove extension
        ee()->db->where('class', 'Client_style_ext')->delete('extensions');

        // Drop settings table
        ee()->load->dbforge();
        ee()->dbforge->drop_table('client_style_settings');

        return true;
    }
}
