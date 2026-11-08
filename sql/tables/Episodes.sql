CREATE TABLE Follower.Episodes
(
	id INT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME NULL,
    
    show_id INT NOT NULL,
    episode_id INT NULL,    
    episode_name VARCHAR(255) NULL,
    season_number INT NOT NULL,
    episode_number INT NOT NULL,
    first_aired DATE NULL,
    is_watched BOOL NOT NULL DEFAULT FALSE,
    
    CONSTRAINT fk_episode FOREIGN KEY (show_id) REFERENCES Follower.Shows(show_id),    
    PRIMARY KEY (id)
);
