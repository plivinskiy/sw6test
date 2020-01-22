<?php declare(strict_types=1);

namespace Swpa\CustomOptions;


use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Swpa\CustomOptions\Setup;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Main class of the plugin CustomOptions
 *
 * TODO: remove the link: https://store.shopware.com/swagcustomproducts/custom-products.html
 * TODO: https://store.shopware.com/en/bogx103888261550/product-configurator.html?number=bogx103888261550t&c=2083
 *
 * @package Swpa\CustomOptions
 * @license See COPYING.txt for license details
 * @author  Magidev Team <support@magidev.com>
 */
class CustomOptions extends Plugin
{

    /**
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function build(ContainerBuilder $container): void
    {
        $yamlLoader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/DI/config'));
        $yamlLoader->load('services.yaml');
        $yamlLoader->load('controllers.yaml');
        $yamlLoader->load('subscribers.yaml');
        $yamlLoader->load('entities.yaml');
        parent::build($container);
    }

    public function getStorefrontScriptPath(): string
    {
        return 'Resources/dist/storefront/js';
    }

    public function getViewPaths(): array
    {
        $viewPaths = parent::getViewPaths();
        $viewPaths[] = 'Resources/views/storefront';

        return $viewPaths;
    }

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context): void
    {
        $install = new Setup\Install($this->container->get(Connection::class));
        $install->install($context);

        parent::install($context);
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context): void
    {
        /** @var Setup\Uninstall $install */
        $install = new Setup\Uninstall($this->container->get(Connection::class));
        $install->uninstall($context);

        parent::uninstall($context);
    }

    /**
     * @param ActivateContext $context
     */
    public function activate(ActivateContext $context): void
    {
        /** @var Setup\Activate $install */
        $install = new Setup\Activate($this->container->get(Connection::class));
        $install->activate($context);

        parent::activate($context);
    }

    /**
     * @param DeactivateContext $context
     */
    public function deactivate(DeactivateContext $context): void
    {
        /** @var Setup\Deactivate $install */
        $install = new Setup\Deactivate($this->container->get(Connection::class));
        $install->deactivate($context);

        parent::deactivate($context);
    }
}
