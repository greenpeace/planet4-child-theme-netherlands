<?php

namespace GPNL\Theme;


class AssetHelper
{
	/**
	 * Meta box prefix.
	 *
	 * @var string $DEV_ASSET_PATH
	 */
	private const DEV_ASSET_PATH = 'http://localhost:3003/public/build/';

	public function __construct()
	{
		add_action('wp_enqueue_scripts', [ $this,'enqueue_assets'], 3);
		add_action('enqueue_block_editor_assets', [ $this,'enqueue_admin_assets'], 1);
	}

	public function enqueue_assets(): void
	{
		$this->enqueue_assets_from_entry('bootstrap', ['jquery']);
		$this->enqueue_assets_from_entry('child-theme-main', ['bootstrap'], ['bootstrap', 'parent-style', 'planet4-blocks-style']);
	}

	public function enqueue_admin_assets(): void
	{
		$this->enqueue_assets_from_entry('bootstrap', ['jquery']);
		$this->enqueue_assets_from_entry('child-theme-main', ['bootstrap'], ['bootstrap']);
		$this->enqueue_assets_from_entry('child-theme-editor');
	}


	public function enqueue_assets_from_entry($name, $script_dependencies = [], $style_dependencies = []): void
	{
		// Check to see if there are files in the build directory, If not (in develop mode) the in-memory JS file from webpack is used.
		if ($this->is_dev()) {
			// Runtime.js is required in dev only to run the rest of the scripts.
			wp_enqueue_script('child-theme-runtime', self::DEV_ASSET_PATH . 'runtime.js', [], null, true);
			wp_enqueue_script($name, self::DEV_ASSET_PATH . $name . '.js', $script_dependencies, null, true);
		} else {
			$this->enqueue_prod_assets($name, $script_dependencies, $style_dependencies);
		}
	}

	public function enqueue_prod_assets($name, $script_dependencies, $style_dependencies): void
	{
		$manifest = json_decode(file_get_contents(get_theme_file_path() . BUILD_DIR . 'manifest.json'), true, 512, JSON_THROW_ON_ERROR);
		$script_glob = glob(get_theme_file_path() . BUILD_DIR . $name . '.js');
		$style_glob = glob(get_theme_file_path() . BUILD_DIR . $name . '.css');

		if(!empty($script_glob)) {
			$script_filename = basename($script_glob[0]);
			$script_version = $manifest[$script_filename];
			$script_path = get_stylesheet_directory_uri() . BUILD_DIR . $script_filename;
			wp_enqueue_script($name, $script_path, $script_dependencies, $script_version, true);
		}
		if(!empty($script_glob)) {
			$style_filename = basename($style_glob[0]);
			$style_version = $manifest[$style_filename];
			$style_path = get_stylesheet_directory_uri() . BUILD_DIR . $style_filename;
			wp_enqueue_style($name, $style_path, $style_dependencies, $style_version);
		}
	}

	public function is_dev(): bool
	{
		$file_path = get_theme_file_path();
		return count(glob($file_path . '/public/build/*')) === 0;
	}
}
