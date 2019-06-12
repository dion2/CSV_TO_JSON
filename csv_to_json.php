<?
if ($_FILES) {
    
    $csv_name = $_FILES['csv_file']['name'];
    $csv_tmp_name = $_FILES['csv_file']['tmp_name'];
    $csv_size = $_FILES['csv_file']['size'];

    if (move_uploaded_file($csv_tmp_name, $csv_name)) {
        echo "上傳成功";
        $key_array = array();
        $data_array = array();
        $row = 1;
        if (($handle = fopen($csv_name, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                if ($row == 1) {
                    for ($col = 0; $col < $num; $col++) {
                        array_push($key_array, $data[$col]);
                    }
                } else {
                    $tmp_arr = array();
                    for ($col = 0; $col < $num; $col++) {
                        $tmp_arr[$key_array[$col]] = $data[$col];
                    }
                    array_push($data_array, $tmp_arr);
                }
                $row++;
            }
            fclose($handle);
        }
        $json_file = "temp_json.json";
        file_put_contents($json_file, json_encode(($data_array)));
        echo "檔案下載<a href='$json_file' target='_blank'>JSON檔案下載</a>";
        echo "<br><hr>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="csv_to_json.php" method="post" enctype="multipart/form-data">
        上傳CSV檔<br>
        <input type="file" name="csv_file">
        <input type="submit">
    </form>
</body>

</html>