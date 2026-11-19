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
		$this->width=!empty($width) ? intval($width) : 80;		
		$this->height=!empty($height) ? intval($height) : 30;
		$this->color=!empty($color) ? $color : 'ff6600';
		$this->bgcolor=!empty($bgcolor) ? $bgcolor : 'ffffff';
		$this->lenght=!empty($lenght) ? intval($lenght) : 6;
		$this->type=!empty($type) ? intval($type) : 3;
		$this->createimg();
	}
	
	private function createimg(){
		$this->bgimg=imagecreatetruecolor($this->width,$this->height);
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
		
		$font=dirname(__FILE__).'/arial.ttf';
		$this->getcode();
		$temp = imagettfbbox(15,0,$font,$this->code); //取得使用 TrueType 字体的文本的范围		
		$markwidth  = $temp[2] - $temp[6];
		$markheight = $temp[3] - $temp[7];
		//imagestring($bgimg,6,10,10,'ABVFD',$color_qg);
		imagettftext($this->bgimg,15,0,3,$markheight+6,$color_qg,$font,$this->code);		
		
	}
	public function image(){	
		header("content-type:image/png\r\n");
		imagepng($this->bgimg);
		imagedestroy($this->bgimg);
	}
	
	public function dian($num=10,$color=null){
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
				$this->code=strtoupper(substr(bin2hex(random_bytes((int) ceil($this->lenght / 2))),0,$this->lenght));
			break;
			case 4:	//汉字,字体太大的问题暂不支持
				$this->code=strtoupper(substr(bin2hex(random_bytes((int) ceil($this->lenght / 2))),0,$this->lenght));
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
    /**
     * @param int $width 宽度,默认为120
     * @param int $height 高度,默认为50
     * @param int $fontSize 字体的大小
     */
    public function arithmeticCode($width=90,$height=50,$fontSize=20){
        //开启session
        session_start();
        //创建画布
        $img = imagecreatetruecolor($width,$height);
        //分配颜色
        $color = imagecolorallocate($img,255,255,255);
        //填充颜色
        imagefill($img,0,0,$color);

        //干扰点
        for ($i = 0;$i < 500;$i++){
            $pixColor = imagecolorallocate($img,mt_rand(100,200),mt_rand(100,200),mt_rand(100,200));
            imagesetpixel($img,mt_rand(0,$width),mt_rand(0,$height),$pixColor);
        }
        //干扰线
        for ($i = 0;$i < 4;$i++){
            $lineColor = imagecolorallocate($img,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120));
            imageline($img,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),$lineColor);
        }

        //定义一个数组存放运算符号
        $arr = ['+','-','*'];
        //计算数组的长度
        $len = count($arr);
        //定义一个1到20的数组
        $num = range(1,10);
        $numLen = count($num);
        //定义一个空数组来存放随机取得的验证码
        $code = [];
        for ($i = 0;$i < $len;$i++) {
            if ($i == 1) {
                $code[] = $arr[mt_rand(0,$len-1)];
            }else {
                $code[] = $num[mt_rand(0,$numLen-1)];
            }
        }

        $str = implode($code);//将数组转为字符串
        $textColor = imagecolorallocate($img,mt_rand(100,200),mt_rand(100,200),mt_rand(100,200));
        $fontAngle = 0;
        $x = ($width - $fontSize*3)/2;
        $y = ($height - $fontSize) / 2 + $fontSize;
        imagettftext($img,$fontSize,$fontAngle,$x,$y,$textColor,dirname(__FILE__).'/arial.ttf',$str);

        $res = $this->getRes($code);

        //将函数存放在session中
        $_SESSION['admin_arithmetic_code'] = (string) $res;

        //输出图片
        header("content-type:image/png");
        imagepng($img);
    }
    public function getRes($arr) {
        $sum = 0;
        //判断数组元素下标为1的运算符号是什么
        switch ($arr[1]){
            case '+':
                $sum = $arr[0] + $arr[2];
                break;
            case '-':
                $sum = $arr[0] - $arr[2];
                break;
            case '*':
                $sum = $arr[0] * $arr[2];
                break;
        }
        return $sum;
    }
}
?>