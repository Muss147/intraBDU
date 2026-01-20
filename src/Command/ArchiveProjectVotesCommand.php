<?php

namespace App\Command;

use App\Entity\ProjectResult;
use App\Repository\ProjectImpactRepository;
use App\Repository\ProjectVoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:archive-project-votes',
    description: 'Archive les résultats des projets à impact du mois'
)]
class ArchiveProjectVotesCommand extends Command
{
    public function __construct(
        private ProjectImpactRepository $projectRepo,
        private ProjectVoteRepository $voteRepo,
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $month = (new \DateTime())->format('Y-m');

        $projects = $this->projectRepo->findToArchive($month);

        foreach ($projects as $project) {
            $votes = $this->voteRepo->countByProject($project->getId());

            $result = new ProjectResult();
            $result->setProjectTitle($project->getTitle());
            $result->setOwnerName($project->getOwner()->getFullName());
            $result->setVotesCount($votes);
            $result->setMonth($month);
            $result->setArchivedAt(new \DateTimeImmutable());

            $project->setIsArchived(true);

            $this->em->persist($result);
        }

        $this->voteRepo->deleteByMonth($month);
        $this->em->flush();

        $output->writeln('<info>✔ Archivage terminé pour '.$month.'</info>');

        return Command::SUCCESS;
    }
}