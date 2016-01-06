DROP FUNCTION IF EXISTS calculateTheDistance;

DELIMITER //

CREATE FUNCTION calculateTheDistance(fA DECIMAL(10,8), lA DECIMAL(11,8),fB DECIMAL(10,8), lB DECIMAL(11,8)) RETURNS INT DETERMINISTIC
BEGIN
	SET @M_PI  = PI();
	SET @lat1 = fA * @M_PI / 180;
    SET @lat2 = fB * @M_PI / 180;
    SET @long1 = lA * @M_PI / 180;
    SET @long2 = lB * @M_PI / 180;
    
    SET @cl1 = COS(@lat1);
    SET @cl2 = COS(@lat2);
    SET @sl1 = SIN(@lat1);
    SET @sl2 = SIN(@lat2);
    SET @delta = @long2 - @long1;
    SET @cdelta = COS(@delta);
    SET @sdelta = SIN(@delta);
	
	SET @y = sqrt(pow(@cl2 * @sdelta, 2) + pow(@cl1 * @sl2 - @sl1 * @cl2 * @cdelta, 2));
	SET @x = @sl1 * @sl2 + @cl1 * @cl2 * @cdelta;
 
    SET @ad = atan2(@y, @x);
    SET @dist = @ad * 6372795;

RETURN @dist;
END
//

DELIMITER ;

#SELECT calculateTheDistance(77.1539, -139.398, -77.1804, -139.55);

#							CURRENT POSITION 	Latitude	Longtitude,	DB Fields					Entfernung aus dem FIlter in meter
SELECT * FROM offers WHERE calculateTheDistance(50.76774370, 6.09138290, latitude, longtitude) <= 65000