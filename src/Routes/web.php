<?php
Route::get('coming-soon', 'ComingSoonController@index')->name('comingsoon.index');
Route::post('coming-soon/token', 'ComingSoonController@token')->name('comingsoon.token');