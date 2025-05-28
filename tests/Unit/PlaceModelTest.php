<?php

namespace Tests\Unit;

use App\Models\Place;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaceModelTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_it_automatically_generates_slug_on_creation()
    {
        $place = Place::create([
            'name' => 'Test Place',
            'city' => 'Test City',
            'state' => 'Test State'
        ]);

        $this->assertEquals('test-place', $place->slug);
    }

    
    public function test_it_updates_slug_when_name_changes()
    {
        $place = Place::create([
            'name' => 'Original Name',
            'city' => 'Test City',
            'state' => 'Test State'
        ]);

        $place->update(['name' => 'Updated Name']);

        $this->assertEquals('updated-name', $place->slug);
    }

    
    public function test_it_does_not_update_slug_when_other_fields_change()
    {
        $place = Place::create([
            'name' => 'Original Name',
            'city' => 'Test City',
            'state' => 'Test State'
        ]);

        $originalSlug = $place->slug;
        $place->update(['city' => 'New City']);

        $this->assertEquals($originalSlug, $place->fresh()->slug);
    }

    
    public function test_it_uses_slug_for_route_key()
    {
        $place = new Place();

        $this->assertEquals('slug', $place->getRouteKeyName());
    }
}
