<?php
/**
 * Worlds | set parameter command
 */

namespace surva\worlds\commands;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use surva\worlds\form\WorldSettingsForm;

class SetCommand extends CustomCommand {
    public function do(Player $player, array $args) {
        $folderName = $player->getLevel()->getFolderName();

        if(!($world = $this->getWorlds()->getWorldByName($folderName))) {
            $player->sendMessage($this->getWorlds()->getMessage("general.world.notloaded"));

            return true;
        }

        if(count($args) === 0) {
            $wsForm = new WorldSettingsForm($this->getWorlds(), $folderName, $world);

            $player->sendForm($wsForm);

            return true;
        }

        if($args[0] === "legacy") {
            $player->sendMessage(
                $this->getWorlds()->getMessage(
                    "set.list.values",
                    array(
                        "name" => $folderName,
                        "permission" => $this->formatText($world->getPermission()),
                        "gamemode" => $this->formatGamemode($world->getGamemode()),
                        "build" => $this->formatBool($world->getBuild()),
                        "pvp" => $this->formatBool($world->getPvp()),
                        "damage" => $this->formatBool($world->getDamage()),
                        "interact" => $this->formatBool($world->getInteract()),
                        "explode" => $this->formatBool($world->getExplode()),
                        "drop" => $this->formatBool($world->getDrop()),
                        "hunger" => $this->formatBool($world->getHunger()),
                        "fly" => $this->formatBool($world->getFly()),
                        "daylightcycle" => $this->formatBool($world->getDaylightCycle()),
                    )
                )
            );

            return true;
        }

        if(!(count($args) === 2)) {
            return false;
        }

        if(!(in_array(
            $args[0],
            array("permission", "gamemode", "build", "pvp", "damage", "interact", "explode", "drop", "hunger", "fly", "daylightcycle")
        ))) {
            return false;
        }

        if($args[0] === "permission") {
            if($this->getWorlds()->getServer()->getDefaultLevel()->getFolderName() === $folderName) {
                $player->sendMessage($this->getWorlds()->getMessage("set.permission.notdefault"));

                return true;
            }

            $world->updateValue($args[0], $args[1]);

            $player->sendMessage(
                $this->getWorlds()->getMessage(
                    "set.success",
                    array("world" => $player->getLevel()->getFolderName(), "key" => $args[0], "value" => $args[1])
                )
            );
        } elseif($args[0] === "gamemode") {
            if(($args[1] = Server::getGamemodeFromString($args[1])) === -1) {
                $player->sendMessage($this->getWorlds()->getMessage("set.gamemode.notexist"));

                return true;
            }

            $world->updateValue($args[0], $args[1]);

            $player->sendMessage(
                $this->getWorlds()->getMessage(
                    "set.success",
                    array("world" => $player->getLevel()->getFolderName(), "key" => $args[0], "value" => $args[1])
                )
            );
        } else {
            if(!(in_array($args[1], array("true", "false")))) {
                $player->sendMessage($this->getWorlds()->getMessage("set.notbool", array("key" => $args[0])));

                return true;
            }

            $world->updateValue($args[0], $args[1]);

            $player->sendMessage(
                $this->getWorlds()->getMessage(
                    "set.success",
                    array("world" => $player->getLevel()->getFolderName(), "key" => $args[0], "value" => $args[1])
                )
            );
        }

        return true;
    }

    /**
     * Format a text for showing its value
     *
     * @param string|null $value
     * @return string
     */
    private function formatText(?string $value): string {
        if($value === null) {
            return $this->getWorlds()->getMessage("set.list.notset");
        }

        return TextFormat::WHITE . $value;
    }

    /**
     * Format a gamemode for showing its value
     *
     * @param int|null $value
     * @return string
     */
    private function formatGamemode(?int $value): string {
        if($value === null) {
            return $this->getWorlds()->getMessage("set.list.notset");
        }

        return $this->getWorlds()->getServer()->getLanguage()->translateString(
            TextFormat::WHITE . Server::getGamemodeString($value)
        );
    }

    /**
     * Format a boolean for showing its value
     *
     * @param bool|null $value
     * @return string
     */
    private function formatBool(?bool $value): string {
        if($value === true) {
            return TextFormat::GREEN . "true";
        } elseif($value === false) {
            return TextFormat::RED . "false";
        } else {
            return $this->getWorlds()->getMessage("set.list.notset");
        }
    }
}
