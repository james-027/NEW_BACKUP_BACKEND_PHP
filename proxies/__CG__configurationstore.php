<?php

namespace DoctrineProxies\__CG__\configuration;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class store extends \configuration\store implements \Doctrine\ORM\Proxy\InternalProxy
{
    use \Symfony\Component\VarExporter\LazyGhostTrait {
        initializeLazyObject as private;
        setLazyObjectAsInitialized as public __setInitialized;
        isLazyObjectInitialized as private;
        createLazyGhost as private;
        resetLazyObject as private;
    }

    public function __load(): void
    {
        $this->initializeLazyObject();
    }
    

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'address' => [parent::class, 'address', null],
        "\0".parent::class."\0".'created_by' => [parent::class, 'created_by', null],
        "\0".parent::class."\0".'date_created' => [parent::class, 'date_created', null],
        "\0".parent::class."\0".'distance' => [parent::class, 'distance', null],
        "\0".parent::class."\0".'end_time' => [parent::class, 'end_time', null],
        "\0".parent::class."\0".'id' => [parent::class, 'id', null],
        "\0".parent::class."\0".'latitude' => [parent::class, 'latitude', null],
        "\0".parent::class."\0".'longitude' => [parent::class, 'longitude', null],
        "\0".parent::class."\0".'outlet_code' => [parent::class, 'outlet_code', null],
        "\0".parent::class."\0".'outlet_ifs' => [parent::class, 'outlet_ifs', null],
        "\0".parent::class."\0".'outlet_name' => [parent::class, 'outlet_name', null],
        "\0".parent::class."\0".'start_time' => [parent::class, 'start_time', null],
        "\0".parent::class."\0".'town' => [parent::class, 'town', null],
        "\0".parent::class."\0".'users' => [parent::class, 'users', null],
        "\0".parent::class."\0".'zip_code' => [parent::class, 'zip_code', null],
        'address' => [parent::class, 'address', null],
        'created_by' => [parent::class, 'created_by', null],
        'date_created' => [parent::class, 'date_created', null],
        'distance' => [parent::class, 'distance', null],
        'end_time' => [parent::class, 'end_time', null],
        'id' => [parent::class, 'id', null],
        'latitude' => [parent::class, 'latitude', null],
        'longitude' => [parent::class, 'longitude', null],
        'outlet_code' => [parent::class, 'outlet_code', null],
        'outlet_ifs' => [parent::class, 'outlet_ifs', null],
        'outlet_name' => [parent::class, 'outlet_name', null],
        'start_time' => [parent::class, 'start_time', null],
        'town' => [parent::class, 'town', null],
        'users' => [parent::class, 'users', null],
        'zip_code' => [parent::class, 'zip_code', null],
    ];

    public function __isInitialized(): bool
    {
        return isset($this->lazyObjectState) && $this->isLazyObjectInitialized();
    }

    public function __serialize(): array
    {
        $properties = (array) $this;
        unset($properties["\0" . self::class . "\0lazyObjectState"]);

        return $properties;
    }
}
