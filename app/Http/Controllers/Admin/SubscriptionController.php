<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Lawyer;

class SubscriptionController extends Controller
{
    public function index()
    {
        // Fetch subscriptions with associated lawyer data
        $subscriptions = Subscription::with('lawyer')->get();

        // Return view with data
        return view('admin.subscriptions.index', compact('subscriptions'));
    }
}
