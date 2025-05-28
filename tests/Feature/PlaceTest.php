<?php

namespace Tests\Feature;

use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlaceTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    public function test_it_can_list_all_places()
    {
        $places = Place::factory()->count(3)->create();

        $response = $this->getJson('/api/places');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id', 'name', 'slug', 'city', 'state', 'created_at', 'updated_at']
                ]
            ])
            ->assertJsonCount(3, 'data');
    }


    public function test_it_can_filter_places_by_name()
    {
        $place1 = Place::factory()->create(['name' => 'Central Park']);
        $place2 = Place::factory()->create(['name' => 'Golden Gate Bridge']);
        $place3 = Place::factory()->create(['name' => 'Statue of Liberty']);

        $response = $this->getJson('/api/places?name=park');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Central Park'])
            ->assertJsonMissing(['name' => 'Golden Gate Bridge'])
            ->assertJsonMissing(['name' => 'Statue of Liberty']);
    }


    public function test_it_can_show_a_specific_place()
    {
        $place = Place::factory()->create();

        $response = $this->getJson("/api/places/{$place->slug}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Place details retrieved successfully.',
                'data' => [
                    'id' => $place->id,
                    'name' => $place->name,
                    'slug' => $place->slug,
                    'city' => $place->city,
                    'state' => $place->state
                ]
            ]);
    }


    public function test_it_returns_404_for_nonexistent_place()
    {
        $response = $this->getJson('/api/places/nonexistent-slug');

        $response->assertStatus(404);
    }


    public function test_it_can_create_a_new_place()
    {
        $placeData = [
            'name' => 'Eiffel Tower',
            'city' => 'Paris',
            'state' => 'Île-de-France'
        ];

        $response = $this->postJson('/api/places', $placeData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Place created successfully.',
                'data' => [
                    'name' => 'Eiffel Tower',
                    'city' => 'Paris',
                    'state' => 'Île-de-France'
                ]
            ]);

        $this->assertDatabaseHas('places', $placeData);
    }


    public function test_it_validates_required_fields_when_creating_place()
    {
        $response = $this->postJson('/api/places', []);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Failed to create the place',
                'statusCode' => 400,
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'city' => [
                        'The city field is required.'
                    ],
                    'state' => [
                        'The state field is required.'
                    ]
                ]
            ]);
    }


    public function test_it_validates_name_uniqueness_when_creating_place()
    {
        Place::factory()->create(['name' => 'Duplicate Name']);

        $response = $this->postJson('/api/places', [
            'name' => 'Duplicate Name',
            'city' => 'Some City',
            'state' => 'Some State'
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Failed to create the place',
                'statusCode' => 400,
                'errors' => [
                    'name' => [
                        'The name has already been taken.'
                    ]
                ]
            ]);
    }


    public function test_it_can_update_an_existing_place()
    {
        $place = Place::factory()->create(['name' => 'Old Name']);

        $updateData = [
            'name' => 'New Name',
            'city' => 'New City',
            'state' => 'New State'
        ];

        $response = $this->putJson("/api/places/{$place->slug}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Place updated successfully.',
                'data' => [
                    'name' => 'New Name',
                    'city' => 'New City',
                    'state' => 'New State'
                ]
            ]);

        $this->assertDatabaseHas('places', $updateData);
    }


    public function test_it_validates_fields_when_updating_place()
    {
        $place = Place::factory()->create();

        $response = $this->putJson("/api/places/{$place->slug}", [
            'name' => '',
            'city' => '',
            'state' => ''
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Failed to update the place',
                'statusCode' => 400,
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'city' => [
                        'The city field is required.'
                    ],
                    'state' => [
                        'The state field is required.'
                    ]
                ]
            ]);
    }


    public function test_it_validates_name_uniqueness_when_updating_place()
    {
        $place1 = Place::factory()->create(['name' => 'Place One']);
        $place2 = Place::factory()->create(['name' => 'Place Two']);

        $response = $this->putJson("/api/places/{$place2->slug}", [
            'name' => 'Place One',
            'city' => $place2->city,
            'state' => $place2->state
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Failed to update the place',
                'statusCode' => 400,
                'errors' => [
                    'name' => [
                        'The name has already been taken.'
                    ]
                ]
            ]);
    }


    public function test_it_updates_slug_when_name_is_updated()
    {
        $place = Place::factory()->create(['name' => 'Original Name']);

        $response = $this->putJson("/api/places/{$place->slug}", [
            'name' => 'Updated Name'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('places', [
            'id' => $place->id,
            'name' => 'Updated Name',
            'slug' => 'updated-name'
        ]);
    }


    public function test_it_can_update_partial_fields()
    {
        $place = Place::factory()->create(['name' => 'Original Name', 'city' => 'Original City']);

        $response = $this->putJson("/api/places/{$place->slug}", [
            'name' => 'New Name'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'New Name',
                    'city' => 'Original City'
                ]
            ]);
    }
}
