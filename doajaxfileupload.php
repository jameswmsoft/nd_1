<?php session_start();
include("database.php");
$con = $link;
sleep(1);
$error = "";
	$msg = "success";
	$fileElementName = 'fileToUpload';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds file size limit';
				break;
			case '2':
				$error = 'The uploaded file exceeds file size limit';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
	}else 
	{ 
 if($_POST['select']=="image")
{   if($_POST['image_name']=="")
{
  $img_name=$_POST['page_key']."_".uniqid(); 
}
else
{
    $img_name=$_POST['image_name'];
}
    $img_path="uploaded_images/".$img_name.".jpg";
    $pos=strrpos($_FILES['fileToUpload']['name'],'.')+1;
   $ext=substr($_FILES['fileToUpload']['name'],$pos,4);
   $ext=strtolower($ext);
   if($ext=="jpg"||$ext=="jpeg")
{
   if(copy($_FILES['fileToUpload']['tmp_name'],$img_path))
{create_thumb($img_name);
$msg=$img_name;
if( $_POST['image_name'] =="")
{////////////////new page    
//$sql_check="select * from junk_data where image='$img_name'";
//$res_check=mysqli_query($con,$sql_check) ; 
//$row_check=mysqli_num_rows($res_check);
//if($row_check == 0)   
    $sql="insert into pages_data(page_key,name,type) values('$_POST[page_key]','$img_name.jpg','image')";
$res=mysqli_query($con,$sql);                
}
}
// $msg="--".$_POST['select']."---".$_POST['page_key']."-------".$_POST['page_id']."----".$_POST['image'].$_FILES['fileToUpload']['name'];
}
else 
$error="only jpg image allowed";


}
else  if($_POST['select']=="video")
{
     if($_POST['image_name']=="")
{
  $img_name=$_POST['page_key']."_".uniqid(); 
}
else
{
    $img_name=$_POST['image_name'];
}
    $img_path="uploaded_videos/".$img_name.".mp4";
    $pos=strpos($_FILES['fileToUpload']['name'],'.')+1;
   $ext=substr($_FILES['fileToUpload']['name'],$pos,4);
   $ext=strtolower($ext);
   if($ext=="mp4")
{if(copy($_FILES['fileToUpload']['tmp_name'],$img_path))
{
    //  $new_file=$img_name.".mp3"; 
   if($_POST['image_name'] == "")
{
//$sql_check="select * from junk_data where page_key='$_POST[page_key]'";
//$res_check=mysqli_query($con,$sql_check); 
//$row_check=mysqli_num_rows();
//if($row_check == 0)   
    $sql="insert into pages_data(page_key,name,type) values('$_POST[page_key]','$img_name.mp4','video')";
$res=mysqli_query($con,$sql);    
}
$msg=$img_name;}
                      
}
else 
$error="only mp4 allowed";
}
    }	
   
    ///."--".$_POST['select']."---".$_POST['page_key']."-------".$_POST['page_id']."----".$_POST['image'].$_FILES['fileToUpload']['name']
	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg ."'\n";
	echo "}";
    function create_thumb($img_name)
    {
     $img_path="uploaded_images"; 
   
     $thumb_path="uploaded_images/thumbs"; 
   
       $img=$img_path."/".$img_name.".jpg"; 
        $img = imagecreatefromjpeg($img);
      $width = imagesx( $img );
      $height = imagesy( $img );

      
      $new_width =100; //$thumbWidth;
      $new_height =100; //floor( $height * ( $thumbWidth / $width ) );
      if($width>$height)
      {
        $size=$height;
  }
  else
  {$size=$width;}
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $size, $size );
     
      $save_path=$thumb_path."/".$img_name.".jpg";
      imagejpeg( $tmp_img, $save_path );
    }
  
  
?>
