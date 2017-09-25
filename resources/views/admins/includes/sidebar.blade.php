<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('img/logo_alojapan_sp.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
            <?php 
             ?>
                <p>{!! isset(Auth::user()->name) ? Auth::user()->name : '' !!}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="{!! (Request::is('admin/users*') ? 'active' : '') !!} treeview">
                <a href="{!! route('admin.users.index')!!}">
                    <i class="fa fa-user"></i> <span>{!! trans('global.users') !!}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! (Request::is('admin/users') ? 'active' : '') !!}"><a href="{!! route('admin.users.index')!!}"><i class="fa fa-circle-o"></i> {!! trans('global.manage_users') !!} </a></li>
                    <li class="{!! (Request::is('admin/users/create') ? 'active' : '') !!}"><a href="{!! route('admin.users.create')!!}"><i class="fa fa-circle-o"></i> {!! trans('global.create_users') !!} </a></li>
                </ul>
            </li>

            <li class="{!! (Request::is('admin/categories*') ? 'active' : '') !!} treeview">
                <a href="{!! route('admin.categories.index')!!}">
                    <i class="fa fa-dashboard"></i> <span>{!! trans('global.categories') !!}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! (Request::is('admin/categories') ? 'active' : '') !!}"><a href="{!! route('admin.categories.index')!!}"><i class="fa fa-circle-o"></i> {!! trans('global.manage_categories') !!} </a></li>
                    <li class="{!! (Request::is('admin/categories/create') ? 'active' : '') !!}"><a href="{!! route('admin.categories.create')!!}"><i class="fa fa-circle-o"></i> {!! trans('global.create_categories') !!} </a></li>
                </ul>
            </li>

            <li class="{!! (Request::is('admin/address*') ? 'active' : '') !!} treeview">
                <a href="{!! route('admin.address.index')!!}">
                    <i class="fa fa-map-marker"></i> <span>{!! trans('global.address_manager') !!}</span> <i class="fa  fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="{!! (Request::is('admin/news*') ? 'active' : '') !!} treeview">
                <a href="{!! route('admin.news.index')!!}">
                    <i class="fa fa-dashboard"></i> <span>{!! trans('global.news') !!}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! (Request::is('admin/news') ? 'active' : '') !!}"><a href="{!! route('admin.news.index')!!}"><i class="fa fa-circle-o"></i> {!! trans('global.news') !!} </a></li>
                    <li class="{!! (Request::is('admin/news/create') ? 'active' : '') !!}"><a href="{!! route('admin.news.create')!!}"><i class="fa fa-circle-o"></i> {!! trans('global.create_news') !!} </a></li>
                </ul>
            </li>
            <li class="{!! (Request::is('admin/slider*') ? 'active' : '') !!} treeview">
                <a href="{!! route('admin.slider.index')!!}">
                    <i class="fa fa-image"></i> <span>{!! trans('global.slider_manager') !!}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>