<?php
namespace vale\session;

use pocketmine\player\Player;
use pocketmine\Server;
use vale\Main;

class Session{

    public array $data = []; //the data which will be saved in the Session

	public Player $player;
     //data to insert into session
    public const DATA = [
        "money" => 0,
        "kills" => 0,
    ];

    public function __construct(Player $player){
        $this->player = $player;
		var_dump($this->player->getName()); //debug
        if(
            file_exists(Main::getInstance()->getDataFolder() . "sessions" . DIRECTORY_SEPARATOR . $player->getName() . ".yml")
        ){
            //ik its aids typing this all out u can make a func to get the path
            $this->data = yaml_parse_file(Main::getInstance()->getDataFolder() . "sessions" . DIRECTORY_SEPARATOR . $player->getName() . ".yml");
        }
    }

    /**
     * @return string
     */

    public function getPlayerName(): string{
        return $this->player->getName();
    }

    /**
     * @return Player|null
     */
    public function getPlayer(): ?Player{
        return Server::getInstance()->getPlayerExact($this->getPlayerName());
    }

    public function register(): void{
            foreach (self::DATA as $key => $val){
               $this->data[$key] = $val;
            }
    }

	public function load(): void{
		if(!$this->getPlayer()->hasPlayedBefore()){
			$this->register();
			$this->getPlayer()->sendMessage("HI ur new"); //if player is new we do this
		}
		$this->getPlayer()->sendMessage("welcome back"); //if not we do this
	}

    public function save(): void{
        yaml_emit_file(Main::getInstance()->getDataFolder() . "sessions" . DIRECTORY_SEPARATOR . $this->getPlayerName() . ".yml", $this->data);
    }

    public function setKills(int $value){
		$this->data["kills"] = $value;  // $data is just an array we can access its keys such as kills
    }
	/**
	 * @param int $value
	 */
	public function addKills(int $value): void{
		$kills = $this->getKills();
		$this->setKills($this->getKills() + $value);
	}

    public function getKills(): int{
        return $this->data["kills"];
    }
}
