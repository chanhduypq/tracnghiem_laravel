@php 
use Illuminate\Support\Facades\Session;
use JP_COMMUNITY\Models\Question;
@endphp
@if (Session::get('user')) 
    @php ($identity = Session::get('user'))
    @php ($user_id = $identity['id'])
@endif
@php ($row = DB::select("SELECT * FROM user_exam WHERE user_id=" . $user_id . " ORDER BY exam_date DESC LIMIT 1"))
<form method="POST" id="form1" onsubmit="return false;">
    <div class="row-fluid">
        <div class="span12">
            <div style="margin-top: 20px;" class="span3">
                
                @if (is_array($row) && count($row) > 0) 
                    <a class="download-result" href="{{ route('thi_viewresult') }}">
                        <button style="border-radius: 5px;background-color: brown;color: white;">
                            Xem kết quả lần thi gần nhất
                        </button>
                    </a>
                @endif
                
            </div>

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
            <!--<div class="span2" style="margin-top: 20px;"><input type="submit" value="Bắt đầu thi" id="start"/></div>-->
            <div class="span2" style="margin-top: 20px;" id="start">
                <a>Bắt đầu thi</a> 
            </div>
        </div>
    </div>

    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
</form>

<script type="text/javascript">

    jQuery(function ($) {
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
        $("#nganh_nghe_id").val('{{ $nganhNgheId }}');
        $("#level").val('{{ $level }}');

    });


</script>
