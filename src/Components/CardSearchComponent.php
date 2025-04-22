<?php
namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\Computed;
use Symfony\Contracts\HttpClient\HttpClientInterface;


#[AsLiveComponent('card_search', template:'components/card_search.html.twig')]
class CardSearchComponent
{
    #[LiveProp(writable: true)]
    public string $query = '';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke(): void
    {
    }

    #[Computed]
    public function results(): array
    {
        if (strlen($this->query) < 2) {
            return [];
        }
        error_log("Recherche : " . $this->query);
        $response = $this->client->request('GET', 'https://api.tcgdex.net/v2/fr/cards', [
            'query' => ['name' => $this->query]
        ]);  

        return array_slice($response->toArray(), 0, 10);
    }
}
