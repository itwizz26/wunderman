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
use App\Entity\Comments;

class SaveCommentsCommand extends Command
{
    private $baseUri = "https://hacker-news.firebaseio.com/v0";
    private $itemEndpoint = "/item";

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:save-comments';

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Saves all news comments from HN.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to save all the news comments from HN...');
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
            'Saves all comments...',
            '===================',
            '',
        ]);

        $output->writeln('Now saving all news comments from HN...!');
        // $output->write('You are about to ');

        // do api call
        $result = $this->saveAllComments ($this->em, $this->registry);

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
    public function saveAllComments ($entityManager, $managerRegistry)
    {
        $repo = new NewsRepository ($managerRegistry);
        $allNews = $repo->getNewsAssoc();
        $allComments = [];

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
                $stories = json_decode ($story->getContent(), true);
                
                if (array_key_exists ("kids", $stories))
                {
                    $allComments[$stories['id']] = $stories['kids'];
                    continue;
                }
            }
        }
        
        foreach ($allComments as $key => $kids)
        {
            $comment = new Comments();
            $comment->setId($key);
            $comment->setKids(json_encode ($kids), true);
            $entityManager->merge($comment);
            $entityManager->flush();
        }

        // success
        return 0;
    }
}
