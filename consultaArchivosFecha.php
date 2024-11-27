<?php
require_once 'php/includes/DbConnect.php';
dirList('./users','newestFirst');


function dirList ($directory, $sortOrder){

    //Get each file and add its details to two arrays
    $results = array();
    $handler = opendir($directory);
    $i = 0;
    while ($file = readdir($handler)) {  
        if ($file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess"){
            $currentModified = filectime($directory."/".$file);
            if(date('Y-m-d',$currentModified) == "2023-06-15"){
                
                $file_dates[] = date('Y-m-d',$currentModified);
                $file_names[] = $file;
                $result[$i]['name'] = $file;
                $result[$i]['date'] = date('Y-m-d',$currentModified);
                
                $i++;
            }
        }    
    }
       closedir($handler);

    //Sort the date array by preferred order
    if ($sortOrder == "newestFirst"){
        arsort($file_dates);
    }else{
        asort($file_dates);
    }
    
    //Match file_names array to file_dates array
    $file_names_Array = array_keys($file_dates);
    foreach ($file_names_Array as $idx => $name) $name=$file_names[$name];
    $file_dates = array_merge($file_dates);
    
    $i = 0;
    //print_r($file_names);
    //print_r($file_dates);
    //print_r($result);
    consulta($result);
    //Loop through dates array and then echo the list
    /*foreach ($file_dates as $file_dates){
        $date = $file_dates;
        $j = $file_names_Array[$i];
        $file = $file_names[$j];
        $i++;
            
        echo  "File name: ".$file." - Date Added: ". $date. "<br/>";        
    }*/
}

function consulta($array){
    
    
    $datos = array();
    $db = new DbConnect();
    $conn = $db->connect();
    for($i = 0; $i < count($array); $i++){
        $sql = "SELECT * FROM `tabla_inicio` WHERE cif='{$array[$i]['name']}' AND fecha='2023-06-15' AND fase = 'privado' AND estado = 'hecho' UNION ALL ";
        $sentencia=$conn->prepare("SELECT * FROM `tabla_inicio` WHERE cif='{$array[$i]['name']}' AND fecha='2023-06-15' AND fase = 'privado'");
        echo $sql;
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $row = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $datos = $row;
        }else{
           // echo "error";
        }
    }
    print_r($datos);
}
?>