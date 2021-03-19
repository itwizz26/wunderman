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
        $details = $repo->findOneBy(['id' => $itemId]);
        
        // var_dump($details);
        // die;

        foreach ($allComms as $item)
        {
            // first fetch parent details
            $parent = $this->client->request(
                'GET',
                $this->baseUri . $this->itemEndpoint . '/' . $item['id'] . '.json'
            );

            // Check if parent found
            $statusCode = $parent->getStatusCode();
            
            if ($statusCode === 200) {
                $parentDetails = json_decode ($parent->getContent(), true);
                $comments[$item['id']] = $parentDetails['title'];
                $kids = explode (",", $item['kids']);

                // loop comment keys
                foreach ($kids as $key => $value)
                {
                    // get comment details
                    $kid = $this->client->request(
                        'GET',
                        $this->baseUri . $this->itemEndpoint . '/' . preg_replace ('/[^0-9]/', '', $value) . '.json'
                    );

                    $statusCode = $kid->getStatusCode();
                    if ($statusCode === 200) {
                        $kidItem = $kid->getContent();

                        // skip blanks
                        if (!empty ($kidItem))
                        {
                            $comments['kids'][] = $kidItem;
                            continue;
                        }
                    }
                }
            }
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $response->setContent (json_encode ($comments));
        return $response;
    }
}