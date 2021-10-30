<?php
namespace vale;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use vale\session\SessionManager;

class Main extends PluginBase implements Listener {

    private SessionManager $sessionManager;

    private static Main $instance;

    public function onEnable(): void{
        if(!is_dir($this->getDataFolder() . "sessions")) @mkdir($this->getDataFolder() . "sessions");
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->sessionManager = new SessionManager();
    }

    public function onJoin(PlayerJoinEvent $event)
    {
          $player = $event->getPlayer();
            $session = SessionManager::createSession($player);
			$session->load();
        }


    public function onQuit(PlayerQuitEvent $event){
            $player = $event->getPlayer();
            $session = SessionManager::createSession($event->getPlayer());
            $session->save(); //save the data
            $this->sessionManager->removeSession($player); ///remove the session when quit
        }

    public function onChat(PlayerChatEvent $event){
        $session = SessionManager::createSession($event->getPlayer());
        $event->setMessage($event->getPlayer()->getName()  . " " .  $session->getKills()); //example on how to call methods from session
    }

    public function getSessionManager(): SessionManager{
        return $this->sessionManager;
    }

    /**
     * @return static
     */
    public static function getInstance(): self{
        return self::$instance;
    }

}
