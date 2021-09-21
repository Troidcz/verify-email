<?php

declare(strict_types=1);

namespace {
    require __DIR__ . '/../bootstrap.php';

    class MyPresenter implements Nette\Application\IPresenter
    {
        public function run(Nette\Application\Request $request): Nette\Application\Response
        {
            return new Nette\Application\Responses\VoidResponse();
        }
    }
}

namespace Troidcz\Tests\VerifyEmail {

    use Nette\Application\LinkGenerator;
    use Nette\Application\PresenterFactory;
    use Nette\Application\Routers\SimpleRouter;
    use Nette\Http\UrlScript;
    use Tester\Assert;
    use Troidcz\VerifyEmail\Exception\ExpiredSignatureException;
    use Troidcz\VerifyEmail\Generator\VerifyEmailTokenGeneratorInterface;
    use Troidcz\VerifyEmail\Http\UriSignerInterface;
    use Troidcz\VerifyEmail\Util\VerifyEmailQueryUtility;
    use Troidcz\VerifyEmail\VerifyEmailHelper;

    $container = require __DIR__ . '/../bootstrap.php';

    class SimpleTest extends TestAbstract
    {
        public function test01(): void
        {
            $container = Helpers::createContainerFromConfigurator($this->container->getParameters()['tempDir'], [
                'verifyEmail' => [
                    'lifetime' => 3600,
                    'secretKey' => 'asdasd',
                ],
            ]);
            $pf = new PresenterFactory();
            $verifyEmailHelper = new VerifyEmailHelper(
                new LinkGenerator(new SimpleRouter(), new UrlScript('http://example.com'), $pf),
                $container->getByType(UriSignerInterface::class),
                $container->getByType(VerifyEmailQueryUtility::class),
                $container->getByType(VerifyEmailTokenGeneratorInterface::class),
                1
            );
            $address = $verifyEmailHelper->generateSignature(
                'My:',
                "1",
                'email@email.cz',
                []
            );
            $verifyEmailHelper->validateEmailConfirmation($address->getSignedUrl(), '1', 'email@email.cz');

            Assert::exception(function () use ($verifyEmailHelper, $address): void {
                sleep(1);
                $verifyEmailHelper->validateEmailConfirmation($address->getSignedUrl(), '1', 'email@email.cz');
            }, ExpiredSignatureException::class);
        }
    }

    (new SimpleTest($container))->run();
}
