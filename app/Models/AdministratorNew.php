<?php

namespace App\Models;


use Dcat\Admin\Models\Administrator;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class AdministratorNew extends Administrator
{
    protected $fillable = ['username', 'password', 'name', 'avatar', 'area', 'phone', 'job_number', 'company_codes'];
}
