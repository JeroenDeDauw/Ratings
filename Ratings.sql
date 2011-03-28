-- MySQL version of the database schema for the Ratings extension.

-- Special translations table.
CREATE TABLE IF NOT EXISTS /*$wgDBprefix*/votes (
  user_id                 INT(10) unsigned   NOT NULL,
  page_id                 INT(10) unsigned   NOT NULL,
  prop_id                 INT(5) unsigned    NOT NULL,
  vote_value              INT(4) unsigned    NOT NULL,
  vote_time               CHAR(14) binary    NOT NULL default '',
  PRIMARY KEY  ('user_id','page_id','prop_id')
) /*$wgDBTableOptions*/; 

-- Table to keep track of translation memories for the special words.
CREATE TABLE IF NOT EXISTS /*$wgDBprefix*/vote_props (
  prop_id                 INT(5) unsigned   NOT NULL auto_increment PRIMARY KEY,
  prop_name               VARCHAR(255)      NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX prop_name ON /*$wgDBprefix*/vote_props (prop_name);