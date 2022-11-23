<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyAccountController extends Controller
{
    /**
     * Display the account view.
     *
     * @return \Illuminate\View\View
     */
    public function getAccountInfo()
    {
        $this->data['user'] = Auth::user();
        return view('my-account', $this->data);
    }

}
