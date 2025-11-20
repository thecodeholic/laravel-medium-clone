<?php

use App\Models\User;

uses()->group('follower', 'feature');

test('user can follow and unfollow another user', function () {
    $user = User::factory()->create();
    $userToFollow = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('follow', ['user' => $userToFollow]));

    $response->assertOk();
    $response->assertJson(['followersCount' => 1]);
    $this->assertTrue($userToFollow->isFollowedBy($user));

    $response = $this->actingAs($user)
        ->post(route('follow', ['user' => $userToFollow]));

    $response->assertOk();
    $response->assertJson(['followersCount' => 0]);
    $this->assertFalse($userToFollow->isFollowedBy($user));
});

test('guest cannot follow user', function () {
    $userToFollow = User::factory()->create();

    $response = $this->post(route('follow', ['user' => $userToFollow]));

    $response->assertRedirect(route('login'));
});

