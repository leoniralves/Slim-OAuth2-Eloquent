<?php

namespace RelationalExample\Config;

include __DIR__.'/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => 'sqlite',
    'database'  => __DIR__.'/oauth2.sqlite3',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);

$capsule->setAsGlobal();

@unlink(__DIR__.'/oauth2.sqlite3');
touch(__DIR__.'/oauth2.sqlite3');

Capsule::statement('PRAGMA foreign_keys = ON');

/******************************************************************************/

print 'Creating users table'.PHP_EOL;

Capsule::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->string('username');
    $table->string('password');
    $table->string('name');
    $table->string('email');
    $table->string('photo');
});

Capsule::table('users')->insert([
    'username'  =>  'usertest',
    'password'  =>  password_hash('test', PASSWORD_DEFAULT),
    'name'      =>  'User Test',
    'email'     =>  'email@restful.co.uk',
    'photo'     =>  'url photo',
]);

/******************************************************************************/

print 'Creating clients table'.PHP_EOL;

Capsule::schema()->create('oauth_clients', function ($table) {
    $table->string('id');
    $table->string('secret');
    $table->string('name');
    $table->primary('id');
});

Capsule::table('oauth_clients')->insert([
    'id'        =>  'testclient',
    'secret'    =>  'secret',
    'name'      =>  'Test Client',
]);

/******************************************************************************/

print 'Creating client redirect uris table'.PHP_EOL;

Capsule::schema()->create('oauth_client_redirect_uris', function ($table) {
    $table->increments('id');
    $table->string('client_id');
    $table->string('redirect_uri');
});

Capsule::table('oauth_client_redirect_uris')->insert([
    'client_id'     =>  'testclient',
    'redirect_uri'  =>  'http://example.com/redirect',
]);

/******************************************************************************/

print 'Creating scopes table'.PHP_EOL;

Capsule::schema()->create('oauth_scopes', function ($table) {
    $table->string('id');
    $table->string('description');
    $table->primary('id');
});

Capsule::table('oauth_scopes')->insert([
    'id'            =>  'basic',
    'description'   =>  'Basic details about your account',
]);

Capsule::table('oauth_scopes')->insert([
    'id'            =>  'email',
    'description'   =>  'Your email address',
]);

Capsule::table('oauth_scopes')->insert([
    'id'            =>  'photo',
    'description'   =>  'Your photo',
]);

/******************************************************************************/

print 'Creating sessions table'.PHP_EOL;

Capsule::schema()->create('oauth_sessions', function ($table) {
    $table->increments('id')->unsigned();
    $table->string('owner_type');
    $table->string('owner_id');
    $table->string('client_id');
    $table->string('client_redirect_uri')->nullable();

    $table->foreign('client_id')->references('id')->on('oauth_clients')->onDelete('cascade');
});

Capsule::table('oauth_sessions')->insert([
    'owner_type'    =>  'client',
    'owner_id'      =>  'testclient',
    'client_id'     =>  'testclient',
]);

Capsule::table('oauth_sessions')->insert([
    'owner_type'    =>  'user',
    'owner_id'      =>  '1',
    'client_id'     =>  'testclient',
]);

Capsule::table('oauth_sessions')->insert([
    'owner_type'    =>  'user',
    'owner_id'      =>  '2',
    'client_id'     =>  'testclient',
]);

/******************************************************************************/

print 'Creating access tokens table'.PHP_EOL;

Capsule::schema()->create('oauth_access_tokens', function ($table) {
    $table->string('access_token')->primary();
    $table->integer('session_id')->unsigned();
    $table->integer('expire_time');

    $table->foreign('session_id')->references('id')->on('oauth_sessions')->onDelete('cascade');
});

Capsule::table('oauth_access_tokens')->insert([
    'access_token'  =>  'iamgod',
    'session_id'    =>  '1',
    'expire_time'   =>  time() + 86400,
]);

/******************************************************************************/

print 'Creating refresh tokens table'.PHP_EOL;

Capsule::schema()->create('oauth_refresh_tokens', function ($table) {
    $table->string('refresh_token')->primary();
    $table->integer('expire_time');
    $table->string('access_token');

    $table->foreign('access_token')->references('access_token')->on('oauth_access_tokens')->onDelete('cascade');
});

/******************************************************************************/

print 'Creating auth codes table'.PHP_EOL;

Capsule::schema()->create('oauth_auth_codes', function ($table) {
    $table->string('auth_code')->primary();
    $table->integer('session_id')->unsigned();
    $table->integer('expire_time');
    $table->string('client_redirect_uri');

    $table->foreign('session_id')->references('id')->on('oauth_sessions')->onDelete('cascade');
});

/******************************************************************************/

print 'Creating oauth access token scopes table'.PHP_EOL;

Capsule::schema()->create('oauth_access_token_scopes', function ($table) {
    $table->increments('id')->unsigned();
    $table->string('access_token');
    $table->string('scope');

    $table->foreign('access_token')->references('access_token')->on('oauth_access_tokens')->onDelete('cascade');
    $table->foreign('scope')->references('id')->on('oauth_scopes')->onDelete('cascade');
});

Capsule::table('oauth_access_token_scopes')->insert([
    'access_token'  =>  'iamgod',
    'scope'         =>  'basic',
]);

Capsule::table('oauth_access_token_scopes')->insert([
    'access_token'  =>  'iamgod',
    'scope'         =>  'email',
]);

Capsule::table('oauth_access_token_scopes')->insert([
    'access_token'  =>  'iamgod',
    'scope'         =>  'photo',
]);

/******************************************************************************/

print 'Creating oauth auth code scopes table'.PHP_EOL;

Capsule::schema()->create('oauth_auth_code_scopes', function ($table) {
    $table->increments('id');
    $table->string('auth_code');
    $table->string('scope');

    $table->foreign('auth_code')->references('auth_code')->on('oauth_auth_codes')->onDelete('cascade');
    $table->foreign('scope')->references('id')->on('oauth_scopes')->onDelete('cascade');
});

/******************************************************************************/

print 'Creating oauth session scopes table'.PHP_EOL;

Capsule::schema()->create('oauth_session_scopes', function ($table) {
    $table->increments('id')->unsigned();
    $table->integer('session_id')->unsigned();
    $table->string('scope');

    $table->foreign('session_id')->references('id')->on('oauth_sessions')->onDelete('cascade');
    $table->foreign('scope')->references('id')->on('oauth_scopes')->onDelete('cascade');
});
