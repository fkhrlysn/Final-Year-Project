<style>
	.collapse a {
		text-indent: 10px;
	}

	nav#sidebar {
		background: url(assets/uploads/kereta.jpg) !important
	}
</style>

<nav id="sidebar" class='mx-lt-5 bg-dark'>

	<div class="sidebar-list">
		<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i
					class="fa fa-home"></i></span> Home</a>
		<a href="index.php?page=category" class="nav-item nav-category"><span class='icon-field'><i
					class="fa fa-list"></i></span> Category List</a>
		<a href="index.php?page=subjects" class="nav-item nav-subjects"><span class='icon-field'><i
					class="fa fa-book"></i></span> Subject List</a>
		<a href="index.php?page=instructor" class="nav-item nav-instructor"><span class='icon-field'><i
					class="fa fa-user-tie"></i></span> Instructor List</a>
		<a href="index.php?page=schedule" class="nav-item nav-schedule"><span class='icon-field'><i
					class="fa fa-calendar-day"></i></span> Schedule</a>
		<?php if ($_SESSION['login_type'] == 1): ?>
			<a href="index.php?page=admin" class="nav-item nav-admin"><span class='icon-field'><i
						class="fa fa-user"></i></span> Admin</a>
		<?php endif; ?>
		<a href="index.php?page=request" class="nav-item nav-request" class="nav-item nav-instructor"><span
				class='icon-field'><i class="fa fa-tasks"></i></span>Request</a>
	</div>
	<p style="color:black;"><sub><i>created by Muhammad Fakhrul Mukminin</i></sub></p>
</nav>
<script>
	$('.nav_collapse').click(function () {
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>