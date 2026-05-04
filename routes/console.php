<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test', function () {
    Mail::raw('Test email từ ResDeli - Gmail SMTP hoạt động!', function ($m) {
        $m->to('lyanhtu0203@gmail.com')->subject('ResDeli - Test Mail ✅');
    });
    $this->info('✅ Email đã gửi tới lyanhtu0203@gmail.com');
})->purpose('Test Gmail SMTP');
