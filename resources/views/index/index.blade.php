@extends('layouts.index')
@section('title', $title)
@section('content')
    <div class="row-fluid">
        <div class="span12" id="editorcontents">
            <?php echo $content; ?>
        </div>
    </div>	
@endsection
