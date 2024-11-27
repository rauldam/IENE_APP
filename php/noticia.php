<?php
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

include('includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();


$titulo = $_POST['title'];
$noticia = $_POST['noticia'];
$editar = $_GET['editar'];

if($editar == "no"){
    
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["file"]["tmp_name"]);
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
    if ($_FILES["file"]["size"] > 500000) {
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
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		$categoria = json_encode(array('Legal'));
		$author = '{"name":"VN","avatar":"https://i.pravatar.cc/300"}';
        $image = 'https://'.$_SERVER['SERVER_NAME'].'/images/'.basename($_FILES["file"]["name"]);
        $sentencia=$conn->prepare("INSERT INTO `noticias`(`title`, `image`, `content`, `categories`, `author`) VALUES (?,?,?,?,?)");
        $sentencia->bindParam(1,$titulo);
        $sentencia->bindParam(2,$image);
        $sentencia->bindParam(3,$noticia);
		$sentencia->bindParam(4,$categoria);
        $sentencia->bindParam(5,$author);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            echo "Noticia insertada";
        }else{
            echo "Noticia no insertada";
        }
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }

}else{
    $id = $_GET['idnoticia'];
    $sentencia=$conn->prepare("UPDATE `noticias` SET `title`= ?, `content`= ? WHERE id = ?");
    $sentencia->bindParam(1,$titulo);
    $sentencia->bindParam(2,$noticia);
    $sentencia->bindParam(3,$id);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        echo "Actualizado";
    }else{
        echo "No actualizado";
    }
}



?>