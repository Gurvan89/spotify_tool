<?php
namespace SpotifyApp\Database;

interface InterfaceDatabase
{
    /**
     * To delete an object in corresponding table
     *
     * @param integer $id
     * @return void
     */
    function delete(int $id): void;

    /**
     * Get object by Id
     *
     * @param integer $id
     * @return object
     */
    function getById(int $id);
}
