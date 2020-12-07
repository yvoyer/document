<?php declare(strict_types=1);

namespace App\Controller;

use App\AppRouteStore;
use App\Installation\InstallationChecker;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Dashboard extends AppController
{
    /**
     * @Route(path="/", name="dashboard")
     *
     * @param InstallationChecker $checker
     * @return Response
     */
    public function __invoke(InstallationChecker $checker): Response {
        if (! $checker->isInstalled()) {
            return $this->redirect($this->generateUrl(AppRouteStore::SETUP));
        }

        return $this->render('Dashboard\index.html.twig');
    }
}
