<?php

//mock global functions (rewrite it from global namespace)
namespace App {

    function file_get_contents() {

        return 'parameters:' . "\r\n" . '    fos_elastica.enable: 1';
    }

    function file_exists() {

        return true;
    }

}

namespace App\Test {

    use Symfony\Component\Config\Loader\LoaderInterface;
    use App\Kernel;
    use PHPUnit\Framework\TestCase;

    class KernelTest extends TestCase {

        public function testGetCacheDir() {
            $this->assertStringEndsWith('/var/cache/test', $this->getKernel()->getCacheDir());
        }

        public function testGetLogDir() {
            $this->assertStringEndsWith('/var/log', $this->getKernel()->getLogDir());
        }

        public function testRegisterBundles() {
            $bundles = $this->getKernel()->registerBundles();
            foreach ($bundles as $bundle) {
                $this->assertInstanceOf(\Symfony\Component\HttpKernel\Bundle\BundleInterface::class, $bundle);
            }
        }

        public function testRegisterContainerConfiguration() {
            $kernel = $this->getKernel();

            $loaderResolver = new \Symfony\Component\Config\Loader\LoaderResolver();
            $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerBuilder::class);
            $container->method('getExtensions')->willReturn(['fos_elastica' => null]);
            $locator = $this->createMock(\Symfony\Component\Config\FileLocatorInterface::class);
            $closureFileLoader = new \Symfony\Component\DependencyInjection\Loader\ClosureLoader($container);
            $globFileLoader = new \Symfony\Component\DependencyInjection\Loader\GlobFileLoader($container, $locator);
            $loaderResolver->addLoader($globFileLoader);
            $loaderResolver->addLoader($closureFileLoader);

            $loader = new \Symfony\Component\Config\Loader\DelegatingLoader($loaderResolver);

            $this->assertNull($kernel->registerContainerConfiguration($loader));
        }

        public function testLoadRoutes() {
            $loader = $this->createMock(LoaderInterface::class);
            $loaderResolver = $this->createMock(\Symfony\Component\Config\Loader\LoaderResolverInterface::class);
            $loaderResolver->expects($this->any())->method('resolve')->willReturn($loader);
            $loader->expects($this->any())->method('getResolver')->willReturn($loaderResolver);
            $routeCollection = $this->getKernel()->loadRoutes($loader);
            $this->assertInstanceOf(\Symfony\Component\Routing\RouteCollection::class, $routeCollection);
        }

        private function getKernel(): Kernel {
            $kernel = new \App\Kernel('test', true);

            return $kernel;
        }

    }
}