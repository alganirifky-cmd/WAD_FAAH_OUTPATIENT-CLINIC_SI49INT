<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DrugAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function drug(): Drug
    {
        return Drug::create([
            'drug_code' => 'DRG-001', 'drug_name' => 'Paracetamol', 'dosage' => '500mg',
            'category' => 'Analgesic', 'preparation_form' => 'Tablet', 'manufacturer' => 'PT ABC',
            'stock' => 10, 'unit' => 'Tablet', 'price' => 1000, 'expiry_date' => '2030-01-01',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('drugs.index'))->assertRedirect(route('login'));
        $this->get(route('drugs.create'))->assertRedirect(route('login'));
    }

    public function test_staff_can_view_but_not_modify(): void
    {
        $staff = $this->user('staff');
        $drug = $this->drug();

        $this->actingAs($staff)->get(route('drugs.index'))->assertOk();
        $this->actingAs($staff)->get(route('drugs.create'))->assertForbidden();
        $this->actingAs($staff)->delete(route('drugs.destroy', $drug))->assertForbidden();
        $this->assertDatabaseHas('drugs', ['id' => $drug->id]);
    }

    public function test_admin_can_view_and_modify(): void
    {
        $admin = $this->user('admin');
        $drug = $this->drug();

        $this->actingAs($admin)->get(route('drugs.index'))->assertOk();
        $this->actingAs($admin)->get(route('drugs.create'))->assertOk();
        $this->actingAs($admin)->delete(route('drugs.destroy', $drug))->assertRedirect(route('drugs.index'));
        $this->assertDatabaseMissing('drugs', ['id' => $drug->id]);
    }
}
