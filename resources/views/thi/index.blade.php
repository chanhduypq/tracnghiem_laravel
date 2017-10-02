@extends('layouts.index')
@section('title', $title)
@section('content')
    <link href="{{ asset('css/question.css') }}?{{ substr(md5(microtime()), rand(0, 26), 5) }}" rel="stylesheet" type="text/css"/>
    
    @php use Illuminate\Support\Facades\Session; @endphp
    @if ($success != '')
        <div style="color: red;text-align: center;width: 100%;padding-top: 20px;">
            <h3>{{ $success }}</h3>
        </div>        
    @else
        @if (isset($message) && $message != '') 
            
            <div style="color: red;text-align: center;width: 100%;padding-top: 20px;">
                <h3>{{ $message }}</h3>
            </div>
            
        @endif
    @endif

    
    @if (Session::get('user'))
        @php ($identity = Session::get('user'))
        @php ($user_id = $identity['id'])
    @endif
    
    @php ($row = DB::select("SELECT * FROM user_exam WHERE user_id=" . $user_id . " ORDER BY exam_date DESC LIMIT 1"))
    @if (is_array($row) && count($row) > 0 && (!isset($showFormNganhNgheCapBac) || $showFormNganhNgheCapBac == FALSE)) 

        <div class="row-fluid">
            <div class="span12">
                <div class="span4" style="margin-top: 20px;">
                    <a class="download-result" href="{{ route('thi_viewresult') }}">
                        <button style="border-radius: 5px;background-color: brown;color: white;">
                            Xem kết quả lần thi gần nhất
                        </button>
                    </a>
                </div>
            </div>
        </div>
    @endif
    @if (isset($showFormNganhNgheCapBac) && $showFormNganhNgheCapBac == true)         
        @include('thi.partial.form_nganhnghe_capbac')
    @endif
    <div class="row-fluid">
        <div class="span12"></div>
    </div>
    
    @if (is_array($questions) && count($questions) > 0)         
        @include('thi.partial.form_question')
    @endif
    <script src="{{ asset('js/jquery.fileDownload.js') }}?{{ substr(md5(microtime()),rand(0,26),5) }}" type="text/javascript"></script>
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





