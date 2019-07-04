<?
if($_POST){
    foreach($_POST as $key => $value){
        $$key = $value;
    }
}
if($run == "1"){
    if($law_target){
        $json_data = array();
        foreach ($law_target as $target_key => $target_value){
            $target = $law_target[$target_key];
            $pcode = $law_pcode[$target_key];
            if($target!="" && $pcode !=""){
                $temp_obj = new stdClass;
                $temp_obj->target = $target;
                $temp_obj->pcode = $pcode;
                array_push($json_data,$temp_obj);
            }
        }
        $law_taget_json = json_encode($json_data);
        file_put_contents("law_target/law_target.json",$law_taget_json);
    }

    echo "<script>
    alert('更新完成');
    location.href='law_target_set.php';
    </script>";
}

$target_json = file_get_contents("law_target/law_target.json");
$target_array = json_decode($target_json);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script type="text/javascript" src="../include/js/jquery-1.5.1.js"></script>
    <link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../include/bootstrap/js/bootstrap.min.js" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script>
    $(document).ready(function(){
        $("#add_law").click(function(){
            $("#law_target_table").append("<tr><td><input type=\"text\" class=\"form-control\" name=\"law_target[]\" id=\"law_target\" value=\"\"></td><td><input type=\"text\" class=\"form-control\" name=\"law_pcode[]\" id=\"law_pcode\" value=\"\"></td></tr>");
        });
    });
    </script>
</head>
<body>
<div class="container">
    <form action = "law_target_set.php" method="POST" name="law_form" id="law_form">
        <div>
            <input type="submit" class="btn btn-primary" value="存檔">
            <input type="button" id="add_law" class="btn btn-primary" value="新增">
            <input type="hidden" name="run" id="run" value="1">
        </div>

        <table class="table table-bordered table-striped" id="law_target_table">
            <tr>
                <th>
                    法規名稱
                </th>
                <th>
                    法規代碼
                </th>
            </tr>
            <?
            foreach($target_array as $target_key => $target_value){
                $target_name = $target_value->target;
                $target_pcode = $target_value->pcode;
            ?>
            <tr>
                <td>
                    <input type="text" class="form-control" name="law_target[]" id="law_target" value="<?=$target_name?>">
                </td>
                <td>
                    <input type="text" class="form-control" name="law_pcode[]" id="law_pcode" value="<?=$target_pcode?>">
                </td>
            </tr>
            <?}?>
        </table>
    </form>
</div>
</body>
</html>
