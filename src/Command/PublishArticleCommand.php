<?php

namespace App\Command;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:publish-article',
    description: 'Add a short description for your command',
)]

class PublishArticleCommand extends Command
{
    /* On a ajouter un attribut $articleRepository  */
    private $articleRepository;

    /* il nous faut le repository et on ne peut pas l'injecter directement dans execute et on va devoir passer par un constucteur */
    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $manager, string $name = null) //On va lui dire qu'on a besoin d'un parametre supplementaire qui est Article repository
    {
        $this->articleRepository = $articleRepository;
        $this->manager = $manager;
        parent::__construct($name);
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Publie les article "A publier"')
            /* ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description') On ne va pas utilisé ça ici */
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        // dump($this->articleRepository);die;
        //On recupere nos articles à publier
        $articles = $this->articleRepository->findBy([
            'state' => 'a publier'
        ]);
        //On boucle dessus à chaque fois on les passe à publier
        foreach ($articles  as $article){
            $article->setState('publier');
        }

        $this->manager->flush();

        $io->success(count($articles).' article(s) publié(s).');

        return Command::SUCCESS;
    }
}
