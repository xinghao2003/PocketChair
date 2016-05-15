<?php
namespace PocketChair;

use pocketmine\Server;


use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\network\protocol\Info as ProtocolInfo;
use pocketmine\network\protocol\RemoveEntityPacket;
use pocketmine\network\protocol\SetEntityLinkPacket;
use pocketmine\network\protocol\PlayerActionPacket;
use pocketmine\Player;
class PocketChair extends PluginBase implements Listener{	
public function onEnable(){					
 $this->getServer()->getPluginManager()->registerEvents($this, $this);
$this->count = 10000;

}
public function onPacketReceived(DataPacketReceiveEvent $event){
$pk = $event->getPacket();
switch($pk::NETWORK_ID){
case ProtocolInfo::PLAYER_ACTION_PACKET;
$player = $event->getPlayer();
if(isset($this->counter[$player->getName()])){
if($pk->action === 8){$ppk = new SetEntityLinkPacket();
$ppk->from = $this->counter[$player->getName()];
$ppk->to = 0;$ppk->type = 0;
$player->dataPacket($ppk);
$pkc = new SetEntityLinkPacket();
$pkc->from = $this->counter[$player->getName()];
$pkc->to = $player->getId();$pkc->type = 0;
$ps=Server::getInstance()->getOnlinePlayers();
Server::broadcastPacket($ps,$pkc);
$pk0 = new RemoveEntityPacket();
$pk0->eid = $this->counter[$player->getName()];
$ps=Server::getInstance()->getOnlinePlayers();
Server::broadcastPacket($ps,$pk0);
unset($this->counter[$player->getName()]);
}
}
}
}
public function onTouch(PlayerInteractEvent $event){
if($event->getBlock()->getID() === 53){
$player = $event->getPlayer();
$pk = new AddEntityPacket();$this->count = $this->count + 1;
$pk->eid= $this->count;
$pk->type = 84;$pk->x= $event->getBlock()->x + 0.6;$pk->y = $event->getBlock()->y +0.6;$pk->z = $event->getBlock()->z + 0.5;
$pk->speedX = 0;$pk->speedY = 0;$pk->speedZ = 0;
$pk->yaw = $player->yaw;
$pk->pitch = $player->pitch;
$pk->metadata = [0 => [0,1<<5],2 =>[Entity::DATA_TYPE_STRING,"" ],
Entity::DATA_SHOW_NAMETAG =>[0,1],15=>[ 0,1 ] ];
$ps=Server::getInstance()->getOnlinePlayers();
Server::broadcastPacket($ps,$pk);
$ppk = new SetEntityLinkPacket();
$ppk->from = $this->count;$ppk->to = 0;
$ppk->type = 2;$player->dataPacket($ppk);
$pkc = new SetEntityLinkPacket();$pkc->from = $this->count;
$pkc->to = $player->getId();$pkc->type = 2;
$ps=Server::getInstance()->getOnlinePlayers();
Server::broadcastPacket($ps,$pkc);
$this->counter[$player->getName()] = $this->count;
}
}
}
  