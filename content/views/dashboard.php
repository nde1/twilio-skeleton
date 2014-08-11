<h3>Hello, <?= $me->get_firstname()?></h3>


<div class="list-group">
	<a href="<?=$uri?>/calls" class="list-group-item">
		<h4 class="list-group-item-heading">Call Log</h4>
		<p class="list-group-item-text">
			View a log of calls to your Twilio Account
		</p>
	</a>
	<a href="<?=$uri?>/user" class="list-group-item">
		<h4 class="list-group-item-heading">List Users</h4>
		<p class="list-group-item-text">
			View a list of users
		</p>
	</a>
	<a  class="list-group-item modalButton" data-toggle="modal" data-src="<?=$uri?>/user/new?embed=1" data-title="Edit Profile" data-target="#modalbox">
		<h4 class="list-group-item-heading">Add New User</h4>
		<p class="list-group-item-text">
			Add a new user
		</p>
	</a>
	<a  class="list-group-item modalButton" data-toggle="modal" data-src="<?=$uri?>/user/edit/<?php echo $me->id?>?embed=1" data-title="Edit Profile" data-target="#modalbox">
		<h4 class="list-group-item-heading">Edit Profile</h4>
		<p class="list-group-item-text">
			Edit Your Profile
		</p>
	</a>
	<a href="<?=$uri?>/logout" class="list-group-item">
		<h4 class="list-group-item-heading">Logout</h4>
		<p class="list-group-item-text">
		</p>
	</a>
</div>