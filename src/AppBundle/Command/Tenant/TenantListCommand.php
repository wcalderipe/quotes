<?php

namespace AppBundle\Command\Tenant;

use AppBundle\Repository\TenantRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TenantListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tenant:list')
            ->setDescription('List all tenants')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /* @var TenantRepository $repository */
        $repository = $em->getRepository('AppBundle:Tenant');

        $tenants = $repository->findAll();
        if (empty($tenants)) {
            return $output->writeln('There\'s no tenants registred');
        }

        $tmp = array();
        foreach ($tenants as $tenant) {
            $tmp[] = (array) $tenant;
        }

        /* @var Table $table */
        $table = $this->getHelper('table');
        $table
            ->setHeaders(array('id', 'name', 'subdomain'))
            ->setRows($tmp)
            ->render($output)
        ;
    }
}