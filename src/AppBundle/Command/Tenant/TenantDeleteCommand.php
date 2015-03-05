<?php

namespace AppBundle\Command\Tenant;

use AppBundle\Entity\Tenant;
use AppBundle\Repository\TenantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TenantDeleteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tenant:delete')
            ->setDescription('Delete a tenant by ID or subdomain')
            ->addArgument(
                'key',
                InputArgument::REQUIRED,
                'ID or subdomain to delete'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');
        $isById = is_numeric($key);

        /* @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /* @var TenantRepository $repository */
        $repository = $em->getRepository('AppBundle:Tenant');

        $tenant = $isById ? $repository->findOneById($key) :
            $repository->findOneBySubdomain($key);

        if (null === $tenant) {
            throw new EntityNotFoundException(
                "Tenant not found"
            );
        }

        $em->remove($tenant);
        $em->flush();
    }
}