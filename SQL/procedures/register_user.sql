DROP PROCEDURE IF EXISTS register_user;

DELIMITER $$

CREATE PROCEDURE register_user(
	IN usertypecode VARCHAR(10),
    IN emailaddress VARCHAR(200),
    IN password VARCHAR(200),
    IN forename VARCHAR(200),
    IN surname VARCHAR(200),
    IN registerconfirmcode VARCHAR(100)
)
BEGIN

	IF NOT EXISTS (
		SELECT	1
		FROM	users
		WHERE	emailaddress = emailaddress
	) 
    THEN

		INSERT INTO
			users
		(
			usertypeid,
			emailaddress,
			password,
			forename,
			surname,
			dateregistered,
			registerconfirmcode,
			passwordresetcode
		)
		VALUES
		(
			( SELECT usertypeid FROM usertypes WHERE usertypecode = usertypecode ),
			emailaddress,
			password,
			forename,
			surname,
			NOW(),
			registerconfirmcode,
			null
		);
        
        SELECT	1 as success,
				registerconfirmcode;
        
	ELSE
    
		SELECT 	0 as success,
				'Email address already registered' as error_message;	
	
	END IF;

END$$

DELIMITER ;