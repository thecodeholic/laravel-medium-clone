<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

uses()->group('profile', 'feature');

beforeEach(function () {
    $this->seed();
});

test('public profile shows user published posts', function () {
    $user = User::factory()->create();
    $category = Category::first();

    $publishedPost = Post::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'published_at' => now()->subDay(),
    ]);

    $unpublishedPost = Post::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'published_at' => null,
    ]);

    $response = $this->get(route('profile.show', ['user' => $user]));

    $response->assertOk();
    $response->assertSee($publishedPost->title);
    $response->assertDontSee($unpublishedPost->title);
});

