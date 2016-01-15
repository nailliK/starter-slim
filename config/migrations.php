<?php

// users
if (!$capsule->schema()->hasTable('users')) {
	$capsule->schema()->create('users', function ($table) {
	    $table->increments('id');
	    $table->string('email');
	    $table->string('password');
	    $table->text('permissions')->nullable();
	    $table->timestamp('last_login')->nullable();
	    $table->string('first_name')->nullable();
	    $table->string('last_name')->nullable();
		$table->string('phone_number')->nullable();
		$table->string('address')->nullable();
		$table->string('address_2')->nullable();
		$table->string('city')->nullable();
		$table->string('state')->nullable();
		$table->string('zip')->nullable();
		$table->integer('parent_id')->nullable();
	    $table->timestamps();

	    $table->engine = 'InnoDB';
	    $table->unique('email');
	});
}

// user activations
if (!$capsule->schema()->hasTable('activations')) {
	$capsule->schema()->create('activations', function ($table) {
	    $table->increments('id');
	    $table->integer('user_id')->unsigned();
	    $table->string('code');
	    $table->boolean('completed')->default(0);
	    $table->timestamp('completed_at')->nullable();
	    $table->timestamps();

	    $table->engine = 'InnoDB';
	});
}

// persistent logins
if (!$capsule->schema()->hasTable('persistences')) {
	$capsule->schema()->create('persistences', function ($table) {
	    $table->increments('id');
	    $table->integer('user_id')->unsigned();
	    $table->string('code');
	    $table->timestamps();

	    $table->engine = 'InnoDB';
	    $table->unique('code');
	});
}

// login throttle
if (!$capsule->schema()->hasTable('throttle')) {
	$capsule->schema()->create('throttle', function ($table) {
	    $table->increments('id');
	    $table->integer('user_id')->unsigned()->nullable();
	    $table->string('type');
	    $table->string('ip')->nullable();
	    $table->timestamps();

	    $table->engine = 'InnoDB';
	    $table->index('user_id');
	});
}

// email reminders
if (!$capsule->schema()->hasTable('reminders')) {
	$capsule->schema()->create('reminders', function ($table) {
	    $table->increments('id');
	    $table->integer('user_id')->unsigned();
	    $table->string('code');
	    $table->boolean('completed')->default(0);
	    $table->timestamp('completed_at')->nullable();
	    $table->timestamps();
	});
}

// roles
if (!$capsule->schema()->hasTable('roles')) {
	$capsule->schema()->create('roles', function ($table) {
	    $table->increments('id');
	    $table->string('slug');
	    $table->string('name');
	    $table->text('permissions')->nullable();
	    $table->timestamps();

	    $table->engine = 'InnoDB';
	    $table->unique('slug');
	});
	
	// create default roles
	$container->sentinel->getRoleRepository()->createModel()->create(array(
	    'name'          => 'Admin',
	    'slug'          => 'admin',
	    'permissions'   => array()
	));
	$container->sentinel->getRoleRepository()->createModel()->create(array(
	    'name'          => 'Consultant',
	    'slug'          => 'consultant',
	    'permissions'   => array()
	));
	$container->sentinel->getRoleRepository()->createModel()->create(array(
	    'name'          => 'Customer',
	    'slug'          => 'customer',
	    'permissions'   => array()
	));
}

// roles -> users
if (!$capsule->schema()->hasTable('role_users')) {
	$capsule->schema()->create('role_users', function ($table) {
	    $table->integer('user_id')->unsigned();
	    $table->integer('role_id')->unsigned();
	    $table->nullableTimestamps();

	    $table->engine = 'InnoDB';
	    $table->primary(['user_id', 'role_id']);
	});
}