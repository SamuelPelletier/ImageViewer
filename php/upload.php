<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (preg_match('/.*\.zip$/', $_POST["name"])) {
        $target_dir = "../import/";
        $uploadOk = 1;
        $count = 0;
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $folderName = $_POST["name"];
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["file"]["size"] > 209715200) { //200Mo
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $zip = new ZipArchive;
                if ($zip->open($target_dir . $folderName) === TRUE) {
                    $zip->extractTo($target_dir);
                    $zip->close();
                    unlink($target_file);
                    echo 'ok';
                } else {
                    echo 'échec';
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "It's not a zip file";
    }
}
?>
