
CREATE TABLE IF NOT EXISTS users (
    uuid_bin BINARY(16) NOT NULL PRIMARY KEY,
    uuid VARCHAR(36) GENERATED ALWAYS AS(
        BIN_TO_UUID(uuid_bin)
    ) VIRTUAL NOT NULL,
    nickname VARCHAR(64) NOT NULL,
    thumbnail VARCHAR(128),
    UNIQUE INDEX(nickname)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS game_statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_uuid_bin BINARY(16) NOT NULL,
    score TINYINT(3) UNSIGNED,
    game_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_uuid_bin) REFERENCES users(uuid_bin)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX(score DESC)
) ENGINE=InnoDB;

CREATE VIEW game_statistics_view AS (
    SELECT id, uuid_bin, uuid, thumbnail, nickname, score, game_date
    FROM game_statistics JOIN users ON user_uuid_bin=users.uuid_bin
);
