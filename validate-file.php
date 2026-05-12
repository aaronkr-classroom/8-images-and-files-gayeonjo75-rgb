<?php
// @TODO
$moved          =  false;
$message        ='';
$error          ='';
$upload_path    ='uploads/';
$max_size       =524880;
$allowed_types=['image/jpeg','image/png','image/gif'];
$allowed_exts=['jpeg','jpg', 'png','gif'];

function  create_filename($filename, $upload_path){

$basename=pathimfo($filename, PATHINFO_FILENAME);
$extension=pathinfo($filename, PATHINFO_EXTENSION);
$basename=preg_replace('/[^A-z0-9]/','-',$basename);
$i=0;


while(file_exists($upload_path . $filename)) {
  $i= $i+1;
  $filename=basename . $i . '.' . $extension;
}
return $filename;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {                    // If form submitted
    $error = ($_FILES['image']['error'] === 1) ? 'too big ' : '';  // Check size error

    if ($_FILES['image']['error'] == 0) {                          // If no upload errors
        $error  .= ($_FILES['image']['size'] <= $max_size) ? '' : 'too big '; // Check size
        // Check the media type is in the $allowed_types array
        $type   = mime_content_type($_FILES['image']['tmp_name']);        
        $error .= in_array($type, $allowed_types) ? '' : 'wrong type ';
        // Check the file extension is in the $allowed_exts array
        $ext    = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $error .= in_array($ext, $allowed_exts) ? '' : 'wrong file extension ';

        // If there are no errors create the new filepath and try to move the file
        if (!$error) {
          $filename    = create_filename($_FILES['image']['name'], $upload_path);
          $destination = $upload_path . $filename;
          $moved       = move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        }
    }
    if ($moved === true) {                                            // If it moved
        $message = 'Uploaded:<br><img src="' . $destination . '">';   // Show image
    } else {                                                          // Otherwise
        $message = '<b>Could not upload file:</b> ' . $error;         // Show errors
    }
}
?>
<?php include 'includes/header.php' ?>
<?= $message ?>
  <form method="POST" action="validate-file.php" enctype="multipart/form-data">
    <label for="image"><b>Upload file:</b></label>
    <input type="file" name="image" id="image"><br>
    <input type="submit" value="Upload">
  </form>
<?php include 'includes/footer.php' ?>