<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NewsRepository;
use App\Entity\News;

class SaveNewsCommand extends Command
{
    private $baseUri = "https://hacker-news.firebaseio.com/v0";
    private $storiesEndpoint = "/topstories.json";

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:save-news';

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Saves top news entries from HN.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to save the top news articles from HN...');

        $this->addArgument('category', $this->requireCategory ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'Item category - top | new | best');
    }

    public function __construct(bool $requireCategory = false, HttpClientInterface $client,
    EntityManagerInterface $em, ManagerRegistry $registry)
    {
        $this->requireCategory = $requireCategory;
        $this->client = $client;
        $this->em = $em;
        $this->registry = $registry;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Saves news items...',
            '===================',
            '',
        ]);

        $output->writeln('Now pulling news items from HN...!');
        // $output->write('You are about to ');

        // do api call
        $result = $this->getTopStories ($this->em, $this->registry);

        // the value returned by someMethod() can be an iterator (https://secure.php.net/iterator)
        // that generates and returns the messages with the 'yield' PHP keyword
        // $output->writeln($this->someMethod());

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        if ($result === 0) {
            // return this if there was no problem running the command
            // (it's equivalent to returning int(0))
            return Command::SUCCESS;
        }
        else {
            // or return this if some error happened during the execution
            // (it's equivalent to returning int(1))
            return Command::FAILURE;
        }
    }

    /**
     * Gets news items from hacker news
     * @return Int
     */
    public function getTopStories($entityManager, $managerRegistry)
    {
        // Fetch top stories
        $topStories = $this->client->request(
            'GET',
            $this->baseUri . $this->storiesEndpoint
        );

        // Check if top stories found
        $statusCode = $topStories->getStatusCode();
        
        if ($statusCode === 200) {
            // Fetch story details
            $storyIds = json_decode ($topStories->getContent(), true);
            
            foreach ($storyIds as $id)
            {
                $repo = new NewsRepository ($managerRegistry);
                $newsEntry = $repo->findOneBySomeField ('id', $id);
                
                // var_dump ($newsEntry);
                // die;
                
                if (!is_object ($newsEntry))
                {
                    $news = new News();
                    $news->setId($id);
                    $entityManager->merge($news);
                    $entityManager->flush();
                }

            }

            // returns 0 on success
            $response = 0;
        }
        else {
            // No stories found
            // returns 1 on failure
            $response = 1;
        }

        return $response;
    }
}
