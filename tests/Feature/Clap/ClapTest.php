<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

uses()->group('clap', 'feature');

beforeEach(function () {
    $this->seed();
});

test('user can clap and unclap post', function () {
    $user = User::factory()->create();
    $category = Category::first();
    $post = Post::factory()->create([
        'category_id' => $category->id,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->actingAs($user)
        ->post(route('clap', ['post' => $post]));

    $response->assertOk();
    $response->assertJson(['clapsCount' => 1]);
    $this->assertDatabaseHas('claps', [
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    $response = $this->actingAs($user)
        ->post(route('clap', ['post' => $post]));

    $response->assertOk();
    $response->assertJson(['clapsCount' => 0]);
    $this->assertDatabaseMissing('claps', [
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);
});

test('guest cannot clap post', function () {
    User::factory()->create();
    $category = Category::first();
    $post = Post::factory()->create([
        'category_id' => $category->id,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->post(route('clap', ['post' => $post]));

    $response->assertRedirect(route('login'));
});

