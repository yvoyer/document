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
        $this->addFlash('error', $message);
    }

    protected function addFlashNotice(string $message): void
    {
        $this->addFlash('notice', $message);
    }

    protected function addFlashWarning(string $message): void
    {
        $this->addFlash('warning', $message);
    }
}
