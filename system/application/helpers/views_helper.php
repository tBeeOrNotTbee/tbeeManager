<?php

function setBreadcrumb($array){
	$result="<ol class='breadcrumb'>";
		foreach ($array as $item) {
			$result.="<li>".$item."</li>";			
		}	
	$result.="</ol>";
	return $result;
}