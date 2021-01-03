<?php declare(strict_types=1);

namespace App\Controller;

use App\AppRouteStore;
use App\_Installation\InstallationStep;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Setup extends AppController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route(path="/setup", name="setup")
     *
     * @param InstallationStep $install
     * @return Response
     */
    public function __invoke(InstallationStep $install): Response {
        if ($install->isInstalled()) {
            return $this->redirect($this->generateUrl(AppRouteStore::DASHBOARD));
        }

        // todo when form submit, setup
        $install->setup();
        $this->addFlashSuccess($this->translator->trans('flash.success.installation_completed', [], 'flash'));

        return $this->redirect($this->generateUrl(AppRouteStore::DASHBOARD));
    }
}
