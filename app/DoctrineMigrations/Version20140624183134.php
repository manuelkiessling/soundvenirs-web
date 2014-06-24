<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140624183134 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE Sound (id VARCHAR(6) NOT NULL, title VARCHAR(128) NOT NULL, lat DOUBLE PRECISION DEFAULT NULL, long DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id));');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE Sound');
    }
}
