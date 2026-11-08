CREATE TABLE Follower.Users
(
	id INT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME NULL,
    
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
        
    PRIMARY KEY (id)
);