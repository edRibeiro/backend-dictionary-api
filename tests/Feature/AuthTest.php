<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Testa o sucesso no registro de um usuário.
     *
     * @return void
     */
    public function test_signup_success()
    {
        // Dados para o registro do usuário
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Faz a requisição POST para a rota de signup
        $response = $this->postJson('/api/auth/signup', $userData);

        // Verifica se o status retornado é 201 (Created)
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]);

        // Verifica se o usuário foi salvo no banco de dados
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    /**
     * Testa erro de validação no registro (campo faltando).
     *
     * @return void
     */
    public function test_signup_validation_error()
    {
        // Dados incompletos (faltando 'email')
        $userData = [
            'name' => 'Jane Doe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Faz a requisição POST para a rota de signup
        $response = $this->postJson('/api/auth/signup', $userData);

        // Verifica se o status retornado é 422 (Unprocessable Entity)
        $response->assertStatus(422)
            ->assertJson([
                'email' => ['The email field is required.']
            ]);
    }

    /**
     * Testa erro ao tentar registrar um usuário com e-mail já existente.
     *
     * @return void
     */
    public function test_signup_email_already_exists_error()
    {
        // Cria um usuário no banco de dados
        User::factory()->create([
            'email' => 'existinguser@example.com',
        ]);

        // Dados para o registro do usuário com o mesmo e-mail
        $userData = [
            'name' => 'Jane Doe',
            'email' => 'existinguser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Faz a requisição POST para a rota de signup
        $response = $this->postJson('/api/auth/signup', $userData);

        // Verifica se o status retornado é 422 (Unprocessable Entity)
        $response->assertStatus(422)
            ->assertJson([
                'email' => ["The email has already been taken."]
            ]);
    }

    public function test_signin_success()
    {
        // Cria um usuário no banco de dados
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        // Dados de login válidos
        $credentials = [
            'email' => 'user@example.com',
            'password' => 'password123',
        ];

        // Faz a requisição POST para a rota de signin
        $response = $this->postJson('/api/auth/signin', $credentials);

        // Verifica se o status retornado é 200 (OK)
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'token'
            ]);
    }

    public function test_signin_unauthorized()
    {
        // Dados de login inválidos
        $credentials = [
            'email' => 'wronguser@example.com',
            'password' => 'wrongpassword',
        ];

        // Faz a requisição POST para a rota de signin
        $response = $this->postJson('/api/auth/signin', $credentials);

        // Verifica se o status retornado é 401 (Unauthorized)
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized'
            ]);
    }
}
