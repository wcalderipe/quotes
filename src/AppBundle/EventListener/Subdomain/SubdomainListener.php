<?php

namespace AppBundle\EventListener\Subdomain;

use AppBundle\Entity\Tenant;
use AppBundle\Exception\Tenant\TenantNotFoundException;
use AppBundle\Repository\TenantRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubdomainListener
{
	protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request   = $event->getRequest();
        $parser    = new RequestParser($request);
        $subdomain = $parser->getSubdomain();

        if (null === $subdomain) {
            $this->container->tenant = null;
            return;
        }

        $tenant = $this->findTenantOrThrowException($subdomain);
        $this->attachTenantToContainer($tenant);
    }

    private function findTenantOrThrowException($subdomain)
    {
        $container = $this->container;

        /* @var EntityManager $em */
        $em = $container->get('doctrine.orm.entity_manager');

        /* @var TenantRepository $repository */
        $repository = $em->getRepository('AppBundle:Tenant');

        $tenant = $repository->findOneBySubdomain($subdomain);
        if (null === $tenant) {
            throw new TenantNotFoundException(
                "Tenant with subdomain {$subdomain} not found"
            );
        }

        return $tenant;
    }

    private function attachTenantToContainer(Tenant $tenant)
    {
        $this->container->tenant = $tenant;
    }
}
