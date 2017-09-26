<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
@include('includes.head')
@yield('css')
@yield('js-top')

@include('includes.css_js')


<body style="background:#f0f0f0;">
<?php 
use Illuminate\Support\Facades\DB;
$rows =DB::select('SELECT * FROM layout_content');
$menu_items = array();
$logo = array('file_name' => '/images/dien_luc.jpg', 'dynamic' => '1');
$header_text = $header_text_dynamic = $footer_text = $hinh_nen = '';

foreach ($rows as $row) {
    $header_text = $row['header_text'];
    $header_text_dynamic = $row['dynamic_header_text'];
    $logo = array('file_name' => $row['file_name'], 'dynamic' => $row['dynamic_logo']);
    $menu_items[] = $row['menu_text'];
    $footer_text = $row['footer_text'];
    $hinh_nen = $row['hinh_nen_file_name'];
}
$GLOBALS['menu_items'] = $menu_items;
?>
        @include('includes.dialog')
        <div class="container" style="padding-left: 10px;padding-right: 10px;">            
            <!--header-->
            <div class="container" style="padding-top: 30px;height: 100px;background-image: url('/<?php echo ltrim($hinh_nen,'/'); ?>');background-repeat: no-repeat;background-size: 100% 100%;">
                <div class="row-fluid">
                    <div class="span3">
                        <div class="row-fluid" style="padding-top: 10px;padding-left: 10px;">
                            <?php
                            if ($logo['dynamic'] == "1") {
                                ?>

                                <div class="span12" id="logo_header">
                                    <?php
                                    echoImage(ltrim($logo['file_name'],'/') , 70, 70, 'height', array('id' => 'logo-img'));
                                    echoImage(ltrim($logo['file_name'],'/'), 70, 70, 'height', array('id' => 'logo-img1'));
                                    ?>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="span12" id="logo_header">
                                    <?php
                                    echoImage(ltrim($logo['file_name'],'/'), 70, 70, 'height', array('id' => 'logo-img'));
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>  


                    </div>
                    <div class="span5">
                        <div class="row-fluid" <?php
                        if ($header_text_dynamic == "1"&&trim($header_text)!='') {
                            echo ' id="simpleDiv"';
                        }
                        ?> style="font-size: 30px;line-height: 1.3;text-align: center;color: #c1976c;font-family: serif;padding-top: 0;">
                            <div class="span12"><?php echo $header_text; ?></div>
                        </div>  
                    </div>
                    <div class="span4">
                        @include('includes.auth')
                    </div>
                </div>
            </div>            
            <!--end header-->
            <!--middle-->
            <!--above-->                           
            <div class="row-fluid" style="margin-top: 10px;">
                @include('includes.header')
            </div>
            <!--end above-->
            <!--below-->    
            <?php 
            if(isset($bg)&&$bg!=''){
                $bg='background-image: url(/'.ltrim($bg,'/').');background-size: cover;-webkit-background-size: cover;background-repeat: no-repeat;';
            }
            else{
                $bg='background: white;';
            }
            ?>
            <div class="row-fluid" style="margin-top: 10px;<?php echo $bg;?>">

                <!--<div class="span1">&nbsp;</div>-->
                <div class="span12" style="min-height: 600px;padding-left: 20px;">

                    @yield('content')
                    <?php if (isset($questions) && count($questions) > 0) { ?>
                        <div style="float: right;position: fixed;right: 0;width: 13%;background-color: blanchedalmond;max-height: 550px;overflow-y: auto;" id="fixed">
                            <?php
                            if (!isset($questions)) {
                                $questions = array();
                            } 

                            $i = 1;
                            if (count($questions) > 100) {
                                $num = 3;
                            } else if (count($questions) > 9) {
                                $num = 2;
                            } else {
                                $num = 1;
                            }
                            foreach ($questions as $question) {
                                ?>

                                <a class="goto" href="#question_<?php echo $i; ?>">
                                    <button style="margin: 5px;">
                                        <?php
                                        if ($num == 3) {
                                            if (strlen($i) == 1) {
                                                echo '00' . $i;
                                            } else if (strlen($i) == 2) {
                                                echo '0' . $i;
                                            } else {
                                                echo $i;
                                            }
                                        } else if ($num == 2) {
                                            if (strlen($i) == 1) {
                                                echo '0' . $i;
                                            } else {
                                                echo $i;
                                            }
                                        } else {
                                            echo $i;
                                        }
                                        ?>
                                    </button>
                                </a>                            
                                <?php
                                $i++;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!--<div class="span1">&nbsp;</div>-->
            </div>

            <!--end below--> 
            <!--end middle-->
            <!--footer-->            
            <!--<div class="row-fluid" style="color:white;background-color: #c1976c;width: 200%;left: 0;margin-left: -50%;margin-top: 30px;margin-bottom: 30px;">-->	
            <div class="row-fluid" style="color:white;background-color: #c1976c;margin-top: 30px;margin-bottom: 30px;">	
                <!--<div class="span12" style="margin: 0 auto;text-align: center;">-->  
                <div class="span12">
                    <?php                    
                    echo $footer_text;
                    ?>
                </div>
            </div>
            <!--end footer-->


        </div>
        <a id="gotop" style="bottom: -50px; right: -50px;"></a>
        <a id="gobottom" style="top: 0px; right: 0px;"></a>
        <div id='footer'/>
    </body>
</html>
<?php 
function echoImage($image_path, $width_for_view, $height_for_view, $order = "width", $html_option = array()) {
    if (!is_string($order) || ($order != "width" && $order != "height")) {
        echo "";
        return;
    }
    if (!is_string($image_path) || trim($image_path) == "") {
        echo "";
        return;
    }
    if ($image_path[0] != '/') {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $image_path) || !is_file($_SERVER['DOCUMENT_ROOT'] . '/' . $image_path)) {
            echo "";
            return;
        }
        $url = $image_path;
    } else if ($image_path[0] == '/') {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path) || !is_file($_SERVER['DOCUMENT_ROOT'] . $image_path)) {
            echo "";
            return;
        }
        $url = ltrim($image_path, '/');
    }
    list($width_orig, $height_orig) = getimagesize($url);
//        if($width_orig>=$height_orig){
//            $width = $width_for_view;
//            $ratio = $height_orig / $width_orig;
//            $height = ceil($width * $ratio);
//        }
//        else{
//            $height = $height_for_view;       
//            $ratio = $width_orig / $height_orig;
//            $width = ceil($height * $ratio);
//        }
    if ($order == "width") {
        if ($width_orig > $width_for_view) {
            $width = $width_for_view;
        } else {
            $width = $width_orig;
        }
        $ratio = $height_orig / $width_orig;
        $height = ceil($width * $ratio);
    } else if ($order == "height") {
        if ($height_orig > $height_for_view) {
            $height = $height_for_view;
        } else {
            $height = $height_orig;
        }
        $ratio = $width_orig / $height_orig;
        $width = ceil($height * $ratio);
    }
    if ($image_path[0] != '/') {
        $image_path = '/' . $image_path;
    }
    $attr_string = "";
    foreach ($html_option as $key => $value) {
        $attr_string .= " $key='$value'";
    }
    echo '<img' . $attr_string . ' style="margin: 0 auto;width: ' . $width . 'px;height: ' . $height . 'px;" src="' . $image_path . '"/>';
}
?>