<?php

class Client_style_mcp
{
	public function index()
	{
		if ( ! ee('Permission')->isSuperAdmin())
		{
			show_error(lang('unauthorized_access'), 403);
		}

		$site_id = ee()->config->item('site_id');

		// Handle form submission
		if ( ! empty($_POST))
		{
			// Code is stored as entered: XSS cleaning would replace valid
			// CSS/JS (e.g. .innerHTML, document.write) with "[removed]".
			// Only Super Admins can reach this form.
			$data = array(
				'css_url'    => $this->_clean_url(ee()->input->post('css_url') ?? ''),
				'custom_css' => ee()->input->post('custom_css') ?? '',
				'js_url'     => $this->_clean_url(ee()->input->post('js_url') ?? ''),
				'custom_js'  => ee()->input->post('custom_js') ?? '',
			);

			$query = ee()->db->where('site_id', $site_id)->get('client_style_settings');

			if ($query->num_rows() > 0)
			{
				ee()->db->where('site_id', $site_id)->update('client_style_settings', $data);
			}
			else
			{
				$data['site_id'] = $site_id;
				ee()->db->insert('client_style_settings', $data);
			}

			ee('CP/Alert')->makeInline('shared-form')
				->asSuccess()
				->withTitle(lang('settings_saved'))
				->defer();

			ee()->functions->redirect(ee('CP/URL')->make('addons/settings/client_style'));
		}

		// Load current settings
		$settings = ee()->db
			->where('site_id', $site_id)
			->get('client_style_settings')
			->row_array();

		$vars = array(
			'css_url'    => $settings['css_url'] ?? '',
			'custom_css' => $settings['custom_css'] ?? '',
			'js_url'     => $settings['js_url'] ?? '',
			'custom_js'  => $settings['custom_js'] ?? '',
			'base_url'   => ee('CP/URL')->make('addons/settings/client_style'),
			'save_btn_text' => lang('save'),
			'save_btn_text_working' => lang('saving'),
			'cp_page_title' => lang('client_style_module_name'),
		);

		return array(
			'heading' => lang('client_style_module_name'),
			'body'    => ee('View')->make('client_style:settings')->render($vars),
			'breadcrumb' => array(
				ee('CP/URL')->make('addons/settings/client_style')->compile() => lang('client_style_module_name'),
			),
		);
	}

	/**
	 * Only keep plain http(s) URLs without characters that could break out
	 * of the quoted string they are injected into.
	 *
	 * @param  string $url
	 * @return string
	 */
	private function _clean_url($url)
	{
		$url = trim((string) $url);

		return preg_match('#^https?://[^\s"\'()<>\\\\]+$#i', $url) ? $url : '';
	}
}
