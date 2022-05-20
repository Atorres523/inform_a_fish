DROP VIEW IF EXISTS CatchesTable;
DROP VIEW IF EXISTS MostFishCaught;
DROP VIEW IF EXISTS morningFish;

CREATE VIEW CatchesTable AS
SELECT Fisherman.Name as FLname, 
Catches.Username, 
FishermanType.Type as FishermanType, 
Catches.FishName, Body.Name as Bname, 
DateCaught, 
SizeCaught, 
NativeOrStocked, 
Bait, 
SpotID 
from Catches inner join Fisherman inner join FishermanType inner join Body
on Catches.BodyID = Body.BodyID and Catches.Username = Fisherman.Username and Fisherman.Username = FishermanType.Username 
order by Datecaught DESC;


CREATE VIEW MostFishCaught AS
select Fisherman.Name as Name, Fisherman.Username as Username, COUNT(FishName) as Amount 
from Catches inner join Fisherman 
on Fisherman.Username = Catches.Username 
group by Username 
order by Amount DESC 
limit 1;

CREATE VIEW morningFish AS
SELECT * 
FROM Fish
WHERE TOD = "morning";