@php 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
@endphp
@if (is_array($questions) && count($questions) > 0)
    
    @php ($config_exam =DB::table('config_exam')->first())
    @php ($minute_per_question = $config_exam['phut'])
    
    <div class="row-fluid" style="margin-bottom: 20px;">
        <div class="span12" style="text-align: center;margin: 0 auto;font-size: 50px;">
            <div>
                <!--<span id="h">Giờ</span> :-->
                <span id="m">Phút</span> :
                <span id="s">Giây</span>
            </div>
        </div>
        <!--<input type="hidden" id="h_val" value="1"/> <br/>-->
        <input type="hidden" id="m_val" value="{{ count($questions) * $minute_per_question - $miniutes }}"/> <br/>
        <input type="hidden" id="s_val" value="00"/>
    </div>


@endif
<form method="POST" onsubmit="return false;" id="form2">
    @if (is_array($questions) && count($questions) > 0) 
        <div class="row-fluid" style="float: left;width: 85%;">
            <div class="span12"></div>
            
            @php ($i = 1)
            
            @foreach ($questions as $question) 
                
                <div>
                    <div class="span12 question" id="question_{{ $i }}">
                        {{ $i . '/ ' . $question['content'] }}
                    </div>
                    @php ($answers = $question['answers'])
                    
                    @if ($question['is_dao'] == '1')
                        <?php foreach ($answers as &$answer){?>
                            @if ($question['dapan_sign'] == $answer['sign'])
                                @php ($answer['is_dapan'] = TRUE)
                            @else 
                                @php ($answer['is_dapan'] = FALSE)
                            @endif
                        <?php }?>

                        @php 
                        shuffle($answers);
                        $newAnswers['A'] = array_pop($answers);
                        shuffle($answers);
                        $newAnswers['B'] = array_pop($answers);
                        shuffle($answers);
                        $newAnswers['C'] = array_pop($answers);
                        //
                        $newAnswers['D'] = array_pop($answers);
                        @endphp
                        @foreach ($newAnswers as $key => $newAnswer)
                            @if ($newAnswer['is_dapan'] == TRUE)
                                @php ($question['dapan_sign'] = $key)
                            @endif
                        @endforeach
                    @else 
                        @php ($k = 0)
                        @foreach ($answers as $answer) {
                            @if ($question['dapan_sign'] == $answer['sign'])
                                @php ($answer['is_dapan'] = TRUE)
                            @else
                                @php ($answer['is_dapan'] = FALSE)
                            @endif

                            @if ($k == 0)
                                @php ($newAnswers['A'] = $answer)
                            @elseif ($k == 1)
                                @php ($newAnswers['B'] = $answer)
                            @elseif ($k == 2)
                                @php ($newAnswers['C'] = $answer)
                            @else
                                @php ($newAnswers['D'] = $answer)
                            @endif
                            @php ($k++)
                        @endforeach
                    
                    @endif
                    @foreach ($newAnswers as $key=>$answer) 
                        
                        <div class="span11 answer">
                            <label>
                                <div class='span1'>
                                    <input type="radio" name="{{ $question['id'] }}" value="{{ $answer['id'] . '_' . $key }}"/>
                                    {{ $key }}
                                </div>
                                <div class='span11'>
                                    {{ $answer['content'] }}
                                </div>
                            </label>
                        </div>
                    @endforeach
                    <input type="hidden" name="question_id[]" value="{{ $question['id'] }}"/>
                    <input type="hidden" name="answer_id[]"/>
                    <input type="hidden" name="answer_sign[]"/>
                    <input type="hidden" name="dapan_sign[]" value="{{ $question['dapan_sign'] }}"/>
                    <input type="hidden" name="answers_json[]" value='{{ json_encode($newAnswers) }}'/>
                </div>                
                @php ($i++)
            @endforeach
        </div>

        <div class="row-fluid" style="width: 15%;float: left;" id="goto">
        </div>
    
    @endif
    @if (is_array($questions) && count($questions) > 0)         
        <div class="span12" style="text-align: center;margin: 0 auto;">
            <input type="hidden" name="nganh_nghe_id_form2" value="{{ $nganhNgheId }}"/>
            <input type="hidden" name="level_form2" value="{{ $level }}"/>
            <input type="submit" value="Hoàn tất" id="finish"/>
        </div>
    @endif
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

@if (is_array($questions) && count($questions) > 0) 
    start();
@endif

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
