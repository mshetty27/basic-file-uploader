<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['files'])) {
    $errors = [];
    $path = 'uploads/';
    $extensions = ['jpg', 'jpeg', 'png', 'gif'];

    //Database
    $hostname='localhost';
    $username='root';
    $password='root';
    $dbname='edureka';
    $usertable='image_uploads';


    $count_of_files = count($_FILES['files']['tmp_name']);

    for ($i = 0; $i < $count_of_files; $i++) {
      $file_name = $_FILES['files']['name'][$i];
      $file_tmp = $_FILES['files']['tmp_name'][$i];
      $file_type = $_FILES['files']['type'][$i];
      $file_size = $_FILES['files']['size'][$i];
      $file_ext = strtolower(end(explode('.', $_FILES['files']['name'][$i])));

      $file = $path . $file_name;

      if (!in_array($file_ext, $extensions)) {
        $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
      }
      if ($file_size > 2097152) {
          $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
      }

      if (empty($errors)) {
        //Move the file to actual location
        move_uploaded_file($file_tmp, $file);

        //Save the information to database.
        try{
          if(!$con)
            $con = mysqli_connect($hostname,$username, $password);
          if($con){
            mysqli_select_db($con, $dbname);
            $query = "insert into ". $usertable." (fileName, filePath) values('".$file_name ."','".$file."');";
            mysqli_query($con,$query) or ($errors[] = `Unable to add file record to database`);
          }
          else{
            $errors[] = 'Unable to connect to database. ';
            break;
          }
            
        }
        catch(Exception $ce){
          $errors[] = 'Unable to save to database. '. $ce->getMessage();
        }
      }
    }

    $result = array('errors' => $errors);

    //return the json response
    header('Content-Type: application/json');  // <-- header declaration
    echo json_encode($result, true);    // <--- encode
    exit();
  }

}