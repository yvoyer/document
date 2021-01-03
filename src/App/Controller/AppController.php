<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AppController extends AbstractController
{
    protected function addFlashSuccess(string $message): void
    {
        $this->addFlash('success', $message);
    }

    protected function addFlashError(string $message): void
    {
        $this->addFlash('danger', $message);
    }

    protected function addFlashNotice(string $message): void
    {
        $this->addFlash('info', $message);
    }

    protected function addFlashWarning(string $message): void
    {
        $this->addFlash('warning', $message);
    }
}
