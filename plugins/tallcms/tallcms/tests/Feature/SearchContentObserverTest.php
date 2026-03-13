<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchContentObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_does_not_fail_when_non_loaded_attributes_are_accessed_during_save(): void
    {
        $user = User::factory()->create();

        $page = CmsPage::factory()->create([
            'author_id' => $user->id,
        ]);

        $partiallyLoadedPage = CmsPage::query()
            ->select(['id', 'title', 'content', 'meta_title', 'meta_description'])
            ->findOrFail($page->id);

        $partiallyLoadedPage->meta_title = 'Updated meta title';
        $partiallyLoadedPage->save();

        $page->refresh();

        $this->assertNotNull($page->search_content);
    }
}
