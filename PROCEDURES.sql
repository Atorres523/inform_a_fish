DROP PROCEDURE IF EXISTS RegisterFisherman;

DELIMITER //

CREATE PROCEDURE RegisterFisherman(FLname varchar(100), Uname varchar(100), Pass varchar(100), type varchar(10))
BEGIN

	start transaction;

	select count(*) into @usernameCount
	from Fisherman
	where Uname = Username;


	if @usernameCount > 0 then
		SELECT NULL as Uname, "Username already exists" AS 'Error';
	else

		insert into Fisherman (Name, Username, Password) values (FLname, Uname, Pass);
		insert into FishermanType (Username, Type) values (Uname, type);
		-- select last_insert_id() as userid, NULL as 'Error';

	end if;

	commit;
    
END;
//
DELIMITER ;


DROP PROCEDURE IF EXISTS FishermanCatches;

DELIMITER //

CREATE PROCEDURE FishermanCatches(User varchar(100), ID int, Fish varchar(100), Date DATETIME, Size int, Stock varchar(20), bait varchar(100), spotID varchar(50))
BEGIN

	INSERT INTO Catches (Username, BodyID, FishName, DateCaught, SizeCaught, NativeOrStocked, Bait, SpotID)
	VALUES (User, ID, Fish, Date, Size, Stock, bait, spotID);

END;
//
DELIMITER ;

DROP PROCEDURE IF EXISTS DropFisherman;

DELIMITER //

CREATE PROCEDURE DropFisherman(Uname varchar(100))
BEGIN

	start transaction;

	select count(*) into @usernameCount
	from Fisherman
	where Uname = Username;

	if @usernameCount > 0 then
		DELETE FROM Fisherman WHERE Uname = Username;
	else
		SELECT NULL as Uname, "Username does not exist" AS 'Error';
	end if;

	commit;
    
END;
//
DELIMITER ;

DROP PROCEDURE IF EXISTS TopCaughtFish;

DELIMITER //

CREATE PROCEDURE TopCaughtFish(BodyName varchar(100), StartDate DateTime, EndDate DateTime)
BEGIN
    SELECT COUNT(*) INTO @NameCount
    FROM Body
    WHERE BodyName = Name;

    IF @NameCount > 0 THEN
        SELECT FishName, Name, COUNT(FishName) AS NumCaught
        FROM Catches INNER JOIN Body ON Catches.BodyID = Body.BodyID
        WHERE Body.Name = BodyName
         AND DateCaught >= StartDate AND DateCaught <= EndDate
        GROUP BY FishName
        LIMIT 10;
    ELSE
        SELECT NULL as BodyName, "Not in database" AS 'Error';
	END IF;

END;
//
DELIMITER ;

DROP PROCEDURE IF EXISTS fishInBody;

DELIMITER //

CREATE PROCEDURE fishInBody(id int) -- Lists Fish in a particular body
BEGIN

    SELECT Swims.FishName 
	FROM Body inner join Swims inner join Fish 
	ON Swims.BodyID = Body.BodyID and Swims.FishName = Fish.FishName
	WHERE Body.BodyID = id
	GROUP BY Swims.FishName;

END;
//
DELIMITER ;

DROP PROCEDURE IF EXISTS specificFish;

DELIMITER //

CREATE PROCEDURE specificFish(fish varchar(100)) -- Lists Bodies that have a specific fish
BEGIN

    SELECT Body.Name
	FROM Body inner join Swims inner join Fish 
	ON Swims.BodyID = Body.BodyID and Swims.FishName = Fish.FishName
	WHERE Swims.FishName = fish
	GROUP BY Swims.BodyID;

END;
//
DELIMITER ;

