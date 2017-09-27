<?php 
$menu_items=$GLOBALS['menu_items'];
if (\Illuminate\Support\Facades\Session::has('user')) {
    $hrefThi = route('thi');
    $hrefReview = route('review');
    $idReview=$idThi='';
} else {
    $hrefThi = $hrefReview = 'javascript:void(0)';
    $idThi=' id="thi"';
    $idReview=' id="review"';
    
}
$routeArray = app('request')->route()->getAction();
$controllerAction = class_basename($routeArray['controller']);
list($controller, $action) = explode('@', $controllerAction);
$controller = strtolower(str_replace('Controller', '', $controller));
?>
<div class="span12" style="padding: 20px;">
    <ul id="topnav">
        <li<?php if ($controller=='index') echo ' class="active"'; ?>><a href="<?php echo route('/'); ?>/"><?php echo $menu_items[0]; ?></a></li>
        <li<?php echo $idThi; if ($controller=='thi') echo ' class="active"'; ?>><a href="<?php echo $hrefThi; ?>"><?php echo $menu_items[1]; ?></a></li>                        
        <li<?php echo $idReview; if ($controller=='review') echo ' class="active"'; ?>><a href="<?php echo $hrefReview; ?>"><?php echo $menu_items[2]; ?></a></li>                        
        <li<?php if ($controller=='question') echo ' class="active"'; ?>>
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
                            <?php 
                            for($i=1;$i<=5;$i++){?>
                                <li><a href="<?php echo route('/'); ?>/question/<?php echo $row['id'];?>/<?php echo $i;?>">Bậc <?php echo $i;?></a></li>
                            <?php 
                            }
                            ?>
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
