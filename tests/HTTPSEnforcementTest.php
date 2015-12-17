<?hh // strict

namespace HHVM\UserDocumentation\Tests;

/**
 * @group remote
 * @small
 */
final class HTTPSEnforcementTest extends \PHPUnit_Framework_TestCase {
  public function testNoEnforcementByDefault(): void {
    $response = \HH\Asio\join(
      PageLoader::getPage('http://example.com/hack/reference/')
    );
    $this->assertSame(
      200,
      $response->getStatusCode(),
    );
  }

  public function httpsDomains(): array<array<string>> {
    return [
      ['docs.hhvm.com'],
      ['staging.docs.hhvm.com'],
    ];
  }

  /**
   * @dataProvider httpsDomains
   */
  public function testEnforcedOnDomain(string $domain): void {
    $response = \HH\Asio\join(
      PageLoader::getPage('http://'.$domain.'/hack/reference/')
    );
    $this->assertSame(301, $response->getStatusCode());

    $location = $response->getHeaderLine('Location');
    $this->assertSame(
      'https://'.$domain.'/hack/reference/',
      $location,
    );

    $response = \HH\Asio\join(PageLoader::getPage($location));
    $this->assertSame(200, $response->getStatusCode());
  }

  public function testNotEnforcedOnRobotsTxt(): void {
    $response = \HH\Asio\join(
      PageLoader::getPage('http://docs.hhvm.com/robots.txt')
    );
    $this->assertSame(200, $response->getStatusCode());
  }
}