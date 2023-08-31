DROP TABLE IF EXISTS items;
CREATE TABLE items
(
    `id`              smallint unsigned NOT NULL auto_increment,
    `publicationDate` datetime NOT NULL,                              # When the item was published
    `title`           varchar(255) NOT NULL,                      # Full title of the item
    `summary`         text NOT NULL,                              # A short summary of the item
    `content`         mediumtext NOT NULL,                        # The HTML content of the item
    
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS options;
CREATE TABLE options
(
    `id`              smallint unsigned NOT NULL auto_increment,
    `key`             varchar(255) NOT NULL,
    `value`           varchar(255) NOT NULL,

    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS users;
CREATE TABLE users
(
    `id`              smallint unsigned NOT NULL auto_increment,
    `modifiedDate`    datetime NOT NULL,
    `username`        varchar(255) NOT NULL,
    `password`        text NOT NULL,
    `role`            varchar(255) NOT NULL,
    `firstname`       varchar(255) NOT NULL,
    `lastname`        varchar(255) NOT NULL,
    `email`           varchar(255) NOT NULL,

    PRIMARY KEY (`id`)
);