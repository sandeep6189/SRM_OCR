<?php
is_callable('shell_exec') && false === stripos(ini_get('disable_functions'), 'shell_exec');
function isEnabled($func) {
    return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
}


$output_file_name = "uploads/output_".$_FILES["fileToUpload"]["name"];
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$imageFileType = strtolower($imageFileType);

if($imageFileType == "pdf")
{
    // convert pdf to set of images
    //$set_of_images = array();
    $set_of_images = pdf_convert($_FILES["fileToUpload"]["tmp_name"],$_FILES["fileToUpload"]["name"]);
    $out = fopen("uploads/".$output_file_name.'.txt', "a+");
    for($i=0; $i<count($set_of_images);$i++)
    {
        $str = "tesseract ".$set_of_images[$i]." uploads/output_".$_FILES["fileToUpload"]["name"]."_".$i;
        $output = shell_exec($str);
        
    }
    $files = glob("uploads/*.txt*");
    print_r($files);
    foreach($files as $file){
        $in = fopen($file, "r");
            while ($line = fread($in,filesize($file))){
                   fwrite($out, $line);
            }
            fclose($in);
        }
    fclose($out);

    echo "<br><br>Click here to download file: <a href='".$output_file_name.".txt' download='".$output_file_name.".txt'>Download</a>";
    echo "<br><br>Click here to Back: <a href='/SRM_OCR/'>Click Me</a>";
    //echo "pdf file";
    $uploadOk = 1;

}
else
{
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    	if (isEnabled('shell_exec')) {
        		//echo "works";
    		shell_exec('echo "hello world"');
    	}
    	
    	$str = "tesseract ".$target_file." ".$output_file_name;
    	echo $str;
    	$output = shell_exec($str);
    	#echo $output;
    	echo "<br><br>Click here to download file: <a href='".$output_file_name.".txt' download='".$output_file_name.".txt'>Download</a>";
    	echo "<br><br>Click here to Back: <a href='/SRM_OCR/'>Click Me</a>";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

function pdf_convert($pdf,$filename)
    {
        //$pdf = $pdf.'pdf';
        $pdf_in = fopen($pdf, "rb");
        $img_array = array();
        /*
        $im = new imagick();
        $im->setResolution(150,150);
        $im->readImageBlob($pdf_in);
        $num_pages = $im->getNumberImages();
        echo "num pages ".$num_pages;
        for($i = 0;$i < $num_pages; $i++) 
        {
            $im->setIteratorIndex($i);
            $im->setImageFormat('jpeg');
            $img_array[$i] = $im->getImageBlob();
            echo $img_array[$i];
         }
         $im->destroy();
        */
         // try for first page
         //$img_array = array();
         $num_pages = preg_replace('/[^0-9]/','',shell_exec("pdfinfo ".$pdf." | grep Pages:"));
         //echo "num_pages ".$num_pages;
         // create an array of images. 
         for($i=0;$i<$num_pages;$i++)
            {
                 $im = new imagick($pdf.'['.$i.']');
                 $im->setImageFormat('jpeg');  
                 $im->writeImage('uploads/'.$filename.$i.'.jpg');
                 $img_array[$i] =  'uploads/'.$filename.$i.'.jpg';
                 $im->clear(); 
                 $im->destroy();       
            } 
         
         //print_r($img_array);
         return $img_array;

    }
?>
