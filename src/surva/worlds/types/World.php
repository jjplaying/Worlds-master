<?php
/**
 * Worlds | world config class file
 */

namespace surva\worlds\types;

use surva\worlds\Worlds;
use pocketmine\utils\Config;

class World {
    /* @var Worlds */
    private $worlds;

    /* @var Config */
    private $config;

    /* @var string|null */
    protected $permission;

    /* @var int|null */
    protected $gamemode;

    /* @var bool|null */
    protected $build;

    /* @var bool|null */
    protected $pvp;

    /* @var bool|null */
    protected $damage;

    /* @var bool|null */
    protected $interact;

    /* @var bool|null */
    protected $explode;

    /* @var bool|null */
    protected $drop;

    /* @var bool|null */
    protected $hunger;

    /* @var bool|null */
    protected $fly;

    /* @var bool|null */
    protected $daylightcycle;

    public function __construct(Worlds $worlds, Config $config) {
        $this->worlds = $worlds;
        $this->config = $config;

        $this->loadItems();
    }

    /**
     * Load all possible config values
     */
    public function loadItems(): void {
        $this->loadValue("permission");
        $this->loadValue("gamemode");
        $this->loadValue("build");
        $this->loadValue("pvp");
        $this->loadValue("damage");
        $this->loadValue("interact");
        $this->loadValue("explode");
        $this->loadValue("drop");
        $this->loadValue("hunger");
        $this->loadValue("fly");
        $this->loadValue("daylightcycle");
    }

    /**
     * Load value from config
     *
     * @param string $name
     */
    public function loadValue(string $name): void {
        if(!$this->getConfig()->exists($name)) {
            $defVal = $this->getWorlds()->getDefaults()->getValue($name);

            $this->$name = $defVal;

            return;
        }

        switch($this->getConfig()->get($name)) {
            case "true":
                $this->$name = true;
                break;
            case "false":
                $this->$name = false;
                break;
            default:
                $this->$name = $this->getConfig()->get($name);
                break;
        }
    }

    /**
     * Update a config value
     *
     * @param string $name
     * @param string $value
     */
    public function updateValue(string $name, string $value): void {
        $this->getConfig()->set($name, $value);

        $this->getConfig()->save();
        $this->loadItems();
    }

    /**
     * Remove a config value
     *
     * @param string $name
     */
    public function removeValue(string $name): void {
        if(!$this->getConfig()->exists($name)) {
            return;
        }

        $this->getConfig()->remove($name);

        $this->getConfig()->save();
        $this->loadItems();
    }

    /**
     * @return bool|null
     */
    public function getDaylightCycle(): ?bool {
        return $this->daylightcycle;
    }

    /**
     * @return bool|null
     */
    public function getFly(): ?bool {
        return $this->fly;
    }

    /**
     * @return bool|null
     */
    public function getHunger(): ?bool {
        return $this->hunger;
    }

    /**
     * @return bool|null
     */
    public function getDrop(): ?bool {
        return $this->drop;
    }

    /**
     * @return bool|null
     */
    public function getExplode(): ?bool {
        return $this->explode;
    }

    /**
     * @return bool|null
     */
    public function getDamage(): ?bool {
        return $this->damage;
    }

    /**
     * @return bool|null
     */
    public function getInteract(): ?bool {
        return $this->interact;
    }

    /**
     * @return bool|null
     */
    public function getPvp(): ?bool {
        return $this->pvp;
    }

    /**
     * @return bool|null
     */
    public function getBuild(): ?bool {
        return $this->build;
    }

    /**
     * @return int|null
     */
    public function getGamemode(): ?int {
        return $this->gamemode;
    }

    /**
     * @return string|null
     */
    public function getPermission(): ?string {
        return $this->permission;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config {
        return $this->config;
    }

    /**
     * @return Worlds
     */
    public function getWorlds(): Worlds {
        return $this->worlds;
    }
}
