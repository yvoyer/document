<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use PHPUnit\Framework\Assert;
use Star\Component\Document\Tests\App\Fixtures\ApplicationFixtureBuilder;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use function sprintf;

final class TestClient
{
    /**
     * @var KernelBrowser
     */
    private $client;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(KernelBrowser $client, ContainerInterface $container)
    {
        $this->container = $container;
        $this->client = $client;
        $this->container->get('event_dispatcher')
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

    public function createFixtureBuilder(): ApplicationFixtureBuilder
    {
        return $this->container->get('test.service_container')->get(ApplicationFixtureBuilder::class);
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

    public function userSubmitNewDocument(string $name): self
    {
        return $this->submitForm('New document', ['document_type_name' => $name]);
    }

    public function submitForm(string $submit, array $parameters = []): self
    {
        Assert::assertCount(
            1,
            $this->client->getCrawler()->selectButton($submit),
            sprintf('The submit button "%s" could not be found', $submit)
        );
        $this->client->submitForm($submit, $parameters);

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
        return new TestClient($this->client, $this->container);
    }
}
