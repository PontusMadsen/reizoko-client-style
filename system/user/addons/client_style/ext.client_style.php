<?php

class Client_style_ext
{
	public $version = '1.1.0';

	/**
	 * Get settings for current site
	 *
	 * @return object|null
	 */
	private function _get_settings()
	{
		$site_id = ee()->config->item('site_id');

		return ee()->db
			->where('site_id', $site_id)
			->get('client_style_settings')
			->row();
	}

	/**
	 * Hook: cp_css_end
	 *
	 * Injects custom CSS into the control panel.
	 * Linked CSS file loads first, then inline CSS (so inline can override).
	 *
	 * @return string
	 */
	public function cp_css_end()
	{
		$css = ee()->extensions->last_call ?: '';
		$settings = $this->_get_settings();

		if ( ! $settings)
		{
			return $css;
		}

		if ( ! empty($settings->css_url))
		{
			$url = htmlspecialchars($settings->css_url, ENT_QUOTES, 'UTF-8');
			$css .= "\n@import url('" . $url . "');\n";
		}

		if ( ! empty($settings->custom_css))
		{
			$css .= "\n" . $settings->custom_css . "\n";
		}

		return $css;
	}

	/**
	 * Hook: cp_js_end
	 *
	 * Injects custom JavaScript into the control panel.
	 * Linked JS file loads first, then inline JS.
	 *
	 * @return string
	 */
	public function cp_js_end()
	{
		$js = ee()->extensions->last_call ?: '';
		$settings = $this->_get_settings();

		if ( ! $settings)
		{
			return $js;
		}

		if ( ! empty($settings->js_url))
		{
			$url = trim($settings->js_url);

			// Only allow http/https URLs
			if (preg_match('#^https?://#i', $url))
			{
				$contents = @file_get_contents($url);

				if ($contents !== FALSE)
				{
					$js .= "\n" . $contents . "\n";
				}
			}
		}

		if ( ! empty($settings->custom_js))
		{
			$js .= "\n" . $settings->custom_js . "\n";
		}

		return $js;
	}
}
