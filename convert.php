<?php
	$encode_value = 1 ;/// 0 means no enoding , php code will be visible in binary
	if($argc < 3)
	{
		echo "usage : convert <file.php> <out.h>\n";
		return;
	}
	if(!endswith($argv[1],".php"))
	{
		echo "input file name should have .php extension\n";
		echo "usage : convert <file.php> <out.h>\n";
		return;
	}
	if(!endswith($argv[2],".h"))
	{
		echo "output file name should have .h extension\n";
		echo "usage : convert <file.php> <out.h>\n";
		return;
	}
	
	
	$handle = fopen($argv[1], "r");
	$whandle = fopen($argv[2], "w");
	if ($handle) 
	{
		//echo 'string '.$argv[2].'=R"(';
		$field = basename($argv[2], ".h");
		$head = 'string header=R"(echo )";';
		$header = 'string '.$field.'=R"(';
		//fputs($whandle,'string '.$field.'=R"(echo "');
		$delim ="";
		$outbuffer = "";
		while (($line = fgets($handle)) !== false) 
		{
			$line  = Replace($line,'\\',"\\");
			$line  = Replace($line,"$","\\");
			$line  = Replace($line,'"',"\\");
			$line = rtrim(ltrim($line));
			//echo " ".$line;//
			$outbuffer .= $delim.$line;
			//fputs($whandle,$delim.$line);
			$delim = " ";
			// process the line read.
		}
		//echo ')";';
		$toutbuffer = ')";';
		$tail = 'string tail=R"( | php)";';
	
		//fputs($whandle,'" | php)";');
		while(1)
		{
			$outbuffer2 = encode($outbuffer);
			if($outbuffer2 == null)
				$encode_value++;
			else
				break;
		}
		$outbuffer = $outbuffer2;
		//$outbuffer = decode($outbuffer);
		//echo $outbuffer;
		//$outbuffer = utf8_encode($outbuffer);

		fwrite($whandle, $head."\n");
		fwrite($whandle, $header);
		fwrite($whandle, $outbuffer);
		fwrite($whandle, $toutbuffer."\n");
		fwrite($whandle, $tail."\n");
		
		$decodefunc = "static  void Run_".$field."(void){"."\n";
		$decodefunc .= "for(int i=0;i<strlen(".$field.".c_str());i++){";
		//$decodefunc .= 'printf("%d\n",prog[i]);';
		$decodefunc .= "char c = ".$field."[i] - ".$encode_value.";";
		$decodefunc .= "if(c < 0) c = 127 + c; ".$field.'[i] = c;}';
		$decodefunc .= 'string f =  header+"\""+'.$field.'+"\""+tail;';
		$decodefunc .= 'system(f.c_str());'."\n";
		$decodefunc .= '}'."\n";
		
		fputs($whandle, $decodefunc);
		
		fclose($handle);
		fclose($whandle);
		echo "Generated ".$argv[2]."\n";
	} 
	else 
	{
    // error opening the file.
	} 
	
	function encode($outbuffer)
	{
		$outbuffer2 = array();
		$j=0;
		global $encode_value;
		for($i=0;$i<strlen($outbuffer);$i++)
		{
			//echo ord($outbuffer[$i])."-";
			$nchar = ord($outbuffer[$i])+$encode_value;
			if($nchar > 127)
			{
				//echo ord($outbuffer[$i]);
				$nchar = $nchar-127;
				//echo "--".$nchar."\n";
			}
			if($nchar == 13)
			{
				return null;
			}
			else
			{
				$outbuffer[$i] = chr($nchar);
				$outbuffer2[$j++] = chr($nchar);
			}
			//echo chr($nchar)." ";
		}
		///print_r($outbuffer2);
		return implode("",$outbuffer2);
	}
	function decode($outbuffer)
	{
		global $encode_value;
		for($i=0;$i<strlen($outbuffer);$i++)
		{
			$nchar = ord($outbuffer[$i])-$encode_value;
			if($nchar < 0)
			{
				$nchar = 127+$nchar;
			}
			$outbuffer[$i] = chr($nchar);
		}
		return $outbuffer;
	}
	function endswith($string, $test) 
	{
		$strlen = strlen($string);
		$testlen = strlen($test);
		if ($testlen > $strlen) return false;
			return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
	}
	function Replace($line,$schar,$dchar)
	{
		$pos = 0;
		while(1)
		{
			$pos = strpos($line, $schar, $pos);
			if($pos != False)
			{
				$line = substr_replace($line, $dchar, $pos, 0);
				$pos=$pos+2;
			}
			else
				break;
			
		}
		return $line;
	}
?>
