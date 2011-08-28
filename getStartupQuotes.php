//Author : Santosh Lakshman M ( Lucky Murari )
// Name : Startup Quote Images Grabber
// Description : This script downloads quotes from StartupQuote.com. It can be easily modified to scrape images from any site. 
<?php 

//As the script downloads lot of images, which takes lot of time, we need to set the execution time to maximum
set_time_limit(10000000000);

//At the time of script, there were 144 pages on StartupQuote.com
$maximumPages = 144;
//The basic template of the urls to scrape from
$urlTemplate = "http://startupquote.com/page/";

// Loops all the pages from StartupQuote.com {At the time of writing there were 144 pages and hence }
for($i=1; $i<$maximumPages; $i++){
	$html=get_data($urlTemplate.$i);
	$pattern='/< *img[^>]*src *= *["\']?([^"\']*)/i';	
	preg_match_all($pattern,$html,$matches,PREG_PATTERN_ORDER);
	$seq=1;
	foreach($matches[1] as $img)
	{
		if($seq>1 && $seq <5){ // As the first and 5th images are Logo and another random Ad, we skip them
			$fp = fopen("./StartupQuote/image_".$i."_".$seq.".jpg",'wb'); // Saves images to the directory "StartupQuote"
			$ch = curl_init ($img);
			curl_setopt($ch,CURLOPT_FILE,$fp);
			curl_setopt($ch,CURLOPT_URL,$img);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			$image=curl_exec($ch);
			curl_close ($ch);
			fwrite($fp, $image);
			fclose($fp);
		}
	$seq++;
	}
}
echo "All the Images are Downloaded successfully";

?>




<?
/* Returns the HTML contents of a URL using CURL*/
function get_data($url)
{	
	$ch = curl_init();
	$timeout = 5;//Timeout can be increased if you are behind slow connection.
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}



?>