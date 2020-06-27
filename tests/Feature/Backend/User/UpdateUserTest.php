<?php

namespace Tests\Feature\Backend\User;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UpdateUserTest.
 */
class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_access_the_edit_user_page()
    {
        $this->withoutMiddleware(RequirePassword::class);

        $this->loginAsAdmin();

        $user = factory(User::class)->create();

        $response = $this->get('/admin/auth/user/'.$user->id.'/edit');

        $response->assertOk();
    }

    /** @test */
    public function a_user_can_be_updated()
    {
        $this->withoutMiddleware(RequirePassword::class);

        $this->loginAsAdmin();

        $user = factory(User::class)->create();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->patch("/admin/auth/user/{$user->id}", [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'roles' => [
                Role::whereName(config('boilerplate.access.role.admin'))->first()->id,
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => Role::whereName(config('boilerplate.access.role.admin'))->first()->id,
            'model_type' => User::class,
            'model_id' => User::whereEmail('john@example.com')->first()->id,
        ]);
    }

    /** @test */
    public function only_the_master_admin_can_edit_themselves()
    {
        $this->withoutMiddleware(RequirePassword::class);

        $admin = $this->loginAsAdmin();

        $this->get("/admin/auth/user/{$admin->id}/edit")->assertOk();

        $this->logout();

        $otherAdmin = factory(User::class)->create();
        $otherAdmin->assignRole(config('boilerplate.access.role.admin'));

        $this->actingAs($otherAdmin);

        $response = $this->get("/admin/auth/user/{$admin->id}/edit");

        $response->assertSessionHas('flash_danger', __('Only the administrator can update this user.'));
    }

    /** @test */
    public function only_the_master_admin_can_update_themselves()
    {
        $this->withoutMiddleware(RequirePassword::class);

        $admin = $this->loginAsAdmin();

        $this->assertDatabaseMissing('users', [
            'id' => $admin->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->patch("/admin/auth/user/{$admin->id}", [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->logout();

        // Make sure other admins can not update the master admin

        $otherAdmin = factory(User::class)->create();
        $otherAdmin->assignRole(config('boilerplate.access.role.admin'));

        $this->actingAs($otherAdmin);

        $response = $this->patch("/admin/auth/user/{$admin->id}", [
            'id' => $admin->id,
            'name' => 'Changed Name',
            'email' => 'changed@example.com',
        ]);

        $response->assertSessionHas('flash_danger', __('Only the administrator can update this user.'));

        $this->assertDatabaseMissing('users', [
            'id' => $admin->id,
            'name' => 'Changed Name',
            'email' => 'changed@example.com',
        ]);
    }

    /** @test */
    public function the_master_admins_abilities_can_not_be_modified()
    {
        $this->withoutMiddleware(RequirePassword::class);

        $admin = $this->loginAsAdmin();

        $role = factory(Role::class)->create();

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $role->id,
            'model_type' => User::class,
            'model_id' => $admin->id,
        ]);

        $this->patch("/admin/auth/user/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'roles' => [$role->id],
        ]);

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $role->id,
            'model_type' => User::class,
            'model_id' => $admin->id,
        ]);
    }

    /** @test */
    public function only_admin_can_update_roles()
    {
        $this->actingAs(factory(User::class)->create());

        $user = factory(User::class)->create(['name' => 'John Doe']);

        $response = $this->patch("/admin/auth/user/{$user->id}", [
            'name' => 'Jane Doe',
        ]);

        $response->assertSessionHas('flash_danger', __('You do not have access to do that.'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Doe',
        ]);
    }
}
