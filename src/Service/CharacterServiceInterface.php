<?php
namespace App\Service;

use App\Entity\Character;

interface CharacterServiceInterface
{
    /**
     * Creates the character
     */

    public function create(string $data);

    /**
     * @param Character $character
     * @return mixed
     */
    public function modify(Character $character, string $data);

    /**
     * @param Character $character
     * @return mixed
     */
    public function delete(Character $character);


    /**
     * @param Character $character
     * @return mixed
     */
    public function isEntityFilled(Character $character);

    /**
     * @param Character $character
     * @return mixed
     */
    public function submit(Character $character, $formName, $data);

    /**
     * Creates the character from html form
     */
    public function createFromHtml(Character $character);

    /**
     * Modifies the character from html form
     */
    public function modifyFromHtml(Character $character);

    /**
     * Get all Characters
     */
    public function getAll();

    /**
     * Get all Characters by intelligence
     */
    public function getAllByIntelligence(int $intelligence);
}
