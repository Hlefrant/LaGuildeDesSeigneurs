<?php


namespace App\Service;

use App\Entity\Player;

interface PlayerServiceInterface
{
    /**
     * Creates the player
     */

    public function create(string $data);
    public function modify(Player $player, string $data);
    public function delete(Player $player);
}
