<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return inertia('Subscription/Index', [
            'isSubscribed' => $user->subscription_ends_at && $user->subscription_ends_at->isFuture(),
            'subscriptionEndsAt' => $user->subscription_ends_at ? $user->subscription_ends_at->format('Y-m-d') : null,
        ]);
    }

    public function store()
    {
        $user = auth()->user();
        $user->subscription_ends_at = now()->addMonth();
        $user->save();

        return redirect()->back()->with('success', 'Subscribed successfully!');
    }
}
