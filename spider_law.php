<?
ini_set('memory_limit', '99999M');
set_time_limit(0);
date_default_timezone_set('Asia/Taipei');
// 引入解析HTML工具
include "simple_html_dom.php";
// GET方式去回網頁資訊
function spider_get($url){
    $output = file_get_contents($url);
    return $output;
}
// POST方式取回網頁資訊
function spider_post($url,$data_array){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);
    //文件方式回傳，而不是輸出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_array));
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

$target_json = file_get_contents("law_target/law_target.json");
$target_array = json_decode($target_json);
foreach($target_array as $target_key => $target_value){
    $target_name = $target_value->target;
    $target_pcode = $target_value->pcode;

    $url = "https://law.moj.gov.tw/LawClass/LawAll.aspx?pcode=".$target_pcode;
    $output = spider_get($url);
    $html = new simple_html_dom();
    $html->load($output);
    $law_data_arr = array();
    // 法規標題

    $law_reg_title_th = $html->find(".table-title table th ");
    $law_reg_title_td = $html->find(".table-title table td ");

    // 法規名稱
    $LawName_html = $html->find("#hlLawName");
    foreach($LawName_html as $a_key => $a_value){
        $law_name = $a_value->innertext;
    }
    // 修正日期
    $LawDate_html = $html->find("#trLNNDate td");
    foreach($LawDate_html as $key => $value){
        $law_date = $value->innertext;
    }
    // 法規類別
    $law_type = $law_reg_title_td[2];

    // 法規內容
    $law_reg_content = $html->find(".law-reg-content ");
    foreach($law_reg_content as $key => $value){

        $html_2 = new simple_html_dom();
        $html_2->load($value);
        $col_no = $html->find(".row .col-no");
        $col_data = $html->find(".row .col-data");
        foreach($col_no as $col_key => $col_value){
            $temp_obj = new stdClass();

            $temp_obj->law_name = strip_tags($law_name);
            $temp_obj->law_date = strip_tags($law_date);
            $temp_obj->law_type = strip_tags($law_type);
            $temp_obj->law_no = strip_tags($col_value->innertext);
            $temp_obj->law_data = strip_tags($col_data[$col_key]->innertext);
            array_push($law_data_arr,$temp_obj);
        }
    }
    if(!is_dir("law_log")){
        mkdir("law_log",0777);
    }

    if(!is_dir("law_log/".date('Ymd'))){
        mkdir("law_log/".date('Ymd'),0777);
    }
    $law_data_json = json_encode($law_data_arr);
    file_put_contents("law_log/".date('Ymd')."/".$law_name.".json",$law_data_json);
}

?>