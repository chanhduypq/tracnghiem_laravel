<?php 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
if (is_array($questions) && count($questions) > 0) {
    
    $config_exam =DB::table('config_exam')->first();
    $minute_per_question = $config_exam['phut'];
    ?>
    <div class="row-fluid" style="margin-bottom: 20px;">
        <div class="span12" style="text-align: center;margin: 0 auto;font-size: 50px;">
            <div>
                <!--<span id="h">Giờ</span> :-->
                <span id="m">Phút</span> :
                <span id="s">Giây</span>
            </div>
        </div>
        <!--<input type="hidden" id="h_val" value="1"/> <br/>-->
        <input type="hidden" id="m_val" value="<?php echo count($questions) * $minute_per_question - $miniutes; ?>"/> <br/>
        <input type="hidden" id="s_val" value="00"/>
    </div>


    <?php
}
?>
<form method="POST" onsubmit="return false;" id="form2">
    <?php if (is_array($questions) && count($questions) > 0) { ?>
        <div class="row-fluid" style="float: left;width: 85%;">
            <div class="span12"></div>
            <?php
            $i = 1;
            foreach ($questions as $question) {
                ?>
                <div>
                    <div class="span12 question" id="question_<?php echo $i; ?>">
                        <?php echo $i . '/ ' . $question['content']; ?>
                    </div>
                    <?php
                    $answers = $question['answers'];
                    
                    if ($question['is_dao'] == '1') {
                        foreach ($answers as &$answer) {
                            if ($question['dapan_sign'] == $answer['sign']) {
                                $answer['is_dapan'] = TRUE;
                            } else {
                                $answer['is_dapan'] = FALSE;
                            }
                        }


                        shuffle($answers);
                        $newAnswers['A'] = array_pop($answers);
                        shuffle($answers);
                        $newAnswers['B'] = array_pop($answers);
                        shuffle($answers);
                        $newAnswers['C'] = array_pop($answers);
                        //
                        $newAnswers['D'] = array_pop($answers);
                        foreach ($newAnswers as $key => $newAnswer) {
                            if ($newAnswer['is_dapan'] == TRUE) {
                                $question['dapan_sign'] = $key;
                            }
                        }
                    } else {
                        $k = 0;
                        foreach ($answers as $answer) {
                            if ($question['dapan_sign'] == $answer['sign']) {
                                $answer['is_dapan'] = TRUE;
                            } else {
                                $answer['is_dapan'] = FALSE;
                            }

                            if ($k == 0) {
                                $newAnswers['A'] = $answer;
                            } else if ($k == 1) {
                                $newAnswers['B'] = $answer;
                            } else if ($k == 2) {
                                $newAnswers['C'] = $answer;
                            } else {
                                $newAnswers['D'] = $answer;
                            }
                            $k++;
                        }
                    }

                    foreach ($newAnswers as $key=>$answer) {
                        ?>
                        <div class="span11 answer">
                            <label>
                                <div class='span1'>
                                    <input type="radio" name="<?php echo $question['id']; ?>" value="<?php echo $answer['id'] . '_' . $key; ?>"/>
                                    <?php echo $key; ?>
                                </div>
                                <div class='span11'>
                                    <?php echo $answer['content']; ?>
                                </div>
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                    <input type="hidden" name="question_id[]" value="<?php echo $question['id']; ?>"/>
                    <input type="hidden" name="answer_id[]"/>
                    <input type="hidden" name="answer_sign[]"/>
                    <input type="hidden" name="dapan_sign[]" value="<?php echo $question['dapan_sign']; ?>"/>
                    <input type="hidden" name="answers_json[]" value='<?php echo json_encode($newAnswers);?>'/>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>

        <div class="row-fluid" style="width: 15%;float: left;" id="goto">
        </div>
    
        <?php
    }
    if (is_array($questions) && count($questions) > 0) {
        ?>
        <div class="span12" style="text-align: center;margin: 0 auto;">
            <input type="hidden" name="nganh_nghe_id_form2" value="<?php echo $nganhNgheId; ?>"/>
            <input type="hidden" name="level_form2" value="<?php echo $level; ?>"/>
            <input type="submit" value="Hoàn tất" id="finish"/>
        </div>
        <?php
    }
    ?>
    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
</form>

<script type="text/javascript">
    jQuery(function ($) {
        

        $('a.goto').click(function (event) {

            // The id of the section we want to go to.
            var id = $(this).attr("href");

            // An offset to push the content down from the top.
            var offset = 60;

            // Our scroll target : the top position of the
            // section that has the id referenced by our href.
            var target = $(id).offset().top - offset;
            
            $('.span12.question').css('border','0');
            $(id).css('border','1px solid red');

            // The magic...smooth scrollin' goodness.
            $('html, body').animate({scrollTop: target}, 500);

            //prevent the page from jumping down to our section.
            event.preventDefault();
        });

        
        

        $("#finish").click(function () {
            clearTimeout(timeout);
            $('input[name="answer_id[]"]').each(function( index ) {
                if($(this).val()==''){
                    $(this).val('-1');
                }
            });
            $('input[name="answer_sign[]"]').each(function( index ) {
                if($(this).val()==''){
                    $(this).val('Z');
                }
            });
            $("#form2").attr('onsubmit', 'return true');
            $("#form2").submit();
        });

        $("input[type='radio']").click(function () {
            val = $(this).val();
            temp = val.split('_');
            answer_id = temp[0];
            answer_sign = temp[1];
            $(this).parent().parent().parent().parent().find('input[name="answer_id[]"]').eq(0).val(answer_id);
            $(this).parent().parent().parent().parent().find('input[name="answer_sign[]"]').eq(0).val(answer_sign);

            question_id = $(this).parent().parent().parent().parent().find('div.span12.question').eq(0).attr('id');
            $("a[href='#" + question_id + "']").find('button').eq(0).css('color', 'white');
            $("a[href='#" + question_id + "']").find('button').eq(0).css('background-color', 'blue');

        });
    });

//    var h = null; // Giờ
    var m = null; // Phút
    var s = null; // Giây

    var timeout = null; // Timeout
<?php 
if (is_array($questions) && count($questions) > 0) { 
?>
    start();
<?php 
}
?>
    function start()
    {
        /*BƯỚC 1: LẤY GIÁ TRỊ BAN ĐẦU*/
//        if (h === null)
//        {
//            h = parseInt(document.getElementById('h_val').value);
//            m = parseInt(document.getElementById('m_val').value);
//            s = parseInt(document.getElementById('s_val').value);
//        }
        if (m === null)
        {

            m = parseInt(document.getElementById('m_val').value);
            s = parseInt(document.getElementById('s_val').value);
        }

        /*BƯỚC 1: CHUYỂN ĐỔI DỮ LIỆU*/
        // Nếu số giây = -1 tức là đã chạy ngược hết số giây, lúc này:
        //  - giảm số phút xuống 1 đơn vị
        //  - thiết lập số giây lại 59
        if (s === -1) {
            m -= 1;
            s = 59;
        }

        // Nếu số phút = -1 tức là đã chạy ngược hết số phút, lúc này:
        //  - giảm số giờ xuống 1 đơn vị
        //  - thiết lập số phút lại 59
//        if (m === -1){
//            h -= 1;
//            m = 59;
//        }

        // Nếu số giờ = -1 tức là đã hết giờ, lúc này:
        //  - Dừng chương trình
//        if (h == -1){
//            clearTimeout(timeout);
//            alert('Hết giờ');
//            return false;
//        }
        if (m == -1) {
            clearTimeout(timeout);
            setTimeout(function () {
                $("#finish").click();
            }, 2000);
            return false;
        }

        /*BƯỚC 1: HIỂN THỊ ĐỒNG HỒ*/
        document.getElementById('m').innerText = m.toString();
        document.getElementById('s').innerText = s.toString();

        /*BƯỚC 1: GIẢM PHÚT XUỐNG 1 GIÂY VÀ GỌI LẠI SAU 1 GIÂY */
        timeout = setTimeout(function () {
            s--;
            start();
        }, 1000);
    }
</script>
