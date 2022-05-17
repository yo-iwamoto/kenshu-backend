<?php

class PDOFactory
{
    public static function create()
    {
        return new PDO(
            // TODO: 環境の switch
            "pgsql:host=db;dbname={$_ENV['POSTGRES_DEV_DATABASE']};",
            $_ENV['POSTGRES_DEV_USER'],
            $_ENV['POSTGRES_DEV_PASSWORD'],
        );
    }
}
