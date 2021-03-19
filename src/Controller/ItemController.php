<?php
    
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentsRepository;
    
class ItemController extends AbstractController
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
     * @Route("/item/{itemId}", name="item")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getItemComment (Request $request, $itemId, EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    {
        // get comments in db
        $repo = new CommentsRepository ($managerRegistry);
        $comment = $repo->findOneBySomeField('id', $itemId);

        var_dump($comment);
        die;
        
        $detail = [];
        $response = new Response();
        foreach ($comment as $item)
        {
            // first fetch parent details
            $detail[] = $this->client->request(
                'GET',
                $this->baseUri . $this->itemEndpoint . '/' . $item['id'] . '.json'
            );

            // Check if parent found
            $statusCode = $details->getStatusCode();
            
            if ($statusCode === 200) {
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response = json_decode ($detail->getContent(), true);
            }
        }

        return $response;
    }
}