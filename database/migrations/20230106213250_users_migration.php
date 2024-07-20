<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UsersMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $exists = $this->hasTable('users');

        if (!$exists) {
            $this->execute('CREATE TABLE `users` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `name` char(64) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;');
        }
    }
}
