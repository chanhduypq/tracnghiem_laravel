@php 
if (\Illuminate\Support\Facades\Session::has('user')) {
    $hrefThi = route('thi');
    $hrefReview = route('review');
    $idReview=$idThi='';
} else {
    $hrefThi = $hrefReview = 'javascript:void(0)';
    $idThi=' id="thi"';
    $idReview=' id="review"';
    
}
@endphp
<div class="span12" style="padding: 20px;">
    <ul id="topnav">
        <li{!! (Request::is('/') ? ' class="active"' : '') !!}><a href="<?php echo route('/'); ?>/"><?php echo $menu_items[0]; ?></a></li>
        <li{{ $idThi }}{!! (Request::is('thi') ? ' class="active"' : '') !!}><a href="<?php echo $hrefThi; ?>"><?php echo $menu_items[1]; ?></a></li>                        
        <li{{ $idReview }}{!! (Request::is('review') ? ' class="active"' : '') !!}><a href="<?php echo $hrefReview; ?>"><?php echo $menu_items[2]; ?></a></li>                        
        <li{!! (Request::is('question*') ? ' class="active"' : '') !!}>
            <a href="#"><?php echo $menu_items[3]; ?></a>
            <ul style="background-color: white;" id="par">
                <?php 
                use Illuminate\Support\Facades\DB;
                $rows = DB::select('select * from nganh_nghe');
                foreach ($rows as $row){
                ?>
                    <li>
                        <a href="#" style="color: black;"><?php echo $row['title'];?></a>
                        <ul style="margin-left: 60px;background-color: white;">
                           
                            @for($i=1;$i<=5;$i++) 
                                <li><a href="<?php echo route('/'); ?>/question/<?php echo $row['id'];?>/<?php echo $i;?>">Bậc <?php echo $i;?></a></li>
                            @endfor
                        </ul>
                    </li> 
                <?php 
                }
                ?>

            </ul>
        </li>
        <li><a href="<?php echo route('index_guide'); ?>"><?php echo $menu_items[4]; ?></a></li>                        
    </ul>
</div>
