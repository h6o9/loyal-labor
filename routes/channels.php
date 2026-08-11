<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('technician.{id}', function ($user, $id) {
    return $user->user_type === 'technician' && (int) $user->id === (int) $id;
});

Broadcast::channel('service-category.{categoryId}', function ($user, $categoryId) {
    if ($user->user_type !== 'technician') {
        return false;
    }

    return $user->serviceCategories()
        ->where('service_categories.id', (int) $categoryId)
        ->exists();
});

Broadcast::channel('livechat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('trackactiveuser', function ($user) {
    return $user->only('id');
});
