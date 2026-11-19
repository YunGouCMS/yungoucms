<?php 

class checkcodeimg {
	
	private $width;
	private $height;
	private $color;
	private $bgcolor;
	private $lenght;
	private $type;		//1 数字, 2 字母,  3 混合, 4 汉字
	public $code;
	private $bgimg;
	
	public function config($width,$height,$color,$bgcolor,$lenght,$type){
		$this->width  = max(1, !empty($width)  ? intval($width)  : 80);
		$this->height = max(1, !empty($height) ? intval($height) : 30);
		$this->color  = !empty($color)  ? $color : 'ff6600';
		$this->bgcolor= !empty($bgcolor)? $bgcolor : 'ffffff';
		$this->lenght = !empty($lenght) ? intval($lenght) : 6;
		$this->type   = !empty($type)   ? intval($type) : 3;
		$this->createimg();
	}
	
	private function createimg(){
		$this->bgimg = @imagecreatetruecolor($this->width, $this->height);
		if (!$this->bgimg) throw new \Exception('GD image create failed');
		$R = hexdec(substr($this->bgcolor, 0, 2));
		$G = hexdec(substr($this->bgcolor, 2, 2));
		$B = hexdec(substr($this->bgcolor, 4));	
		$color_bg=imagecolorallocate($this->bgimg,$R,$G,$B);	
		
		$R = hexdec(substr($this->color, 0, 2));
		$G = hexdec(substr($this->color, 2, 2));
		$B = hexdec(substr($this->color,4));
		$color_qg=imagecolorallocate($this->bgimg,$R,$G,$B);		
		
		//填充图片
		imagefilledrectangle($this->bgimg,$this->width,$this->height,0,0,$color_bg);
		
		$this->getcode();
		$font = dirname(__FILE__).DIRECTORY_SEPARATOR.'arial.ttf';
		if (file_exists($font)) {
			$temp = imagettfbbox(15,0,$font,$this->code);
			$markheight = $temp[3] - $temp[7];
			imagettftext($this->bgimg,15,0,3,$markheight+6,$color_qg,$font,$this->code);
		} else {
			// 无 ttf 时使用内置字体，避免 500
			imagestring($this->bgimg, 5, (int)($this->width/2 - strlen($this->code)*4), (int)($this->height/2 - 8), $this->code, $color_qg);
		}
	}
	public function image(){	
		if (!$this->bgimg || (!is_resource($this->bgimg) && !is_object($this->bgimg))) return;
		if (ob_get_level()) { @ob_end_clean(); }
		header("Content-Type: image/png");
		header("Cache-Control: no-cache, must-revalidate");
		imagepng($this->bgimg);
		imagedestroy($this->bgimg);
		exit;
	}
	
	public function dian($num=10,$color){
		if (!$this->bgimg || (!is_resource($this->bgimg) && !is_object($this->bgimg))) return;
		if(empty($color))$color=$this->color;
		$R = hexdec(substr($color, 0, 2));
		$G = hexdec(substr($color, 2, 2));
		$B = hexdec(substr($color,4));
		$color=imagecolorallocate($this->bgimg,$R,$G,$B);
		for($i=0;$i<$num;$i++){
			imagesetpixel($this->bgimg,rand(0,$this->width),rand(0,$this->height),$color);
		}
	}
	private function getcode(){
		switch($this->type){
			case 1:	//数字
				$this->code='';
				for($i=0;$i<$this->lenght;$i++){
					$this->code.=rand(0,9);
				}			
			break;
			case 2:	//字母
				$this->code='';
				$str='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				for($i=0;$i<$this->lenght;$i++){
					$this->code.=$str[rand(0,9)];
				}
			break;
			case 3:	//数字与字母
				$this->code=substr(md5(time()),0,$this->lenght);
				$this->code=strtoupper($this->code);
			break;
			case 4:	//汉字,字体太大的问题暂不支持
				$this->code=substr(md5(time()),0,$this->lenght);
				$this->code=strtoupper($this->code);
			break;
			default://默认
				$this->code='';
				$str='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				for($i=0;$i<$this->lenght;$i++){
					$this->code.=$str[rand(0,9)];
				}
			break;
		}
		
		return $this->code;
	
	}
}

?>