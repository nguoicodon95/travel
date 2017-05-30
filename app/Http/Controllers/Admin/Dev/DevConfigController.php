<?php

namespace App\Http\Controllers\Admin\Dev;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseAdminController;

use Session;
use Artisan;

class DevConfigController extends BaseAdminController
{
    public function __construct () {
        parent::__construct();
        $this->middleware('is_webmaster');
    }

    public function clearConfig() {
    	Artisan::call('config:clear');
    	return redirect()->back()->with('msg', 'Clear config successfully');
    }
    public function cacheConfig() {
    	Artisan::call('config:cache');
    	return redirect()->back()->with('msg', 'Cache config successfully.');
    }
}
