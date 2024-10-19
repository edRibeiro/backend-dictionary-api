<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_get_user_me_success()
    {
        // Cria um usuário no banco de dados
        $user = User::factory()->create([
            'name' => 'User 1',
            'email' => 'example@email.com',
        ]);

        // Faz login para obter o token
        $credentials = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        // Atualiza a senha do usuário no banco com bcrypt para garantir a autenticidade
        $user->update(['password' => bcrypt('password123')]);

        // Faz a requisição POST para a rota de signin para pegar o token
        $loginResponse = $this->postJson('/api/auth/signin', $credentials);
        $token = $loginResponse->json('token');

        // Faz a requisição GET para a rota /api/user/me com o token no header de autorização
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/user/me');

        // Verifica se o status retornado é 200 (OK)
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'
            ])
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_user_history_returns_paginated_words()
    {
        // Cria um usuário e faz autenticação
        $user = User::factory()->create();

        // Cria algumas palavras associadas ao usuário
        $words = Word::factory()->count(2)->create();
        foreach ($words as $word) {
            $user->history()->attach($word->id, ['created_at' => now()]);
        }

        // Autentica o usuário
        $this->actingAs($user);

        // Faz a requisição GET para a rota de histórico do usuário
        $response = $this->getJson('/api/user/me/history');

        // Verifica se o status retornado é 200 (OK)
        $response->assertStatus(200);

        // Verifica se a resposta tem a estrutura correta para os dados e paginação
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'word',
                    'added'
                ]
            ],
            'path',
            'per_page',
            'next_cursor',
            'next_page_url',
            'prev_cursor',
            'prev_page_url'
        ]);

        // Verifica se a quantidade de palavras retornadas está correta
        $this->assertCount(2, $response->json('data'));

        // Verifica os valores específicos da resposta
        $this->assertDatabaseHas('user_word', [
            'user_id' => $user->id,
            'word_id' => $words->first()->id,
        ]);
    }

    public function test_user_favorites_returns_paginated_words()
    {
        // Cria um usuário no banco de dados
        $user = User::factory()->create();

        // Adiciona algumas palavras para o usuário e marca como favoritas
        $word1 = Word::create(['word' => 'a', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        $word2 = Word::create(['word' => 'aa', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        $word3 = Word::create(['word' => 'aaa', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);

        // Associando as palavras ao usuário e marcando como favoritas
        $user->favorites()->attach($word1->id, ['created_at' => now()]);
        $user->favorites()->attach($word2->id, ['created_at' => now()]);
        $user->favorites()->attach($word3->id, ['created_at' => now()]); // Não favorito

        // Faz a requisição GET para a rota de favoritos do usuário
        $response = $this->actingAs($user)->getJson('/api/user/me/favorites');

        // Verifica se o status retornado é 200 (OK) e se as palavras estão na resposta
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['word', 'added']]]);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'word_id' => $word2->first()->id,
        ]);
    }
}
