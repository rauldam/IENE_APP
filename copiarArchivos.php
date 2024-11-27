<?php

ini_set('display_errors', 1);

if(rename($_SERVER['DOCUMENT_ROOT'].'/users/E09613951/', $_SERVER['DOCUMENT_ROOT'].'/users/B56988694/CIF_ANTERIOR/')){
	echo 'ok';
}else{
	echo 'nook';
}
?>