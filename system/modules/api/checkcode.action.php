<?php
defined('G_IN_SYSTEM')or exit("no");

class checkcode extends SystemAction {

	/** 出错时输出 1x1 透明 PNG，避免 500 */
	private function outputMinimalPng() {
		if (ob_get_level()) { @ob_end_clean(); }
		header("Content-Type: image/png");
		header("Cache-Control: no-cache, must-revalidate");
		echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKMIQQAAAABJRU5ErkJggg==');
		exit;
	}

	public function image(){
		@error_reporting(0);
		@ini_set('display_errors', '0');
		try {
			$style = $this->segment(4);
			if ($style === false || $style === null || $style === '') $style = '80_27';
			$style = is_string($style) ? explode("_", trim($style)) : array('80','27');

			$width  = isset($style[0]) ? max(1, intval($style[0])) : 80;
			$height = isset($style[1]) ? max(1, intval($style[1])) : 27;
			$color  = isset($style[2]) ? $style[2] : '';
			$bgcolor= isset($style[3]) ? $style[3] : '';
			$lenght = isset($style[4]) ? intval($style[4]) : '';
			$type   = isset($style[5]) ? intval($style[5]) : '';

			if (!function_exists('imagecreatetruecolor') || !function_exists('imagepng')) {
				$this->outputMinimalPng();
			}

			$checkcode = System::load_app_class("checkcodeimg");
			$checkcode->config($width, $height, $color, $bgcolor, $lenght, $type);
			$checkcode->dian(100, $color);

			if (function_exists('_setcookie')) {
				_setcookie("checkcode", md5(strtolower($checkcode->code)));
			}
			$checkcode->image();
		} catch (Throwable $e) {
			$this->outputMinimalPng();
		}
	}
}

?>