<?php
// @TODO

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
          $thumbpath   = $upload_path . 'thumb_' . $filename;        // Path to thumbnail
          $moved       = move_uploaded_file($_FILES['image']['tmp_name'], $destination);
          $resized     = create_thumbnail($destination, $thumbpath); // Create thumbnail
        }
    }
    if ($moved === true and $resized === true) {                                   // If it moved
        $message = 'Uploaded:<br><img src="uploads/thumb_' . $filename . '">';     // Show image
    } else {                                                          // Otherwise
        $message = '<b>Could not upload file:</b> ' . $error;         // Show errors
    }
}
?>
<?php include 'includes/header.php' ?>
<?= $message ?>
  <form method="POST" action="resize-im.php" enctype="multipart/form-data">
    <label for="image"><b>Upload file:</b></label>
    <input type="file" name="image" id="image"><br>
    <input type="submit" value="Upload">
  </form>
<?php include 'includes/footer.php' ?>