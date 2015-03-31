<?php
set_time_limit(0);
include 'simple_html_dom.php';
$bookDepot = file_get_html('http://www.bookdepository.com/category/3149/Bibles/');
if (isset($bookDepot)) {
	foreach ($bookDepot->find('h3') as $h3) {
		foreach ($h3->find('a') as $aElement) {
			$bookLink = $aElement->href;
			$content[$bookLink] = '';
		}
		// echo 'Book Link: '; #needs to be json identifier
		// echo $bookLink.'<br>'; #needs to be json info
		$indBook = file_get_html($bookLink);
		foreach ($indBook->find('h1') as $bookTitle) {
			$bookTitle = $bookTitle->plaintext;
			$content[$bookLink]["title"] = $bookTitle;
		// echo 'Book Title: '; #needs to be json identifier
		// echo $bookTitle.', '; #needs to be json info
		}
		foreach ($indBook->find('img') as $bigImg) {
			if ($bookTitle == $bigImg->alt) {
				$bigImg = $bigImg->src;
				$content[$bookLink]["image"] = $bigImg;
				// echo 'Big Image: '; #needs to be json identifier
				// echo $bigImg.', '; #needs to be json info
			}
		}
		foreach ($indBook->find('ul') as $ul) {
			$ulClass = $ul->class;
			if ($ulClass == "biblio") {
				foreach ($ul->find('li') as $li) {
					$plainLi = $li->plaintext;
					$content[$bookLink]["plainli"] = $plainLi;
					// echo $plainLi.', ';
				}
			}
		}
		foreach ($indBook->find('div') as $pDiv) {
			$divClass = $pDiv->class;
			if ($divClass == "longDescription") {
				foreach ($pDiv->find('p') as $para) {
					$para = $para->plaintext;
					$content[$bookLink]["para"] = $para;
					// echo 'Long Description: '; #needs to be json identifier
					// echo $para; #needs to be json info
				}
			}
		}
	}
	file_put_contents("BookDepot.json", json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | FILE_APPEND));
}
# What I need to print in the JSON
	#$smallImg, $bigImg
#Completed things to print out
	#$bookLink
?>
