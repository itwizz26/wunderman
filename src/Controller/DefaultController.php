<?php
    
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NewsRepository;
    
class DefaultController extends AbstractController
{
    private $baseUri = "https://hacker-news.firebaseio.com/v0";
    private $itemEndpoint = "/item";
    private $items = [];

    public function __construct (HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/{reactRouting}", name="home", defaults={"reactRouting": null})
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/api/v1/all", name="all")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllStories (EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    {
        $repo = new NewsRepository ($managerRegistry);
        $allNews = $repo->getNewsAssoc();
        $stories = [];

        foreach ($allNews as $item)
        {
            // Fetch new stories
            $story = $this->client->request(
                'GET',
                $this->baseUri . $this->itemEndpoint . '/' . $item['id'] . '.json'
            );

            // Check if new stories found
            $statusCode = $story->getStatusCode();
            
            if ($statusCode === 200) {
                $stories[] = $story->getContent();
            }
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->setContent (json_encode ($stories));
        return $response;
    }
}