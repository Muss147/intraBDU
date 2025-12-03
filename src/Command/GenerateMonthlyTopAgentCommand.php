<?php

namespace App\Command;

use App\Entity\Agents;
use App\Entity\VoteNote;
use App\Entity\Parametres;
use App\Entity\ClassementMensuel;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-monthly-top-agent',
    description: 'Génère et sauvegarde l’agent du mois automatiquement.',
)]
class GenerateMonthlyTopAgentCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private VotesRepository $voteRepo
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startOfMonth = (new \DateTime('first day of this month'))->setTime(0, 0);
        $endOfMonth = (new \DateTime('last day of this month'))->setTime(23, 59, 59);

        // On récupère le nombre de critères (en base ou via repository)
        $nbCriteres = $this->em->getRepository(Parametres::class)->count([
            'type' => 'criteres', // ou tout autre filtre pertinent
            'deletedAt' => null
        ]);

        if ($nbCriteres === 0) {
            $output->writeln("⚠️ Aucun critère défini.");
            return Command::FAILURE;
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('a.id AS agent_id, a.nom, a.prenoms, a.civilite, COUNT(v.id)/:nbCriteres as nbVotes, AVG(vn.note) as moyenne')
            ->from(VoteNote::class, 'vn')
            ->join('vn.vote', 'v')
            ->join('v.agent', 'a')
            ->where('v.votedAt BETWEEN :start AND :end')
            ->groupBy('a.id, a.nom, a.prenoms, a.civilite')
            ->orderBy('nbVotes', 'DESC')
            ->addOrderBy('moyenne', 'DESC')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->setParameter('nbCriteres', $nbCriteres)
            ->setMaxResults(1);

        $top = $qb->getQuery()->getOneOrNullResult();

        if ($top) {

            // Récupère l'entité agent
            $agent = $this->em->getRepository(Agents::class)->find($top['agent_id']);

            $classement = new ClassementMensuel();
            $classement->setMois(new \DateTime($startOfMonth->format('Y-m-01')));
            $classement->setAgent($agent);
            $classement->setMoyenne($top['moyenne']);
            $classement->setNombreVotes($top['nbVotes']);

            $this->em->persist($classement);
            $this->em->flush();


            $output->writeln("✅ L’agent du mois est : " . $agent->getCivilite() . ' ' . $agent->getPrenoms() . ' ' . $agent->getNom());
            $output->writeln("📊 Moyenne : " . round($top['moyenne'], 2) . ' | Nombre de votes : ' . $top['nbVotes'] / 5);
            $output->writeln("📅 Mois concerné : " . $startOfMonth->format('F Y'));
        } else {
            $output->writeln("⚠️ Aucun vote ce mois-ci.");
        }

        return Command::SUCCESS;
    }
}
