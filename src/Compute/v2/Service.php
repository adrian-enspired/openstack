<?php declare(strict_types=1);

namespace OpenStack\Compute\v2;

use OpenStack\Common\Service\AbstractService;
use OpenStack\Compute\v2\Models\Flavor;
use OpenStack\Compute\v2\Models\HypervisorStatistic;
use OpenStack\Compute\v2\Models\Image;
use OpenStack\Compute\v2\Models\Keypair;
use OpenStack\Compute\v2\Models\Limit;
use OpenStack\Compute\v2\Models\Server;
use OpenStack\Compute\v2\Models\Hypervisor;

/**
 * Compute v2 service for OpenStack.
 *
 * @property \OpenStack\Compute\v2\Api $api
 */
class Service extends AbstractService
{
    /**
     * Create a new server resource. This operation will provision a new virtual machine on a host chosen by your
     * service API.
     *
     * @param array $options {@see \OpenStack\Compute\v2\Api::postServer}
     *
     * @return \OpenStack\Compute\v2\Models\Server
     */
    public function createServer(array $options): Server
    {
        return $this->model(Server::class)->create($options);
    }

    /**
     * List servers.
     *
     * @param bool     $detailed Determines whether detailed information will be returned. If FALSE is specified, only
     *                           the ID, name and links attributes are returned, saving bandwidth.
     * @param array    $options  {@see \OpenStack\Compute\v2\Api::getServers}
     * @param callable $mapFn    A callable function that will be invoked on every iteration of the list.
     *
     * @return \Generator
     */
    public function listServers(bool $detailed = false, array $options = [], callable $mapFn = null): \Generator
    {
        $def = ($detailed === true) ? $this->api->getServersDetail() : $this->api->getServers();
        return $this->model(Server::class)->enumerate($def, $options, $mapFn);
    }

    /**
     * Retrieve a server object without calling the remote API. Any values provided in the array will populate the
     * empty object, allowing you greater control without the expense of network transactions. To call the remote API
     * and have the response populate the object, call {@see Server::retrieve}. For example:
     *
     * <code>$server = $service->getServer(['id' => '{serverId}']);</code>
     *
     * @param array $options An array of attributes that will be set on the {@see Server} object. The array keys need to
     *                       correspond to the class public properties.
     *
     * @return \OpenStack\Compute\v2\Models\Server
     */
    public function getServer(array $options = []): Server
    {
        $server = $this->model(Server::class);
        $server->populateFromArray($options);
        return $server;
    }

    /**
     * List flavors.
     *
     * @param array    $options {@see \OpenStack\Compute\v2\Api::getFlavors}
     * @param callable $mapFn   A callable function that will be invoked on every iteration of the list.
     *
     * @return \Generator
     */
    public function listFlavors(array $options = [], callable $mapFn = null): \Generator
    {
        return $this->model(Flavor::class)->enumerate($this->api->getFlavors(), $options, $mapFn);
    }

    /**
     * Retrieve a flavor object without calling the remote API. Any values provided in the array will populate the
     * empty object, allowing you greater control without the expense of network transactions. To call the remote API
     * and have the response populate the object, call {@see Flavor::retrieve}.
     *
     * @param array $options An array of attributes that will be set on the {@see Flavor} object. The array keys need to
     *                       correspond to the class public properties.
     *
     * @return \OpenStack\Compute\v2\Models\Flavor
     */
    public function getFlavor(array $options = []): Flavor
    {
        $flavor = $this->model(Flavor::class);
        $flavor->populateFromArray($options);
        return $flavor;
    }

    /**
     * Create a new flavor resource.
     *
     * @param array $options {@see \OpenStack\Compute\v2\Api::postFlavors}
     *
     * @return Flavor
     */
    public function createFlavor(array $options = []): Flavor
    {
        return $this->model(Flavor::class)->create($options);
    }

    /**
     * List images.
     *
     * @param array    $options {@see \OpenStack\Compute\v2\Api::getImages}
     * @param callable $mapFn   A callable function that will be invoked on every iteration of the list.
     *
     * @return \Generator
     */
    public function listImages(array $options = [], callable $mapFn = null): \Generator
    {
        return $this->model(Image::class)->enumerate($this->api->getImages(), $options, $mapFn);
    }

    /**
     * Retrieve an image object without calling the remote API. Any values provided in the array will populate the
     * empty object, allowing you greater control without the expense of network transactions. To call the remote API
     * and have the response populate the object, call {@see Image::retrieve}.
     *
     * @param array $options An array of attributes that will be set on the {@see Image} object. The array keys need to
     *                       correspond to the class public properties.
     *
     * @return \OpenStack\Compute\v2\Models\Image
     */
    public function getImage(array $options = []): Image
    {
        $image = $this->model(Image::class);
        $image->populateFromArray($options);
        return $image;
    }

    /**
     * List key pairs.
     *
     * @param array    $options {@see \OpenStack\Compute\v2\Api::getKeyPairs}
     * @param callable $mapFn   A callable function that will be invoked on every iteration of the list.
     *
     * @return \Generator
     */
    public function listKeypairs(array $options = [], callable $mapFn = null): \Generator
    {
        return $this->model(Keypair::class)->enumerate($this->api->getKeypairs(), $options, $mapFn);
    }

    /**
     * Create or import keypair
     *
     * @param array $options
     *
     * @return Keypair
     */
    public function createKeypair(array $options): Keypair
    {
        return $this->model(Keypair::class)->create($options);
    }

    /**
     * Get keypair
     *
     * @param array $options
     *
     * @return Keypair
     */
    public function getKeypair(array $options = []): Keypair
    {
        $keypair = $this->model(Keypair::class);
        $keypair->populateFromArray($options);
        return $keypair;
    }

    /**
     * Shows rate and absolute limits for the tenant
     *
     * @return Limit
     */
    public function getLimits(): Limit
    {
        $limits = $this->model(Limit::class);
        $limits->populateFromResponse($this->execute($this->api->getLimits(), []));
        return $limits;
    }

    /**
     * Shows summary statistics for all hypervisors over all compute nodes.
     *
     * @return HypervisorStatistic
     */
    public function getHypervisorStatistics(): HypervisorStatistic
    {
        $statistics = $this->model(HypervisorStatistic::class);
        $statistics->populateFromResponse($this->execute($this->api->getHypervisorStatistics(), []));
        return $statistics;
    }

    /**
     * List hypervisors.
     *
     * @param bool     $detailed Determines whether detailed information will be returned. If FALSE is specified, only
     *                           the ID, name and links attributes are returned, saving bandwidth.
     * @param array    $options  {@see \OpenStack\Compute\v2\Api::getHypervisors}
     * @param callable $mapFn    A callable function that will be invoked on every iteration of the list.
     *
     * @return \Generator
     */
    public function listHypervisors(bool $detailed = false, array $options = [], callable $mapFn = null): \Generator
    {
        $def = ($detailed === true) ? $this->api->getHypervisorsDetail() : $this->api->getHypervisors();
        return $this->model(Hypervisor::class)->enumerate($def, $options, $mapFn);
    }

}
