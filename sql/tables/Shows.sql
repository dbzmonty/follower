CREATE TABLE Follower.Shows
(
	id INT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME,
    
    show_id INT NOT NULL,
    show_name VARCHAR(255) NOT NULL,
    last_ep_watched DATETIME NULL,
    show_finished BOOL NOT NULL DEFAULT FALSE,
    
    CONSTRAINT uc_show_id UNIQUE (show_id),
    PRIMARY KEY (id)
);
