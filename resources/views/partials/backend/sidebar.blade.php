
	<div class="page-content pt-0">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md align-self-start">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				<span class="font-weight-semibold">Main sidebar</span>
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- User menu -->
				<div class="sidebar-user-material">
					<div class="sidebar-user-material-body">
						<div class="card-body text-center">
							<a href="#">
								<img src="{{ asset('assets/images/user/user.jpg') }}" class="img-fluid rounded-circle shadow-2 mb-3" width="80" height="80" alt="">
							</a>
							<h6 class="mb-0 text-white text-shadow-dark">{{ auth()->user()->name }}</h6>
							{{-- <span class="font-size-sm text-white text-shadow-dark">egfdgdfg</span> --}}
						</div>
													
						<div class="sidebar-user-material-footer">
							<a href="#user-nav" class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle" data-toggle="collapse"><span>My account</span></a>
						</div>
					</div>

					<div class="collapse" id="user-nav">
						<ul class="nav nav-sidebar">
							<li class="nav-item">
								<a href="{{url('admin/user/show/')}}/{{ auth()->user()->id }}" class="nav-link">
									<i class="icon-user-plus"></i>
									<span>My profile</span>
								</a>
							</li>
							<li class="nav-item">
								<a href="{{url('admin/user')}}" class="nav-link">
									<i class="icon-people"></i>
									<span>Users List</span>
								</a>
							</li>
							<li class="nav-item">
								<a onClick="signOut()" class="nav-link">
									<i class="icon-switch2"></i>
									<span>Logout</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<!-- /user menu -->


				<!-- Navigation -->
				<div class="card card-sidebar-mobile">
						<div class="card-body p-0">
						<ul class="nav nav-sidebar" data-nav-type="accordion">
							<li class="nav-item"><a href="{{url('admin/')}}" class="nav-link"><i class="icon-home4"></i><span>Dashboard Home</span></a></li>
							 
						</ul>
						</div>
					<div class="card-header header-elements-inline">
						<h6 class="card-title">Main App</h6>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>

					<div class="card-body p-0">
						<ul class="nav nav-sidebar" data-nav-type="accordion">
							<li class="nav-item nav-item-submenu">
								<a href="#" class="nav-link"><i class="icon-stack2"></i> <span>Header Menu</span></a>
								<ul class="nav nav-group-sub" data-submenu-title="Header Menu">
									<li class="nav-item"><a href="{{url('admin/header-menu')}}" class="nav-link"> <span>List Menu</span></a></li>
									<li class="nav-item"><a href="{{ url('/admin/header-menu/create') }}" class="nav-link"> <span>Add New Menu</span></a></li>
								</ul>
							</li>
						 	<li class="nav-item nav-item-submenu">
								<a href="#" class="nav-link"><i class="icon-cash3"></i> <span>Banner Ads</span></a>
								<ul class="nav nav-group-sub" data-submenu-title="Banner Ads">
									<li class="nav-item"><a href="{{url('admin/banner-ads/')}}" class="nav-link"> <span>List Banner Ads</span></a></li>
									<li class="nav-item"><a href="{{url('admin/banner-ads/create/')}}" class="nav-link"> <span>New Banner Ads</span></a></li>
								</ul>
							</li>
							<li class="nav-item" data-html="true" data-content="<i class='icon-spinner10 spinner'></i> Headlines News" data-popup="popover" data-trigger="hover"><a href="{{url('admin/headline')}}" class="nav-link"><i class="icon-spinner10 spinner"></i> <span>Headlines News</span></a></li>
							<li class="nav-item" data-html="true" data-content="<i class='icon-file-text2'></i> Upload File" data-popup="popover" data-trigger="hover"><a href="{{url('admin/file')}}" class="nav-link"><i class="icon-file-text2"></i> <span>Upload File</span></a></li>
							<li class="nav-item" data-html="true" data-content="<i class='icon-media'></i> Media" data-popup="popover" data-trigger="hover"><a href="{{url('admin/media/')}}" class="nav-link"><i class="icon-media"></i> <span>Media</span></a></li>
							
						</ul> 
					</div>
				</div>
				<!-- /navigation -->
			 
				<!-- Navigation -->
				<div class="card card-sidebar-mobile">
					<div class="card-header header-elements-inline">
						<h6 class="card-title">Main Web</h6>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>

					<div class="card-body p-0">
						<ul class="nav nav-sidebar" data-nav-type="accordion">
							<li class="nav-item nav-item-submenu">
								<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Posts</span></a>
								<ul class="nav nav-group-sub" data-submenu-title="Artikels">
									<li class="nav-item"><a href="{{url('admin/article')}}" class="nav-link"> <span>All Posts</span></a></li>
									<li class="nav-item"><a href="{{url('admin/article/create')}}" class="nav-link"> <span>New Post</span></a></li>
								</ul>
							</li>
							<li class="nav-item nav-item-submenu">
								<a href="#" class="nav-link"><i class="icon-list-unordered"></i> <span>Category</span></a>
								<ul class="nav nav-group-sub" data-submenu-title="Kategori">
									<li class="nav-item"><a href="{{url('admin/category')}}" class="nav-link"> <span>Categories</span></a></li>
									<li class="nav-item"><a href="{{url('admin/category/create')}}" class="nav-link"> <span>New Category</span></a></li>
								</ul>
							</li>
							<li class="nav-item nav-item-submenu">
								<a href="#" class="nav-link"><i class="icon-price-tags2"></i> <span>Tags</span></a>
								<ul class="nav nav-group-sub" data-submenu-title="Tags">
									<li class="nav-item"><a href="{{url('admin/tag')}}" class="nav-link"> <span>List Tags</span></a></li>
									<li class="nav-item"><a href="{{url('admin/tag/create')}}" class="nav-link"> <span>New Tag</span></a></li>
								</ul>
							</li>
							<li class="nav-item nav-item-submenu">
								<a href="#" class="nav-link"><i class="icon-book"></i> <span>Pages</span></a>
								<ul class="nav nav-group-sub" data-submenu-title="Tags">
									<li class="nav-item"><a href="{{url('admin/page')}}" class="nav-link"> <span>List Pages</span></a></li>
									<li class="nav-item"><a href="{{url('admin/page/create')}}" class="nav-link"> <span>New Page</span></a></li>
								</ul>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link"><i class="icon-book"></i> <span>Contacts</span></a>
							</li>
							{{-- <li class="nav-item"><a href="{{url('admin/page/create')}}" class="nav-link"> <span>Contacts</span></a></li> --}}
							
						</ul> 
					</div>
				</div>
				<!-- /navigation -->
				
				<!-- Navigation -->
				<div class="card card-sidebar-mobile">
					<div class="card-header header-elements-inline">
						<h6 class="card-title">Setting</h6>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>

					<div class="card-body p-0">
						<ul class="nav nav-sidebar" data-nav-type="accordion">
							<li class="nav-item" data-html="true" data-content="<i class='icon-puzzle2'></i> Content Styling" data-popup="popover" data-trigger="hover"><a href="{{url('admin/styling')}}" class="nav-link"><i class="icon-puzzle2"></i> <span>Theme</span></a></li> 
							<li class="nav-item" data-html="true" data-content="<i class='icon-cog3'></i> Global Setting" data-popup="popover" data-trigger="hover"><a href="{{url('admin/setting')}}" class="nav-link"><i class="icon-cog3"></i> <span>Setting</span></a></li>
						</ul> 
					</div>
				</div>
				<!-- /navigation -->
			</div>
			<!-- /sidebar content -->
			
		</div>	
			
			 
				
					
			 
			