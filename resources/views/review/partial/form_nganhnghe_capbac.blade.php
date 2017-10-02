@php 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use JP_COMMUNITY\Models\Question;
@endphp

@if (!is_array($questions) || count($questions) == 0)
    <form method="POST" id="form1" onsubmit="return false;" action="{{ route('review') }}">
        <div class="row-fluid">
            <div class="span12">
                
                 
                @if (Session::get('user')) 
                    @php ($identity = Session::get('user'))
                    @php ($user_id = $identity['id'])
                @endif
                @php ($row =DB::select("SELECT * FROM user_review WHERE user_id=" . $user_id . " ORDER BY review_date DESC LIMIT 1"))
                
                
                @if (is_array($row) && count($row) > 0)
                    @php ($row=$row[0])
                    <div class="span4" style="margin-top: 20px;">
                        <a class="download-result" href="{{ route('review_viewresult') }}">
                            <button style="border-radius: 5px;background-color: brown;color: white;">
                                Xem kết quả lần ôn tập gần nhất
                            </button>
                        </a>
                    </div>
                    @else 
                    <div class="span4"></div>
                    @endif
                <div class="span4" style="margin-top: 20px;">
                    <select id="nganh_nghe_id" name="nganh_nghe_id" style="width: 100%;">
                        <option value="0">------------------Chọn ngành nghề------------------</option>
                        @foreach ($nganhNghes as $nganhNghe)
                            <option value="{{ $nganhNghe['id'] }}">{{ $nganhNghe['title'] }}</option>
                        @endforeach    
                    </select>
                </div>
                <div class="span3" style="margin-top: 20px;">
                    <select id="level" name="level" style="width: 100%;">
                        <option value="0">-----------Chọn cấp bậc-----------</option>
                        <option value="{{ Question::BAC1 }}">Bậc 1</option>
                        <option value="{{ Question::BAC2 }}">Bậc 2</option>
                        <option value="{{ Question::BAC3 }}">Bậc 3</option>
                        <option value="{{ Question::BAC4 }}">Bậc 4</option>
                        <option value="{{ Question::BAC5 }}">Bậc 5</option>
                    </select>
                </div>
                <!--<div class="span1" style="margin-top: 20px;"><input type="submit" value="Bắt đầu" id="start"/></div>-->
                <div class="span1" style="margin-top: 20px;" id="start">
                    <a>Bắt đầu</a> 
                </div>    
                
            </div>
        </div>

        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
    </form>
    @endif
@section('js-top')
<script src="{{ asset('js/jquery.fileDownload.js') }}?<?php echo substr(md5(microtime()),rand(0,26),5);?>" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(function ($) {
        
        $(document).on("click", "a.download-result", function () {
            $.fileDownload($(this).prop('href'), {
                preparingMessageHtml: "Hệ thống đang download, vui lòng chờ cho đến khi hoàn thành.",
                failMessageHtml: "Đường truyền internet bị lỗi. Vui lòng thử lại sau."
            });
            return false; 
        });

        $("#nganh_nghe_id").val('<?php echo $nganhNgheId; ?>');
        $("#level").val('<?php echo $level; ?>');

        $("#start").click(function () {
            if ($("#nganh_nghe_id").val() == '0') {
                alert('Vui lòng chọn ngành nghề');
                $("#nganh_nghe_id").focus();
                return;
            }
            if ($("#level").val() == '0') {
                alert('Vui lòng chọn cấp bậc');
                $("#level").focus();
                return;
            }
            $("#form1").attr('onsubmit', 'return true');
            $("#form1").submit();
        });
        

       
    });

    
</script>
@endsection