<?php

namespace App\Command;

use App\Repository\ArticleRepository;
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
    /* On a ajouter ça aussi */
    private $articleRepository;

    /* ça on a ajouter pour lu mettre la repository parce q'on ne pourras pas l'injecter directement */
    public function __construct(ArticleRepository $articleRepository, string $name = null)
    {
        $this->articleRepository = $articleRepository;
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
        
        dump($this->articleRepository);die;

        $io->success('Articles publiés.');

        return Command::SUCCESS;
    }
}
