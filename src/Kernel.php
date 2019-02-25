<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return $this->getProjectDir().'/var/log';
    }

    public function registerBundles() {
        $contents = require $this->getProjectDir() . '/config/bundles.php';

        //register dynamic bundles
        if($this->isFOSElasticaBundleEnabled()){
            $contents[\FOS\ElasticaBundle\FOSElasticaBundle::class] = ['all' => true];
        }
        
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {

                yield new $class();
            }
        }
    }

    /**
     * Make FOSElastica bundle allowance dependent on it's own configuration
     * You can disable/enable bundle by changing fos_elastica.enable property in
     *     \config\packages\fos_elastica.yaml
     * 
     * @return bool
     */
    private function isFOSElasticaBundleEnabled(): bool {
        $fosElasticaCfg = $this->getProjectDir() . '/config/optional/fos_elastica.yaml';
        $configValues = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($fosElasticaCfg));
        
        //@todo provide environment context
        
//        $fosElasticaEnvCfg = $this->getProjectDir() . '/config/optional/' . $this->environment . '/fos_elastica.yaml';
//        if (file_exists($fosElasticaEnvCfg)) {
//            $configEnvValues = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($fosElasticaEnvCfg));
//            $configValues = array_merge($configValues, $configEnvValues);
//        }
        
        return (bool) $configValues['parameters']['fos_elastica.enable'];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        // Feel free to remove the "container.autowiring.strict_mode" parameter
        // if you are using symfony/dependency-injection 4.0+ as it's the default behavior
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        

        foreach ($container->getExtensions() as $extName => $extClass) {
            if (file_exists($confDir . '/optional/' . $extName . '.yaml')) {
                $loader->load($confDir . '/{optional}/' . $extName . '.yaml', 'glob');
            }
        }

        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }
}
