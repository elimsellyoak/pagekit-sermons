<?php

use beejjacobs\Sermons\Model\BibleBook;
use beejjacobs\Sermons\SermonsModule;

return [
    'install' => function ($app) {
      /**
       * @var \Pagekit\Application $app
       * @var \Pagekit\Database\Utility $util
       */
      $util = $app['db']->getUtility();

      if ($util->tableExists('@sermons_sermons') === false) {
        $util->createTable('@sermons_sermons', function ($table) {
          /**
           * @var Doctrine\DBAL\Schema\Table $table
           */
          $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
          $table->addColumn('status', 'smallint');
          $table->addColumn('title', 'string', ['length' => 255]);
          $table->addColumn('slug', 'string', ['length' => 255]);
          $table->addColumn('date', 'datetime');
          $table->addColumn('mp3_source', 'string', ['length' => 512]);
          $table->addColumn('preacher_id', 'integer', ['unsigned' => true, 'length' => 10]);
          $table->addColumn('bible_passage', 'string', ['length' => 100]);
          $table->addColumn('description', 'text');
          $table->addColumn('sermon_series_id', 'integer', ['unsigned' => true, 'length' => 10]);
          $table->addColumn('sermon_notes', 'text');
          $table->addColumn('data', 'json_array', ['notnull' => false]);

          $table->setPrimaryKey(['id']);
        });
      }

      if ($util->tableExists('@sermons_series') === false) {
        $util->createTable('@sermons_series', function ($table) {
          /**
           * @var Doctrine\DBAL\Schema\Table $table
           */
          $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
          $table->addColumn('name', 'string', ['length' => 255]);
          $table->addColumn('sermon_count', 'integer', ['default' => 0]);

          $table->setPrimaryKey(['id']);
        });
      }

      if ($util->tableExists('@sermons_preachers') === false) {
        $util->createTable('@sermons_preachers', function ($table) {
          /**
           * @var Doctrine\DBAL\Schema\Table $table
           */
          $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
          $table->addColumn('name', 'string', ['length' => 255]);
          $table->addColumn('sermon_count', 'integer', ['default' => 0]);

          $table->setPrimaryKey(['id']);
        });
      }

      if ($util->tableExists('@sermons_topics') === false) {
        $util->createTable('@sermons_topics', function ($table) {
          /**
           * @var Doctrine\DBAL\Schema\Table $table
           */
          $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
          $table->addColumn('name', 'string', ['length' => 255]);
          $table->addColumn('sermon_count', 'integer', ['default' => 0]);

          $table->setPrimaryKey(['id']);
        });
      }

      if ($util->tableExists('@sermons_sermon_topics') === false) {
        $util->createTable('@sermons_sermon_topics', function ($table) {
          /**
           * @var Doctrine\DBAL\Schema\Table $table
           */
          $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
          $table->addColumn('sermon_id', 'integer', ['unsigned' => true, 'length' => 10, 'default' => 0]);
          $table->addColumn('topic_id', 'integer', ['unsigned' => true, 'length' => 10, 'default' => 0]);

          $table->setPrimaryKey(['id']);
        });
      }

      if ($util->tableExists('@sermons_bible_books') === false) {
        $util->createTable('@sermons_bible_books', function ($table) {
          /**
           * @var Doctrine\DBAL\Schema\Table $table
           */
          $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
          $table->addColumn('name', 'string', ['length' => 255]);
          $table->addColumn('sermon_count', 'integer', ['default' => 0]);

          $table->setPrimaryKey(['id']);

        });
        foreach (SermonsModule::BOOKS_OF_THE_BIBLE as $book) {
          BibleBook::create(['name' => $book])->save();
        }
      }

      if ($util->tableExists('@sermons_sermon_bible_books') === false) {
        $util->createTable('@sermons_sermon_bible_books', function ($table) {
          /**
           * @var Doctrine\DBAL\Schema\Table $table
           */
          $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
          $table->addColumn('sermon_id', 'integer', ['unsigned' => true, 'length' => 10, 'default' => 0]);
          $table->addColumn('bible_book_id', 'integer', ['unsigned' => true, 'length' => 10, 'default' => 0]);

          $table->setPrimaryKey(['id']);
        });
      }
    },
    'enable' => function ($app) {

    },
    'uninstall' => function ($app) {
      /**
       * @var \Pagekit\Application $app
       * @var \Pagekit\Database\Utility $util
       */
      $util = $app['db']->getUtility();

      foreach (SermonsModule::DB_TABLES as $table) {
        if ($util->tableExists($table)) {
          $util->dropTable($table);
        }
      }
    },
    'updates' => [

    ]
];
