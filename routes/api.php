<?php
use Illuminate\Support\Facades\Route;

route::post('/orders', [
  'uses' => 'App\Http\Controllers\OrderController@store',
  'as' => 'orders.store'
]);
route::get('/orders', [
  'uses' => 'App\Http\Controllers\OrderController@index',
  'as' => 'orders.index'
]);
route::put('/orders/{id}', [
  'uses' => 'App\Http\Controllers\OrderController@update',
  'as' => 'orders.update'
]);
route::get('/orders/stats', [
  'uses' => 'App\Http\Controllers\OrderController@getOrderStats',
  'as' => 'orders.getOrderStats'
]);