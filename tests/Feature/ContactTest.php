<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Tests\TestCase;

class ContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_to_make_a_new_contact(): void
    {
        $this->withoutExceptionHandling();

        $contact = Contact::factory()->make();

        $this->actingAs(User::factory()->create());
        $this->json("POST", "/api/contact", $contact->toArray(), [
            'Content-Type' => 'application/json',
        ])->assertCreated();
    }

    public function test_to_update_a_contact()
    {
        $contact = Contact::factory()->create();

        $newDetails = Contact::factory()->make()->toArray();

        $this->actingAs(User::factory()->create());

        $this->json("PATCH", "/api/contact/{$contact->id}", $newDetails, [
            'Content-Type' => 'application/json',
        ]);

        $this->assertDatabaseMissing('contacts', [
            'name' => $contact->name,
            'phone_number' => $contact->phone_number,
        ]);

    }

    public function test_to_delete_a_contact()
    {
        $this->withoutExceptionHandling();
        $contact = Contact::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->delete("/api/contact/{$contact->id}");

        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }

    public function test_to_list_all_contacts()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());
        $response = $this->get('api/contact');
        $response->assertJsonFragment(['per_page' => 20]);
    }

    public function test_to_retrieve_a_single_contact() {

        $contact = Contact::factory()->create();

        $this->actingAs(User::factory()->create());
        $this->get("api/contact/{$contact->id}")->assertJson([
            'name' => $contact->name,
        ]);
        
    }

}
