<?php 

function view($path, $data = null) {

	if ($data) {
		extract($data);
	}

	$path = $path . '.view.php';

	include "views/layout.php";
}

function viewAdmin($data = null) {

    if ($data) {
        extract($data);
    }
    
    include "../views/admin/create.view.php";
}

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function getExcerpt($str, $startPos=0, $maxLength=200) {
	if(strlen($str) > $maxLength) {
		$excerpt   = substr($str, $startPos, $maxLength-3);
		$lastSpace = strrpos($excerpt, ' ');
		$excerpt   = substr($excerpt, 0, $lastSpace);
		$excerpt  .= '...';
	} else {
		$excerpt = $str;
	}
	
	return $excerpt;
}

function getAddress() {
    $protocol = (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") ? 'https' : 'http';
    return $protocol.'://'.$_SERVER['SERVER_NAME'];
}

function uploadImage($arr, $dir) {  
    
    $uploadfile = $arr['tmp_name'];
    $uploadname = $arr['name'];
    $uploadsize = $arr['size'];       
    $uploadtype = getimagesize($arr['tmp_name']);
    $uploadInfoArray = [];       
    
    if(is_uploaded_file($uploadfile)) {

        if (!((preg_match("`^[-0-9A-Z_\.]+$`i", $uploadname)) ? true : false)) {
            $uploadInfoArray['status'] = "Failed to upload your image. Its name contains illegal characters. ";
            return $uploadInfoArray;
        }
        elseif (($uploadtype[2] !== IMAGETYPE_GIF) && ($uploadtype[2] !== IMAGETYPE_JPEG) && ($uploadtype[2] !== IMAGETYPE_PNG)) {
            $uploadInfoArray['status'] = "Failed to upload your image. Your file is not an image. ";
            return $uploadInfoArray;
        }
        elseif ($uploadsize > 2000000) {
            $uploadInfoArray['status'] = "Failed to upload your image. Your file is bigger than 2Mb. ";
            return $uploadInfoArray;
        }
        else {            
            $uploadpath = "../assets/img/hero/" . $dir . "/";
            if (!file_exists($uploadpath)) {
                mkdir($uploadpath, 0777, true);
            }             
            $uploadInfoArray['uploadurl'] = ROOT . substr($uploadpath, 2) . $uploadname;            
            if(move_uploaded_file($uploadfile, $uploadpath . $uploadname)) {
                $uploadInfoArray['status'] = "Sussecfully uploaded your image(s). ";
                return $uploadInfoArray;
            }
            else {
                $uploadInfoArray['status'] = "Failed to move your image(s). ";
                return $uploadInfoArray;
            }
        }           
    }    
    else {
        $uploadInfoArray['status'] = "No image was uploaded. ";
        return $uploadInfoArray;
    }
}

function getPicdir($id) {
    $post = Article::find($id);
    $arr = $post->to_array();
    $dir = getStringBetween($arr['pic_url'], '/hero/', '/');
    return $dir;
}

function getUploadurl($id) {
    $post = Article::find($id);
    $arr = $post->to_array();
    $url = $arr['pic_url'];
    return $url;
}

function getStringBetween($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}


