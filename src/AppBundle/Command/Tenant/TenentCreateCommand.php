<?php

namespace AppBundle\Command\Tenant;


use AppBundle\Entity\Tenant;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TenentCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tenant:create')
            ->setDescription('Create a new tenant')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Tenant name'
            )
            ->addArgument(
                'subdomain',
                InputArgument::REQUIRED,
                'Tenant subdomain'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tenant = new Tenant();
        $tenant
            ->setName($input->getArgument('name'))
            ->setSubdomain($input->getArgument('name'))
        ;

        /* @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($tenant);
        $em->flush();
    }
}