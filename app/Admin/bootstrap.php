<?php

use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Show;
use Dcat\Admin\Layout\Navbar;

/**
 * Dcat-admin - admin builder based on Laravel.
 * @author jqh <https://github.com/jqhph>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 *
 * extend custom field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Column::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Admin::navbar(function (Navbar $navbar) {

    // 下拉面板--消息通知
    $data = [
        ['title' => 'You have new order!', 'type'=>'App Notifications','text' => 'Are your going to meet me tonight?', 'created_at' => '2022-02-01 00:00:00'],
        ['title' => 'You have new order!', 'type'=>'App Notifications','text' => 'Are your going to meet me tonight?', 'created_at' => '2022-02-01 00:00:00'],

    ];
    $navbar->right(view('admin.notification', ['notifications' => $data]));
});
