<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

uses()->group('integration', 'feature');

beforeEach(function () {
    $this->seed();
});

test('dashboard integration with following and pagination', function () {
    $user = User::factory()->create();
    $followedUser1 = User::factory()->create();
    $followedUser2 = User::factory()->create();
    $category = Category::first();

    $user->following()->attach([$followedUser1->id, $followedUser2->id]);

    $posts = Post::factory()->count(7)->create([
        'category_id' => $category->id,
        'published_at' => now()->subHour(),
    ]);

    $posts->take(5)->each(function ($post, $index) use ($followedUser1, $followedUser2) {
        $post->update([
            'user_id' => $index % 2 === 0 ? $followedUser1->id : $followedUser2->id,
        ]);
    });

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertOk();

    $response->assertViewHas('posts', function ($posts) {
        return $posts->count() > 0
            && $posts->first()->relationLoaded('user')
            && $posts->first()->relationLoaded('media');
    });
});

