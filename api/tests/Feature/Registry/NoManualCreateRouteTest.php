<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Http\Controllers\Api\V1\Registry\BeneficiaryController;
use App\Http\Controllers\Api\V1\Registry\HouseholdController;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use Tests\TestCase;

/**
 * Ingestion is source-only (CLAUDE.md §8): there must be NO manual single-record
 * create endpoint for beneficiaries or households anywhere.
 *
 * Guarded on four levels because each one can reintroduce the path on its own — a
 * bare `Route::apiResource`, a hand-written POST, or a `store()` method that a later
 * route change makes reachable. The URI check alone would miss a create route
 * registered under a different path.
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

    /**
     * No POST anywhere resolves to a `store` action on either controller — this is
     * what a stray `Route::apiResource('beneficiaries', ...)` would trip, wherever it
     * were mounted and whatever it were named.
     */
    public function test_no_route_points_at_a_create_action_on_either_controller(): void
    {
        $forbidden = [BeneficiaryController::class.'@store', HouseholdController::class.'@store'];

        foreach (Route::getRoutes() as $route) {
            $action = $route->getActionName();

            $this->assertNotContains(
                $action,
                $forbidden,
                "A create action is routed at [{$route->uri()}].",
            );
        }
    }

    /**
     * The controllers do not even define a create action, so one cannot be wired up by
     * adding a route line alone.
     */
    public function test_neither_controller_defines_a_store_method(): void
    {
        foreach ([BeneficiaryController::class, HouseholdController::class] as $controller) {
            $this->assertFalse(
                (new ReflectionClass($controller))->hasMethod('store'),
                "{$controller} still defines a store() method.",
            );
        }
    }

    /**
     * The ingestion doors that MUST remain — removing manual entry must not have
     * closed the source paths, or nothing could enter the registry at all.
     */
    public function test_the_source_ingestion_doors_are_still_registered(): void
    {
        foreach ([
            'beneficiaries.imports.store',   // Excel/CSV/Kobo/ODK upload
            'beneficiaries.intake',          // REST API source
            'sync.offline-batches',          // offline capture flush
        ] as $name) {
            $this->assertTrue(Route::has($name), "The {$name} ingestion route is missing.");
        }
    }
}
