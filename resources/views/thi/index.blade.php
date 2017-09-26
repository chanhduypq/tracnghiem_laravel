@extends('layouts.app')
@section('content')
    <link href="{{ asset('css/question.css') }}?<?php echo substr(md5(microtime()), rand(0, 26), 5); ?>" rel="stylesheet" type="textcss"/>
    <?php 
    use Illuminate\Support\Facades\Session;
    if ($success != '') { ?>
        <div style="color: red;text-align: center;width: 100%;padding-top: 20px;">
            <h3><?php echo $success; ?></h3>
        </div>
        <?php
    } else {
        if (isset($message) && $message != '') {
            ?>
            <div style="color: red;text-align: center;width: 100%;padding-top: 20px;">
                <h3><?php echo $message; ?></h3>
            </div>
            <?php
        }
    }

    
    if (Session::get('user')) {
        $identity = Session::get('user');
        $user_id = $identity['id'];
    }
    
    $row = DB::select("SELECT * FROM user_exam WHERE user_id=" . $user_id . " ORDER BY exam_date DESC LIMIT 1");
    if (is_array($row) && count($row) > 0 && (!isset($showFormNganhNgheCapBac) || $showFormNganhNgheCapBac == FALSE)) {
        ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="span4" style="margin-top: 20px;">
                    <a class="download-result" href="<?php echo route('thi_viewresult'); ?>">
                        <button style="border-radius: 5px;background-color: brown;color: white;">
                            Xem kết quả lần thi gần nhất
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($showFormNganhNgheCapBac) && $showFormNganhNgheCapBac == true) {?> 
        
        @include('thi.partial.form_nganhnghe_capbac')
    <?php 
    
    }
    ?>
    <div class="row-fluid">
        <div class="span12"></div>
    </div>
    <?php 
    if (is_array($questions) && count($questions) > 0) {?>
        
        @include('thi.partial.form_question')
        <?php 
    }
    ?>
    <script src="{{ asset('js/jquery.fileDownload.js') }}?<?php echo substr(md5(microtime()),rand(0,26),5);?>" type="text/javascript"></script>
    <script type="text/javascript"> 
        jQuery(function ($){

            $(document).on("click", "a.download-result", function () {
                $.fileDownload($(this).prop('href'), {
                    preparingMessageHtml: "Hệ thống đang download, vui lòng chờ cho đến khi hoàn thành.",
                    failMessageHtml: "Đường truyền internet bị lỗi. Vui lòng thử lại sau."
                });
                return false; 
            });
        });


    </script>
@endsection





