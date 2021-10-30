<?php
namespace vale\session;

use pocketmine\player\Player;
use vale\Main;

class SessionManager{

    private static array $session = []; //

    /**
     * @param Player $player
     * @return Session
     */
    public static function createSession(Player $player): Session{
        if(!isset(self::$session[$player->getName()])){ //if the player does not have a session we create one
            self::$session[$player->getName()] = new Session($player); //Then we return the session
        }
        return self::$session[$player->getName()];

    }

    /***
     * @param Player $player
     * @return bool
     */
    //Checks if they have a Session & if the file exists
    public static function hasSession(Player $player): bool
    {
        $name = $player->getName();
        if (file_exists(Main::getInstance()->getDataFolder() . "sessions" . DIRECTORY_SEPARATOR . $name . ".yml")) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Player $player
     */
    public static function removeSession(Player $player){
        if(isset(self::$session[$player->getName()])){
            $session = self::createSession($player);
            $session->save(); //we save the session
            unset(self::$session[$player->getName()]);
        }
    }
}
