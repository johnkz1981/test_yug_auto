CREATE TABLE `me_flowers` (
                                 `ID` INT(11) NOT NULL AUTO_INCREMENT,
                                 `TITLE` VARCHAR(256) NOT NULL,
                                 `QUANTITY` INT(11) NOT NULL,
                                 `PRICE` FLOAT NOT NULL,
                                 `SORT` INT(11) DEFAULT 500,
                                 `CREATED` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                 PRIMARY KEY(ID)
);