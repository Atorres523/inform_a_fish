DROP TRIGGER IF EXISTS FishermanDelete;
DROP TRIGGER IF EXISTS FishermanUpdate;
DROP TRIGGER IF EXISTS SwimsInsert;
DROP TRIGGER IF EXISTS fishSwims;

DELIMITER //
/*saves user account info and stored in Fisherman_History before deleting*/
CREATE TRIGGER FishermanDelete
BEFORE DELETE ON Fisherman 
FOR EACH ROW
BEGIN
    INSERT INTO Fisherman_History (Name, Username, Password)
    SELECT Name, Username, Password
    FROM Fisherman
    WHERE Fisherman.Username = OLD.Username;
END
//
/*saves previous user account info before changes are made to account and stores old info in Fisherman_History*/
CREATE TRIGGER FishermanUpdate
BEFORE UPDATE ON Fisherman
FOR EACH ROW
BEGIN
    IF OLD.Username != NEW.Username or OLD.Password != NEW.Password
    THEN
        INSERT INTO Fisherman_History 
        VALUES (OLD.Username, OLD.Password, NOW());
    END IF;
END
//

CREATE TRIGGER SwimsInsert
AFTER INSERT ON Swims
FOR EACH ROW
BEGIN
    INSERT INTO Swims_History (SwimsID, FishName, BodyID, LastStockDate)
    SELECT * from Swims order by SwimsID DESC limit 1;
END
//

DELIMITER ;


DELIMITER //
CREATE TRIGGER fishSwims
AFTER INSERT ON Catches
FOR EACH ROW
BEGIN
    CALL FishBody(NEW.FishName, NEW.BodyID);
   
END
//

DELIMITER ;
