<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;
use function sprintf;
use function var_dump;

final class FunctionalAssertion
{
    /**
     * @var KernelBrowser
     */
    private $client;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(
        KernelBrowser $client,
        Response $response,
        Crawler $crawler
    ) {
        $this->client = $client;
        $this->response = $response;
        $this->crawler = $crawler;
    }

    public function assertRedirectingTo(string $location = null, int $code = 302): self
    {
        Assert::assertTrue(
            $this->response->isRedirect($location),
            sprintf(
                'Response "%s" was not redirected to location "%s".',
                $this->getResponseInfo(),
                $location
            )
        );

        return $this->assertStatusCode($code);
    }

    public function assertStatusCode(int $code): self
    {
        $actual = $this->response->getStatusCode();
        Assert::assertSame(
            $code,
            $actual,
            sprintf('Status code "%s" is not as expected "%s".', $actual, $code)
        );

        return $this->newSelf($this->crawler);
    }

    public function assertCurrentPageIs(string $location, int $code): self
    {
        $current = $this->crawler->getUri();
        Assert::assertSame(
            $location,
            $current,
            sprintf(
                'Current location "%s" is not as expected "%s".', $current, $location)
        );

        return $this->assertStatusCode($code);
    }

    public function assertFlashMessage(string $expected, string $type): self
    {
        $flashes = $this->crawler->filter('#flashes .alert-' . $type);
        Assert::assertGreaterThan(0, count($flashes));
        Assert::assertSame($expected, $flashes->text());

        return $this->newSelf($this->crawler);
    }

    public function followRedirect(): self
    {
        $crawler = $this->client->followRedirect();

        return new self(
            $this->client,
            $this->client->getResponse(),
            $crawler
        );
    }

    public function dumpResponse(): self
    {
        var_dump($this->getResponseInfo());

        return $this;
    }

    private function getResponseInfo(): string
    {
        $titleNode = $this->crawler->filter('title');
        $title = 'UNKNOWN';
        if ($titleNode->count() > 0) {
            $title = $titleNode->text();
        }

        $body = $this->response->getContent();
        $bodyNode = $this->crawler->filter('body');
        if (count($bodyNode) > 0) {
            $body = $bodyNode->text();
        }

        return json_encode(
            [
                'body' => $body,
                'title' => $title,
            ],
            JSON_PRETTY_PRINT
        );
    }

    private function newSelf(Crawler $crawler): self
    {
        return new self(
            $this->client,
            $this->client->getResponse(),
            $crawler
        );
    }
}
