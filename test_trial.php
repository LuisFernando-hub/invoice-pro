<?php

use App\Models\User;
use App\Models\Invoice;
use Carbon\Carbon;

// Create a user who just registered (Trial Active)
$userActive = User::factory()->create([
    'name' => 'Trial Active User',
    'email' => 'active' . time() . '@example.com',
    'created_at' => now(),
]);

echo "User Active: " . ($userActive->onTrial() ? 'PASS' : 'FAIL') . "\n";
echo "User Active Can Create: " . ($userActive->canCreateInvoice() ? 'PASS' : 'FAIL') . "\n";

// Create a user who registered 4 months ago (Trial Expired)
$userExpired = User::factory()->create([
    'name' => 'Trial Expired User',
    'email' => 'expired' . time() . '@example.com',
    'created_at' => now()->subMonths(4),
]);

echo "User Expired: " . (!$userExpired->onTrial() ? 'PASS' : 'FAIL') . "\n";
echo "User Expired Can Create (No Sub): " . (!$userExpired->canCreateInvoice() ? 'PASS' : 'FAIL') . "\n";

// Subscribe the expired user
$userExpired->subscription_ends_at = now()->addMonth();
$userExpired->save();

echo "User Expired Can Create (With Sub): " . ($userExpired->canCreateInvoice() ? 'PASS' : 'FAIL') . "\n";
