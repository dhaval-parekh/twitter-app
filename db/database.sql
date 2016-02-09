CREATE TABLE IF NOT EXISTS usermaster
(
screen_name VARCHAR(256) PRIMARY KEY,
tweets BLOB DEFAULT NULL,
tweets_lastupdate TIMESTAMP,
followers BLOB DEFAULT NULL,
followers_lastupdate TIMESTAMP 
);