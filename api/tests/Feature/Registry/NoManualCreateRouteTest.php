<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Ingestion is source-only (bulk import + REST intake): there must be NO manual
 * single-record create endpoint for beneficiaries or households anywhere.
 */
class NoManualCreateRouteTest extends TestCase
{
    public function test_no_named_create_route_is_registered(): void
    {
        $this->assertFalse(Route::has('beneficiaries.store'), 'A beneficiaries create route still exists.');
        $this->assertFalse(Route::has('households.store'), 'A households create route still exists.');
    }

    public function test_posting_to_the_collection_uris_is_not_allowed(): void
    {
        // GET exists on these URIs but POST (create) does not → 405, never 201.
        $this->postJson('/api/v1/beneficiaries', [])->assertStatus(405);
        $this->postJson('/api/v1/households', [])->assertStatus(405);
    }
}
