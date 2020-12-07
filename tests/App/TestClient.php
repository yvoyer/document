<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;

final class TestClient
{
    /**
     * @var KernelBrowser
     */
    private $client;

    public function __construct(KernelBrowser $client)
    {
        $this->client = $client;
        $this->client->getContainer()->get('event_dispatcher')
            ->addListener(
                KernelEvents::EXCEPTION,
                function ($event) {
                    /**
                     * @see \Symfony\Component\HttpKernel\EventListener\ErrorListener
                     */
                    $event->stopPropagation(); // to avoid ExceptionListener to be triggered
                },
                -127
            );
    }

    public function sendRequest(Request $request): TestClient
    {
        $this->client->request(
            $request->getMethod(),
            $request->getUri(),
            $request->query->all(),
            $request->files->all(),
            $request->server->all(),
            $request->getContent(),
            false
        );

        return $this->newClient();
    }

    public function then(): FunctionalAssertion
    {
        return new FunctionalAssertion(
            $this->client,
            $this->client->getResponse(),
            $this->client->getCrawler()
        );
    }

    public function dumpResponse(): self
    {
        $this->then()->dumpResponse();

        return $this;
    }

    protected function newClient(): TestClient
    {
        return new TestClient($this->client);
    }
}
