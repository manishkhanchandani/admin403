<?php
class encryptstring {
	public $ky;
	public $str;
	
	public function convert() {
		if($this->ky == '')
			return $this->str;
		$this->ky = str_replace(chr(32), '', $this->ky);
		if(strlen($this->ky)<8)
			exit('key error');
		$kl = strlen($this->ky)<32 ? strlen($this->ky) : 32;
		$k = array();
		for($i=0;$i<$kl;$i++){
			$k[$i]=ord($this->ky{$i})&0x1F;
		}
		$j=0;
		for($i=0;$i<strlen($this->str);$i++){
			$e=ord($this->str{$i});
			$this->str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);
			$j++;
			$j=$j==$kl?0:$j;
		}
		return $this->str;
	}
}
/* how to use it
$enc = new encryptstring;
$enc->ky = 'thisiskey';
$enc->str = 'my name is manish';
$string = $enc->convert();
echo $string;
echo "<br>";
$enc->str = $string;
$string2 = $enc->convert();
echo $string2;
echo "<br>";
*/
?>