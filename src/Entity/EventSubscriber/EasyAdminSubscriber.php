<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use ReflectionClass; // Utilisation correcte de ReflectionClass
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private KernelInterface $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setIllustration'],
            BeforeEntityUpdatedEvent::class => ['updateIllustration'],
        ];
    }

    public function uploadIllustration($event,$entityName)
    {
        $entity = $event->getEntityInstance();

        // Ensure the file input name matches your form field name
        $tmp_name = $_FILES['$entityName']['tmp_name']['illustration'];
        $filename = uniqid();
        $extension = pathinfo($_FILES['$entityName']['name']['illustration'], PATHINFO_EXTENSION);

        $project_dir = $this->appKernel->getProjectDir();
        $upload_dir = $project_dir . '/public/uploads/';
        move_uploaded_file($tmp_name, $upload_dir . $filename . '.' . $extension);

        $entity->setIllustration($filename . '.' . $extension);
    }

    public function updateIllustration(BeforeEntityUpdatedEvent $event)
    {
        if (!($event->getEntityInstance() instanceof Product)) {
            return;
        }

        $reflexion = new ReflectionClass($event->getEntityInstance());
        $entityName = $reflexion->getShortName();

        if (!empty($_FILES['$entyName']['tmp_name']['illustration'])) {
            $this->uploadIllustration($event,$entityName);
        }
    }

    public function setIllustration(BeforeEntityPersistedEvent $event)
    {

        $reflexion = new ReflectionClass($event->getEntityInstance());
        $entityName = $reflexion->getShortName();
     
        if (!($event->getEntityInstance() instanceof Product) && $event->getEntityInstance()) {
            return;
        }

        $this->uploadIllustration($event,$entityName);
    }
}
