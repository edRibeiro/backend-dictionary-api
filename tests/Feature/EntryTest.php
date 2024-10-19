<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EntryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $token;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->authenticateAndGetToken();
    }

    /**
     * Autentica um usuário e retorna o token JWT.
     *
     * @return string
     */
    protected function authenticateAndGetToken()
    {
        // Cria um usuário no banco de dados
        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Dados de login válidos
        $credentials = [
            'email' => 'user@example.com',
            'password' => 'password123',
        ];

        // Faz a requisição POST para a rota de login e obtém o token
        $response = $this->postJson('/api/auth/signin', $credentials);

        return $response->json('token');
    }

    public function test_entries_en_returns_paginated_words()
    {
        // Cria algumas entradas no banco de dados para palavras em inglês
        Word::create(['word' => 'deb', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'debacle', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'debt', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);

        // Faz a requisição GET para a rota de entradas com o token de autorização
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/entries/en');

        // Verifica se o status retornado é 200 (OK)
        $response->assertStatus(200);

        // Verifica se as palavras retornadas são as esperadas
        $response->assertSeeInOrder(['deb', 'debacle']);
    }

    public function test_entries_en_search_returns_filtered_words_for_authenticated_user()
    {
        // Cria algumas entradas no banco de dados para palavras em inglês
        Word::create(['word' => 'deb', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'debacle', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'debt', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'decision', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);

        // Define a palavra de pesquisa
        $searchWord = 'deb';

        // Faz a requisição GET para a rota de entradas com o parâmetro de pesquisa e o token de autorização
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/entries/en?search=' . $searchWord);

        // Verifica se o status retornado é 200 (OK)
        $response->assertStatus(200);

        // Verifica se as palavras 'deb', 'debacle', e 'debt' estão na resposta (que começam com 'deb')
        $response->assertSeeInOrder(['deb', 'debacle', 'debt']);

        // Verifica se palavras que não correspondem à pesquisa, como 'decision', não estão presentes
        $response->assertDontSee('decision');
    }

    public function test_entries_en_search_with_limit_returns_limited_words_for_authenticated_user()
    {
        // Cria algumas entradas no banco de dados para palavras em inglês
        Word::create(['word' => 'deb', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'debacle', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'debt', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'decision', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);
        Word::create(['word' => 'deduction', 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);

        // Define a palavra de pesquisa e o limite
        $searchWord = 'de';
        $limit = 2;

        // Faz a requisição GET para a rota de entradas com o parâmetro de pesquisa e o limite
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/entries/en?search=' . $searchWord . '&limit=' . $limit);

        // Verifica se o status retornado é 200 (OK)
        $response->assertStatus(200);

        // Verifica se o JSON contém exatamente 2 palavras, conforme o limite especificado
        $response->assertJsonCount($limit, 'data');

        // Verifica se as palavras retornadas começam com 'de' e estão no limite
        $response->assertJsonFragment([
            'data' => ['deb', 'debacle']
        ]);

        // Verifica se palavras adicionais como 'decision' ou 'deduction' não aparecem, pois excedem o limite
        $response->assertDontSee('decision');
        $response->assertDontSee('deduction');
    }

    public function test_entry_en_specific_word_returns_correct_word_data()
    {
        // Define a palavra que será buscada
        $word = 'abelian';

        // Mock da resposta esperada
        Http::fake([
            '/api/entries/en/' . $word => Http::response([
                'data' => [
                    'id' => 15,
                    'word' => 'abelian',
                    'license' => 'CC BY-SA 3.0',
                    'license_url' => 'https://creativecommons.org/licenses/by-sa/3.0',
                    'created_at' => '2024-10-17T00:55:02.000000Z',
                    'updated_at' => '2024-10-17T00:55:02.000000Z',
                    'meanings' => [
                        [
                            'id' => 16,
                            'part_of_speech' => 'adjective',
                            'definitions' => [
                                [
                                    'id' => 27,
                                    'definition' => 'Having a commutative defining operation.',
                                    'synonyms' => [],
                                    'antonyms' => []
                                ]
                            ]
                        ]
                    ],
                    'phonetics' => [
                        [
                            'text' => '/əˈbi.li.ən/',
                            'audio' => ''
                        ]
                    ],
                    'source_urls' => []
                ]
            ], 200)
        ]);

        // Faz a requisição GET para a rota de busca por palavra específica
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/entries/en/' . $word);

        // Verifica se o status retornado é 200 (OK)
        $response->assertStatus(200);

        // Verifica se os dados retornados estão corretos
        $response->assertJsonFragment([
            'word' => 'abelian',
            'license' => 'CC BY-SA 3.0',
            'license_url' => 'https://creativecommons.org/licenses/by-sa/3.0'
        ]);

        // Verifica o fragmento da definição dentro de 'meanings'
        $response->assertJsonFragment([
            'definition' => 'Having a commutative defining operation.',
            'part_of_speech' => 'adjective'
        ]);

        // Verifica se a fonética está correta
        $response->assertJsonFragment([
            'text' => '/əˈbi.li.ən/'
        ]);
    }

    public function test_add_word_to_favorites_returns_success_response()
    {
        // Define a palavra que será adicionada aos favoritos
        $word = 'aaaa';
        Word::create(['word' => $word, 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);

        // Faz a requisição POST para a rota de adicionar aos favoritos
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/entries/en/' . $word . '/favorite');

        // Verifica se o status retornado é 204 (No Content)
        $response->assertStatus(204);

        // Você pode também querer verificar se a palavra foi realmente adicionada aos favoritos
        // Aqui você pode fazer uma chamada para buscar os favoritos e verificar se a palavra está presente
        $favoritesResponse = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/user/me/favorites');

        $favoritesResponse->assertStatus(200);
        $favoritesResponse->assertJsonFragment(['word' => $word]);
    }

    public function test_add_non_existent_word_to_favorites_returns_not_found()
    {
        // Define uma palavra que não existe
        $word = 'nonexistentword';

        // Mock da resposta esperada ao tentar adicionar uma palavra inexistente
        Http::fake([
            '/api/entries/en/' . $word . '/favorite' => Http::response(['message' => 'Not Found'], 404)
        ]);

        // Faz a requisição POST para a rota de adicionar aos favoritos
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/entries/en/' . $word . '/favorite');

        // Verifica se o status retornado é 404 (Not Found)
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Not Found']);
    }

    public function test_remove_word_from_favorites_returns_success_response()
    {
        // Define a palavra que será removida dos favoritos
        $word = 'aaaa';

        $wordData =

            Word::create(['word' => $word, 'license' => "CC BY-SA 3.0", 'license_url' => "https://creativecommons.org/licenses/by-sa/3.0"]);

        $this->user->favorites()->attach($wordData);

        // Faz a requisição POST para a rota de remover dos favoritos
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/entries/en/' . $word . '/unfavorite');

        // Verifica se o status retornado é 204 (No Content)
        $response->assertStatus(204);

        // Verifica se a palavra foi realmente removida dos favoritos
        $favoritesResponse = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/user/me/favorites');

        $favoritesResponse->assertStatus(200);
        $favoritesResponse->assertJsonMissing(['word' => $word]);
    }

    public function test_remove_non_favorite_word_returns_not_found()
    {
        // Define uma palavra que não está nos favoritos
        $word = 'nonfavoriteword';

        // Faz a requisição POST para a rota de remover dos favoritos
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/entries/en/' . $word . '/unfavorite');

        // Verifica se o status retornado é 404 (Not Found)
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Not Found']);
    }
}
