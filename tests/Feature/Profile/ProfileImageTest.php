<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob;

uses()->group('profile', 'feature');

test('user can update profile with image', function () {
    $user = User::factory()->create();

    Storage::fake('public');
    Queue::fake();
    $image = UploadedFile::fake()->image('avatar.jpg', 200, 200);

    $response = $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'image' => $image,
        ]);

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('status', 'profile-updated');

    $user->refresh();
    expect($user->getFirstMedia('avatar'))->not->toBeNull();

    Queue::assertPushed(PerformConversionsJob::class);
});

